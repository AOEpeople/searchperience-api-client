<?php

namespace Searchperience\Api\Client\System\Storage;

use Searchperience\Api\Client\Domain\Stopword\Stopword;
use Searchperience\Api\Client\Domain\Stopword\StopwordCollection;
use Searchperience\Common\Http\Exception\EntityNotFoundException;

/**
 * Class RestStopwordBackend
 * @package Searchperience\Api\Client\System\Storage
 */
class RestStopwordBackend extends AbstractRestBackend implements StopwordBackendInterface {

	/**
	 * @var string
	 */
	protected $endpoint = 'stopwords';

	/**
	 * @throws \Searchperience\Common\Http\Exception\InternalServerErrorException
	 * @throws \Searchperience\Common\Http\Exception\ForbiddenException
	 * @throws \Searchperience\Common\Http\Exception\ClientErrorResponseException
	 * @throws \Searchperience\Common\Http\Exception\UnauthorizedException
	 * @throws \Searchperience\Common\Http\Exception\MethodNotAllowedException
	 * @throws \Searchperience\Common\Http\Exception\RequestEntityTooLargeException
	 * @return \Searchperience\Api\Client\Domain\Stopword\StopwordCollection
	 */
	public function getAll() {
		try {
			$response   = $this->getGetResponseFromEndpoint();
			$xmlElement = $response->xml();
		} catch (EntityNotFoundException $e) {
			return new StopwordCollection();
		}

		return $this->buildStopwordsFromXml($xmlElement);
	}

	/**
	 * @param string $tagName
	 * @throws \Searchperience\Common\Http\Exception\InternalServerErrorException
	 * @throws \Searchperience\Common\Http\Exception\ForbiddenException
	 * @throws \Searchperience\Common\Http\Exception\ClientErrorResponseException
	 * @throws \Searchperience\Common\Http\Exception\UnauthorizedException
	 * @throws \Searchperience\Common\Http\Exception\MethodNotAllowedException
	 * @throws \Searchperience\Common\Http\Exception\RequestEntityTooLargeException
	 * @return \Searchperience\Api\Client\Domain\Stopword\StopwordCollection
	 */
	public function getAllByTag($tagName) {
		try {
			$response   = $this->getGetResponseFromEndpoint('/'.$tagName);
			$xmlElement = $response->xml();
		} catch (EntityNotFoundException $e) {
			return new StopwordCollection();
		}

		return $this->buildStopwordsFromXml($xmlElement);
	}

	/**
	 * @param string $tagName
	 * @param string $word
	 * @throws \Searchperience\Common\Http\Exception\InternalServerErrorException
	 * @throws \Searchperience\Common\Http\Exception\ForbiddenException
	 * @throws \Searchperience\Common\Http\Exception\ClientErrorResponseException
	 * @throws \Searchperience\Common\Http\Exception\UnauthorizedException
	 * @throws \Searchperience\Common\Http\Exception\MethodNotAllowedException
	 * @throws \Searchperience\Common\Http\Exception\RequestEntityTooLargeException
	 * @return \Searchperience\Api\Client\Domain\Stopword\Stopword|null
	 */
	public function getByWord($tagName, $word) {
		try {
			$response   = $this->getGetResponseFromEndpoint('/'.$tagName.'/'. $word);
			$xmlElement = $response->xml();
		} catch (EntityNotFoundException $e) {
			return null;
		}

		return $this->buildStopwordFromXml($xmlElement);
	}

	/**
	 * @param string $tagName
	 * @param Stopword $stopword
	 * @throws \Searchperience\Common\Http\Exception\InternalServerErrorException
	 * @throws \Searchperience\Common\Http\Exception\ForbiddenException
	 * @throws \Searchperience\Common\Http\Exception\ClientErrorResponseException
	 * @throws \Searchperience\Common\Http\Exception\UnauthorizedException
	 * @throws \Searchperience\Common\Http\Exception\MethodNotAllowedException
	 * @throws \Searchperience\Common\Http\Exception\RequestEntityTooLargeException
	 * @return mixed
	 */
	public function post($tagName, Stopword $stopword) {
		return $this->getPostResponseFromEndpoint($stopword,'/'.$tagName);

	}

	/**
	 * @throws \Searchperience\Common\Http\Exception\InternalServerErrorException
	 * @throws \Searchperience\Common\Http\Exception\ForbiddenException
	 * @throws \Searchperience\Common\Http\Exception\ClientErrorResponseException
	 * @throws \Searchperience\Common\Http\Exception\UnauthorizedException
	 * @throws \Searchperience\Common\Http\Exception\MethodNotAllowedException
	 * @throws \Searchperience\Common\Http\Exception\RequestEntityTooLargeException
	 * @return mixed
	 */
	public function deleteAll() {
		$response = $this->getDeleteResponseFromEndpoint();
		return $response->getStatusCode();
	}

	/**
	 * @param string $tagName
	 * @param Stopword $stopword
	 * @throws \Searchperience\Common\Http\Exception\InternalServerErrorException
	 * @throws \Searchperience\Common\Http\Exception\ForbiddenException
	 * @throws \Searchperience\Common\Http\Exception\ClientErrorResponseException
	 * @throws \Searchperience\Common\Http\Exception\UnauthorizedException
	 * @throws \Searchperience\Common\Http\Exception\MethodNotAllowedException
	 * @throws \Searchperience\Common\Http\Exception\RequestEntityTooLargeException
	 * @return mixed
	 */
	public function delete($tagName, Stopword $stopword) {
		return $this->deleteByWord($tagName, $stopword->getWord());
	}

	/**
	 * @param string $tagName
	 * @param string $word
	 * @throws \Searchperience\Common\Http\Exception\InternalServerErrorException
	 * @throws \Searchperience\Common\Http\Exception\ForbiddenException
	 * @throws \Searchperience\Common\Http\Exception\ClientErrorResponseException
	 * @throws \Searchperience\Common\Http\Exception\UnauthorizedException
	 * @throws \Searchperience\Common\Http\Exception\MethodNotAllowedException
	 * @throws \Searchperience\Common\Http\Exception\RequestEntityTooLargeException
	 * @return mixed
	 */
	public function deleteByWord($tagName, $word) {
		$response = $this->getDeleteResponseFromEndpoint('/'.$tagName.'/' . $word);
		return $response->getStatusCode();
	}

	/**
	 * @param \SimpleXMLElement $xml
	 * @return \Searchperience\Api\Client\Domain\Stopword\Stopword
	 */
	protected function buildStopwordFromXml(\SimpleXMLElement $xml) {
		$stopwords = $this->buildStopwordsFromXml($xml);
		return reset($stopwords);
	}

	/**
	 * @param \SimpleXMLElement $xml
	 * @return \Searchperience\Api\Client\Domain\Stopword\StopwordCollection
	 */
	protected function buildStopwordsFromXml(\SimpleXMLElement $xml) {
		$stopwordCollection = new StopwordCollection();
		if ($xml->totalCount instanceof \SimpleXMLElement) {
			$stopwordCollection->setTotalCount((integer)$xml->totalCount->__toString());
		}
		$stopwords = $xml->xpath('stopword');
		foreach ($stopwords as $stopword) {
			$stopwordAttributeArray = (array)$stopword->attributes();

			$stopwordObject = new Stopword();
			$stopwordObject->__setProperty('word',(string) $stopwordAttributeArray['@attributes']['word']);
			$stopwordObject->__setProperty('tagName',(string) $stopwordAttributeArray['@attributes']['tag']);

			$stopwordCollection->append($stopwordObject);
		}
		return $stopwordCollection;
	}

	/**
	 * Creates an data array the is send by postRequest for a stopword
	 *
	 * @param \Searchperience\Api\Client\Domain\AbstractEntity $stopword
	 * @return array
	 */
	protected function buildRequestArray(\Searchperience\Api\Client\Domain\AbstractEntity  $stopword) {
		$valueArray = array();

		/** @var \Searchperience\Api\Client\Domain\Stopword\Stopword $stopword */

		if (!is_null($stopword->getWord())) {
			$valueArray['word'] = $stopword->getWord();
		}

		if (!is_null($stopword->getTagName())) {
			$valueArray['tagName'] = $stopword->getTagName();
		}
		return $valueArray;
	}
}
