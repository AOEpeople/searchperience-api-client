<?php

namespace Searchperience\Api\Client\System\Storage;

use Searchperience\Api\Client\Domain\Synonym\Synonym;
use Searchperience\Api\Client\Domain\Synonym\SynonymCollection;
use Searchperience\Common\Http\Exception\EntityNotFoundException;

/**
 * Class RestSynonymBackend
 * @package Searchperience\Api\Client\System\Storage
 */
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
     * @param string $id
     *
     * @throws \Searchperience\Common\Http\Exception\InternalServerErrorException
     * @throws \Searchperience\Common\Http\Exception\ForbiddenException
     * @throws \Searchperience\Common\Http\Exception\ClientErrorResponseException
     * @throws \Searchperience\Common\Http\Exception\UnauthorizedException
     * @throws \Searchperience\Common\Http\Exception\MethodNotAllowedException
     * @throws \Searchperience\Common\Http\Exception\RequestEntityTooLargeException
     * @return \Searchperience\Api\Client\Domain\Synonym\Synonym
     */
    public function getById($id)
    {
        try {
            $response = $this->getGetResponseFromEndpoint('/'.$id);
            $xmlElement = $response->xml();
        } catch (EntityNotFoundException $e) {
            return null;
        }

        return $this->buildSynonymFromXml($xmlElement);
    }

    /**
     * @param Synonym $synonym
     * @throws \Searchperience\Common\Http\Exception\InternalServerErrorException
     * @throws \Searchperience\Common\Http\Exception\ForbiddenException
     * @throws \Searchperience\Common\Http\Exception\ClientErrorResponseException
     * @throws \Searchperience\Common\Http\Exception\UnauthorizedException
     * @throws \Searchperience\Common\Http\Exception\MethodNotAllowedException
     * @throws \Searchperience\Common\Http\Exception\RequestEntityTooLargeException
     * @return mixed
     */
    public function post(Synonym $synonym) {
        return $this->getPostResponseFromEndpoint($synonym,'');
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
     * @param Synonym $synonym
     * @throws \Searchperience\Common\Http\Exception\InternalServerErrorException
     * @throws \Searchperience\Common\Http\Exception\ForbiddenException
     * @throws \Searchperience\Common\Http\Exception\ClientErrorResponseException
     * @throws \Searchperience\Common\Http\Exception\UnauthorizedException
     * @throws \Searchperience\Common\Http\Exception\MethodNotAllowedException
     * @throws \Searchperience\Common\Http\Exception\RequestEntityTooLargeException
     * @return mixed
     */
    public function delete(Synonym $synonym) {
        return $this->deleteById($synonym->getId());
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

    /**
     * @param \SimpleXMLElement $xml
     * @return \Searchperience\Api\Client\Domain\Synonym\Synonym
     */
    protected function buildSynonymFromXml(\SimpleXMLElement $xml) {
        $synonyms = $this->buildSynonymsFromXml($xml);
        return reset($synonyms);
    }

    /**
     * @param \SimpleXMLElement $xml
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
            $synonymObject->__setProperty('id',(string) $synonymAttributeArray['@attributes']['id']);
            $synonymObject->__setProperty('synonyms',(string) $synonymAttributeArray['@attributes']['synonyms']);
            $synonymObject->__setProperty('matchingType',(string) $synonymAttributeArray['@attributes']['matchingtype']);
            $synonymObject->__setProperty('language',(string) $synonymAttributeArray['@attributes']['language']);
            $synonymObject->__setProperty('isActive',(bool) (int) $synonymAttributeArray['@attributes']['isactive']);
            $synonymObject->__setProperty('mappedWords',(string) $synonymAttributeArray['@attributes']['mappedwords']);
            $synonymCollection->append($synonymObject);
        }
        return $synonymCollection;
    }

    /**
     * Creates an data array the is send by postRequest for a synonym
     *
     * @param \Searchperience\Api\Client\Domain\AbstractEntity $synonym
     * @return array
     */
    protected function buildRequestArray(\Searchperience\Api\Client\Domain\AbstractEntity $synonym) {
        $valueArray = array();

        /** @var \Searchperience\Api\Client\Domain\Synonym\Synonym $synonym */

        if (!is_null($synonym->getId())) {
            $valueArray['id'] = $synonym->getId();
        }

        if (!is_null($synonym->getSynonyms())) {
            $valueArray['synonyms'] = $synonym->getSynonyms();
        }

        if (!is_null($synonym->getMatchingType())) {
            $valueArray['matchingType'] = $synonym->getMatchingType();
        }

        if (!is_null($synonym->getLanguage())) {
            $valueArray['language'] = $synonym->getLanguage();
        }

        if (!is_null($synonym->isActive())) {
            $valueArray['isActive'] = $synonym->isActive() ? 1 : 0;
        }

        if (!is_null($synonym->getMappedWords())) {
            $valueArray['mappedWords'] = $synonym->getMappedWords();
        }

        return $valueArray;
    }

    /**
     * @param int $start
     * @param int $limit
     * @param \Searchperience\Api\Client\Domain\Filters\FilterCollection|null $filtersCollection
     * @param string $sortingField
     * @param string $sortingType
     * @return mixed
     */
    public function getAllByFilterCollection($start, $limit, \Searchperience\Api\Client\Domain\Filters\FilterCollection $filtersCollection = null, $sortingField = '', $sortingType = self::SORTING_DESC)
    {
        try {
            $response = $this->getListResponseFromEndpoint($start, $limit, $filtersCollection, $sortingField, $sortingType);
            $xmlElement = $response->xml();
        } catch (EntityNotFoundException $e) {
            return new SynonymCollection();
        }

        return $this->buildSynonymsFromXml($xmlElement);
    }
}
