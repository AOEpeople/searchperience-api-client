<?php

namespace Searchperience\Api\Client\System\Storage;

use Guzzle\Http\Client;

/**
 * @author Michael Klapper <michael.klapper@aoemedia.de>
 * @date 14.11.12
 * @time 15:17
 */
class RestDocumentBackend extends \Searchperience\Api\Client\System\Storage\AbstractRestBackend implements \Searchperience\Api\Client\System\Storage\DocumentBackendInterface {

	/**
	 * @var \Guzzle\Http\Client
	 */
	protected $restClient;

	/**
	 * @param \Guzzle\Http\Client $restClient
	 * @return void
	 */
	public function injectRestClient(\Guzzle\Http\Client $restClient) {
		$this->restClient = $restClient->setDefaultHeaders(array(
			'User-Agent' => 'Searchperience-API-Client',
		));
	}

	/**
	 * {@inheritdoc}
	 */
	public function post(\Searchperience\Api\Client\Domain\Document $document) {
		$url = '/documents?';
		$url .= '&foreignId='.$document->getForeignId();
		$url .= '&url='.$document->getUrl();
		$url .= '&source='.$document->getUrl();

		$this->restClient->post(
			$url,
			NULL
			, 'body of the request')
		->send();
	}

	/**
	 * {@inheritdoc}
	 */
	public function getByForeignId($foreignId) {
		/** @var $response \Guzzle\http\Message\Response */
		$response = $this->restClient->setBaseUrl($this->baseUrl)
			->get('/documents?foreignId=' . $foreignId)
			->setAuth($this->username, $this->password)
			->send();
		$this->transformStatusCodeToException($response->getStatusCode());

		return $this->buildDocumentFromXml($response->xml());
	}

	/**
	 * {@inheritdoc}
	 */
	public function deleteByForeignId($foreignId) {
		$response = $this->restClient->setBaseUrl($this->baseUrl)
			->delete($this->baseUrl . '/documents?foreignId=' . $foreignId)
			->setAuth($this->username, $this->password)
			->send();
		$this->transformStatusCodeToException($response->getStatusCode());
		return $response->getStatusCode();
	}

	/**
	 * @param \SimpleXMLElement $xml
	 *
	 * @return \Searchperience\Api\Client\Domain\Document
	 */
	protected function buildDocumentFromXml(\SimpleXMLElement $xml) {
		$documentAttributeArray = (array)$xml->document->attributes();

		$document = new \Searchperience\Api\Client\Domain\Document();
		$document->setId((integer)$documentAttributeArray['@attributes']['id']);
		$document->setUrl((string)$xml->document->url);
		$document->setForeignId((integer)$xml->document->foreignId);
		$document->setBoostFactor((integer)$xml->document->boostFactor);
		$document->setContent((string)$xml->document->content->children()->asXML());
		$document->setGeneralPriority((integer)$xml->document->generalPriority);
		$document->setTemporaryPriority((integer)$xml->document->temporaryPriority);
		$document->setMimeType((string)$xml->document->mimeType);
		$document->setIsMarkedForProcessing((integer)$xml->document->isMarkedForProcessing);
		$document->setLastProcessing((string)$xml->document->lastProcessingTime);
		$document->setNoIndex((integer)$xml->document->noIndex);

		return $document;
	}
}
