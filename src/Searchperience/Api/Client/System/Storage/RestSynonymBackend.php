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
     * @param string $synonyms
     * @throws \Searchperience\Common\Http\Exception\InternalServerErrorException
     * @throws \Searchperience\Common\Http\Exception\ForbiddenException
     * @throws \Searchperience\Common\Http\Exception\ClientErrorResponseException
     * @throws \Searchperience\Common\Http\Exception\UnauthorizedException
     * @throws \Searchperience\Common\Http\Exception\MethodNotAllowedException
     * @throws \Searchperience\Common\Http\Exception\RequestEntityTooLargeException
     * @return \Searchperience\Api\Client\Domain\Synonym\Synonym|null
     */
    public function getBySynonyms($tagName, $synonyms) {
        try {
            $response   = $this->getGetResponseFromEndpoint('/'.$tagName.'/' . $synonyms);
            $xmlElement = $response->xml();
        } catch (EntityNotFoundException $e) {
            return null;
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
        $response = $this->getDeleteResponseFromEndpoint('/'.$tagName, $synonym);
        return $response->getStatusCode();
    }

    /**
     * @param string $tagName
     * @param string $synonyms
     * @throws \Searchperience\Common\Http\Exception\InternalServerErrorException
     * @throws \Searchperience\Common\Http\Exception\ForbiddenException
     * @throws \Searchperience\Common\Http\Exception\ClientErrorResponseException
     * @throws \Searchperience\Common\Http\Exception\UnauthorizedException
     * @throws \Searchperience\Common\Http\Exception\MethodNotAllowedException
     * @throws \Searchperience\Common\Http\Exception\RequestEntityTooLargeException
     * @return mixed
     */
    public function deleteBySynonyms($tagName, $synonyms) {
        $response = $this->getDeleteResponseFromEndpoint('/'.$tagName.'/' . $synonyms);
        return $response->getStatusCode();
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

            $synonymObject->__setProperty('synonyms',(string) $synonym->synonyms);
            $synonymObject->__setProperty('tagName',(string) $synonymAttributeArray['@attributes']['tag']);
            $synonymObject->__setProperty('mappedWords',(string) $synonym->mappedWords);

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

        if (!is_null($synonym->getSynonyms())) {
            $valueArray['synonyms'] = $synonym->getSynonyms();
        }

        if (!is_null($synonym->getTagName())) {
            $valueArray['tagName'] = $synonym->getTagName();
        }

        if (!is_null($synonym->getType())) {
            $valueArray['type'] = $synonym->getType();
        }

        if (!is_null($synonym->getMappedWords())) {
            $valueArray['mappedWords'] = $synonym->getMappedWords();
        }

        return $valueArray;
    }
}
