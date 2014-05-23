<?php

namespace Searchperience\Api\Client\System\Storage;

use Searchperience\Api\Client\Domain\Synonym\Synonym;
use Searchperience\Api\Client\Domain\Synonym\SynonymCollection;
use Searchperience\Common\Http\Exception\EntityNotFoundException;

class RestSynonymBackend extends AbstractRestBackend implements SynonymBackendInterface {

	/**
	 * @var string
	 */
	protected $endpoint = 'synonyms';

	/**
	 * @throws \Searchperience\Common\Http\Exception\InternalServerErrorException
	 * @throws \Searchperience\Common\Http\Exception\ForbiddenException
	 * @throws \Searchperience\Common\Http\Exception\ClientErrorResponseException
	 * @throws \Searchperience\Common\Http\Exception\UnauthorizedException
	 * @throws \Searchperience\Common\Http\Exception\MethodNotAllowedException
	 * @throws \Searchperience\Common\Http\Exception\RequestEntityTooLargeException
	 * @return \Searchperience\Api\Client\Domain\Synonym\SynonymCollection
	 */
	public function getAll() {
		try {
			$response   = $this->getGetResponseFromEndpoint();
			$xmlElement = $response->xml();
		} catch (EntityNotFoundException $e) {
			return new SynonymCollection();
		}

		return $this->buildSynonymsFromXml($xmlElement);
	}

	/**
	 * @param string $tagName
	 * @throws \Searchperience\Common\Http\Exception\InternalServerErrorException
	 * @throws \Searchperience\Common\Http\Exception\ForbiddenException
	 * @throws \Searchperience\Common\Http\Exception\ClientErrorResponseException
	 * @throws \Searchperience\Common\Http\Exception\UnauthorizedException
	 * @throws \Searchperience\Common\Http\Exception\MethodNotAllowedException
	 * @throws \Searchperience\Common\Http\Exception\RequestEntityTooLargeException
	 * @return \Searchperience\Api\Client\Domain\Synonym\SynonymCollection
	 */
	public function getAllByTag($tagName) {
		try {
			$response   = $this->getGetResponseFromEndpoint('/'.$tagName);
			$xmlElement = $response->xml();
		} catch (EntityNotFoundException $e) {
			return new SynonymCollection();
		}

		return $this->buildSynonymsFromXml($xmlElement);
	}

	/**
	 * @param string $tagName
	 * @param string $mainWord
	 * @throws \Searchperience\Common\Http\Exception\InternalServerErrorException
	 * @throws \Searchperience\Common\Http\Exception\ForbiddenException
	 * @throws \Searchperience\Common\Http\Exception\ClientErrorResponseException
	 * @throws \Searchperience\Common\Http\Exception\UnauthorizedException
	 * @throws \Searchperience\Common\Http\Exception\MethodNotAllowedException
	 * @throws \Searchperience\Common\Http\Exception\RequestEntityTooLargeException
	 * @return \Searchperience\Api\Client\Domain\Synonym\Synonym
	 */
	public function getByMainWord($tagName, $mainWord) {
		try {
			$response   = $this->getGetResponseFromEndpoint('/'.$tagName.'/'.$mainWord);
			$xmlElement = $response->xml();
		} catch (EntityNotFoundException $e) {
			return new SynonymCollection();
		}

		return $this->buildSynonymFromXml($xmlElement);
	}

	/**
	 * @param string $tagName
	 * @param Synonym $synonym
	 * @throws \Searchperience\Common\Http\Exception\InternalServerErrorException
	 * @throws \Searchperience\Common\Http\Exception\ForbiddenException
	 * @throws \Searchperience\Common\Http\Exception\ClientErrorResponseException
	 * @throws \Searchperience\Common\Http\Exception\UnauthorizedException
	 * @throws \Searchperience\Common\Http\Exception\MethodNotAllowedException
	 * @throws \Searchperience\Common\Http\Exception\RequestEntityTooLargeException
	 * @return mixed
	 */
	public function post($tagName, Synonym $synonym) {
		return $this->getPostResponseFromEndpoint($synonym,'/'.$tagName);

	}

	/**
	 * @param $tagName
	 * @param Synonym $synonym
	 * @throws \Searchperience\Common\Http\Exception\InternalServerErrorException
	 * @throws \Searchperience\Common\Http\Exception\ForbiddenException
	 * @throws \Searchperience\Common\Http\Exception\ClientErrorResponseException
	 * @throws \Searchperience\Common\Http\Exception\UnauthorizedException
	 * @throws \Searchperience\Common\Http\Exception\MethodNotAllowedException
	 * @throws \Searchperience\Common\Http\Exception\RequestEntityTooLargeException
	 * @return mixed
	 */
	public function delete($tagName, Synonym $synonym) {
		return $this->deleteByMainWord($tagName, $synonym->getMainWord());
	}

	/**
	 * @param $tagName
	 * @param $mainWord
	 * @throws \Searchperience\Common\Http\Exception\InternalServerErrorException
	 * @throws \Searchperience\Common\Http\Exception\ForbiddenException
	 * @throws \Searchperience\Common\Http\Exception\ClientErrorResponseException
	 * @throws \Searchperience\Common\Http\Exception\UnauthorizedException
	 * @throws \Searchperience\Common\Http\Exception\MethodNotAllowedException
	 * @throws \Searchperience\Common\Http\Exception\RequestEntityTooLargeException
	 * @return mixed
	 */
	public function deleteByMainWord($tagName, $mainWord) {
		$response = $this->getDeleteResponseFromEndpoint('/'.$tagName.'/' . $mainWord);
		return $response->getStatusCode();
	}

	/**
	 * @param \SimpleXMLElement $xml
	 *
	 * @return \Searchperience\Api\Client\Domain\Synonym\Synonym
	 */
	protected function buildSynonymFromXml(\SimpleXMLElement $xml) {
		$synonyms = $this->buildSynonymsFromXml($xml);
		return reset($synonyms);
	}

	/**
	 * @param \SimpleXMLElement $xml
	 *
	 * @return \Searchperience\Api\Client\Domain\Synonym\SynonymCollection
	 */
	protected function buildSynonymsFromXml(\SimpleXMLElement $xml) {
		$synonymCollection = new SynonymCollection();
		if ($xml->totalCount instanceof \SimpleXMLElement) {
			$synonymCollection->setTotalCount((integer)$xml->totalCount->__toString());
		}
		$synonyms = $xml->xpath('synonym');
		foreach ($synonyms as $synonym) {
			$synonymAttributeArray = (array)$synonym->attributes();

			$synonymObject = new Synonym();
			$synonymObject->__setProperty('mainWord',(string) $synonymAttributeArray['@attributes']['mainWord']);
			$synonymObject->__setProperty('tagName',(string) $synonymAttributeArray['@attributes']['tag']);

			$wordsWithSameMeaning = array();
			if(isset( $synonym->wordWithSameMeaning )) {
				foreach($synonym->wordWithSameMeaning as $word) {
					$wordsWithSameMeaning[(string) $word] = (string) $word;
				}
			}

			$synonymObject->__setProperty('wordsWithSameMeaning',$wordsWithSameMeaning);
			$synonymCollection->append($synonymObject);
		}

		return $synonymCollection;
	}

	/**
	 * Creates an data array the is send by postRequest for a synonym
	 *
	 * @param \Searchperience\Api\Client\Domain\Synonym\Synonym $synonym
	 * @return array
	 */
	protected function buildRequestArray(\Searchperience\Api\Client\Domain\AbstractEntity  $synonym) {
		$valueArray = array();

		/** @var \Searchperience\Api\Client\Domain\Synonym\Synonym $synonym */

		if (!is_null($synonym->getMainWord())) {
			$valueArray['mainWord'] = $synonym->getMainWord();
		}

		$wordsWithSameMeaning = $synonym->getWordsWithSameMeaning();
		$valueArray['wordsWithSameMeaning'] = is_array($wordsWithSameMeaning) ? array_values($wordsWithSameMeaning) : array();

		return $valueArray;
	}
}
