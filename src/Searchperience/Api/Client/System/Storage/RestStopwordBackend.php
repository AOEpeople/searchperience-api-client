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
     * @param string $id
     *
     * @throws \Searchperience\Common\Http\Exception\InternalServerErrorException
     * @throws \Searchperience\Common\Http\Exception\ForbiddenException
     * @throws \Searchperience\Common\Http\Exception\ClientErrorResponseException
     * @throws \Searchperience\Common\Http\Exception\UnauthorizedException
     * @throws \Searchperience\Common\Http\Exception\MethodNotAllowedException
     * @throws \Searchperience\Common\Http\Exception\RequestEntityTooLargeException
     * @return \Searchperience\Api\Client\Domain\Stopword\Stopword
     */
    public function getById($id)
    {
        try {
            $response = $this->getGetResponseFromEndpoint('/'.$id);
            $xmlElement = $response->xml();
        } catch (EntityNotFoundException $e) {
            return null;
        }

        return $this->buildStopwordFromXml($xmlElement);
    }

	/**
	 * @param Stopword $stopword
	 * @throws \Searchperience\Common\Http\Exception\InternalServerErrorException
	 * @throws \Searchperience\Common\Http\Exception\ForbiddenException
	 * @throws \Searchperience\Common\Http\Exception\ClientErrorResponseException
	 * @throws \Searchperience\Common\Http\Exception\UnauthorizedException
	 * @throws \Searchperience\Common\Http\Exception\MethodNotAllowedException
	 * @throws \Searchperience\Common\Http\Exception\RequestEntityTooLargeException
	 * @return mixed
	 */
	public function post(Stopword $stopword) {
		return $this->getPostResponseFromEndpoint($stopword,'');

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
	 * @param Stopword $stopword
	 * @throws \Searchperience\Common\Http\Exception\InternalServerErrorException
	 * @throws \Searchperience\Common\Http\Exception\ForbiddenException
	 * @throws \Searchperience\Common\Http\Exception\ClientErrorResponseException
	 * @throws \Searchperience\Common\Http\Exception\UnauthorizedException
	 * @throws \Searchperience\Common\Http\Exception\MethodNotAllowedException
	 * @throws \Searchperience\Common\Http\Exception\RequestEntityTooLargeException
	 * @return mixed
	 */
	public function delete(Stopword $stopword) {
		return $this->deleteById($stopword->getId());
	}

	/**
	 * @param int $id
	 * @throws \Searchperience\Common\Http\Exception\InternalServerErrorException
	 * @throws \Searchperience\Common\Http\Exception\ForbiddenException
	 * @throws \Searchperience\Common\Http\Exception\ClientErrorResponseException
	 * @throws \Searchperience\Common\Http\Exception\UnauthorizedException
	 * @throws \Searchperience\Common\Http\Exception\MethodNotAllowedException
	 * @throws \Searchperience\Common\Http\Exception\RequestEntityTooLargeException
	 * @return mixed
	 */
	public function deleteById($id) {
		$response = $this->getDeleteResponseFromEndpoint('/'.$id);
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
			$stopwordObject->__setProperty('id',(string) $stopwordAttributeArray['@attributes']['id']);
			$stopwordObject->__setProperty('isActive',(bool) (int) $stopwordAttributeArray['@attributes']['isactive']);
			$stopwordObject->__setProperty('word',(string) $stopwordAttributeArray['@attributes']['word']);
			$stopwordObject->__setProperty('language',(string) $stopwordAttributeArray['@attributes']['language']);

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
	protected function buildRequestArray(\Searchperience\Api\Client\Domain\AbstractEntity $stopword) {
		$valueArray = array();

		/** @var \Searchperience\Api\Client\Domain\Stopword\Stopword $stopword */

        if (!is_null($stopword->getId())) {
            $valueArray['id'] = $stopword->getId();
        }

        if (!is_null($stopword->isActive())) {
            $valueArray['isActive'] = $stopword->isActive() ? 1 : 0;
        }

		if (!is_null($stopword->getWord())) {
			$valueArray['word'] = $stopword->getWord();
		}

		if (!is_null($stopword->getLanguage())) {
			$valueArray['language'] = $stopword->getLanguage();
		}
		return $valueArray;
	}

    public function getAllByFilterCollection($start, $limit, \Searchperience\Api\Client\Domain\Filters\FilterCollection $filtersCollection = null, $sortingField = '', $sortingType = self::SORTING_DESC)
    {
        try {
            $response = $this->getListResponseFromEndpoint($start, $limit, $filtersCollection, $sortingField, $sortingType);
            $xmlElement = $response->xml();
        } catch (EntityNotFoundException $e) {
            return new StopwordCollection();
        }

        return $this->buildStopwordsFromXml($xmlElement);
    }

    /**
     * @throws \Searchperience\Common\Http\Exception\InternalServerErrorException
     * @throws \Searchperience\Common\Http\Exception\ForbiddenException
     * @throws \Searchperience\Common\Http\Exception\ClientErrorResponseException
     * @throws \Searchperience\Common\Http\Exception\UnauthorizedException
     * @throws \Searchperience\Common\Http\Exception\MethodNotAllowedException
     * @throws \Searchperience\Common\Http\Exception\RequestEntityTooLargeException
     */
    public function pushAll()
    {
        return $this->getPostResponseFromEndpointWithoutBody('/pushAll');
    }
}
