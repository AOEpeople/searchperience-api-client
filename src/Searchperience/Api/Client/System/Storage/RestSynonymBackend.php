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
     * @param array $synonyms
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
            $s = is_array($synonyms) ? implode(',', $synonyms) : $synonyms;
            $response   = $this->getGetResponseFromEndpoint('/'.$tagName.'/' . implode(',', $s));
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
        return $this->deleteBySynonyms($tagName, $synonym->getSynonyms());
    }

    /**
     * @param string $tagName
     * @param array $synonyms
     * @throws \Searchperience\Common\Http\Exception\InternalServerErrorException
     * @throws \Searchperience\Common\Http\Exception\ForbiddenException
     * @throws \Searchperience\Common\Http\Exception\ClientErrorResponseException
     * @throws \Searchperience\Common\Http\Exception\UnauthorizedException
     * @throws \Searchperience\Common\Http\Exception\MethodNotAllowedException
     * @throws \Searchperience\Common\Http\Exception\RequestEntityTooLargeException
     * @return mixed
     */
    public function deleteBySynonyms($tagName, $synonyms) {
        $s = is_array($synonyms) ? implode(',', $synonyms) : $synonyms;
        $response = $this->getDeleteResponseFromEndpoint('/'.$tagName.'/' . $s);
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

            $synonymsArray = array();
            if(isset( $synonym->synonyms )) {
                foreach($synonym->synonyms as $synonymEntry) {
                    $synonymsArray[(string) $synonymEntry] = (string) $synonymEntry;
                }
            }

            $synonymObject->__setProperty('synonyms', $synonymsArray);

            $synonymObject->__setProperty('tagName',(string) $synonymAttributeArray['@attributes']['tag']);

            $mappedWords = array();
            if(isset( $synonym->mappedWords )) {
                foreach($synonym->mappedWords as $synonymEntry) {
                    $mappedWords[(string) $synonymEntry] = (string) $synonymEntry;
                }
            }

            $synonymObject->__setProperty('mappedWords',$mappedWords);
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
    protected function buildRequestArray(\Searchperience\Api\Client\Domain\AbstractEntity  $synonym) {
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


        $mappedWords = $synonym->getMappedWords();
        $valueArray['mappedWords'] = is_array($mappedWords) ? array_values($mappedWords) : array();

        return $valueArray;
    }
}
