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
			'User-Agent' => 'Searchperience-API-Client version: ' . \Searchperience\Common\Version::Version,
			'Accepts' => 'application/searchperienceproduct+xml,application/xml,text/xml',
		));
	}

	/**
	 * {@inheritdoc}
	 */
	public function post(\Searchperience\Api\Client\Domain\Document $document) {
		try {
			/** @var $response \Guzzle\http\Message\Response */
			$response = $this->restClient->setBaseUrl($this->baseUrl)
				->post('/{customerKey}/documents', NULL, $this->buildRequestArrayFromDocument($document))
				->setAuth($this->username, $this->password)
				->send();
		} catch (\Guzzle\Http\Exception\ClientErrorResponseException $exception) {
			$this->transformStatusCodeToClientErrorResponseException($exception);
		} catch (\Guzzle\Http\Exception\ServerErrorResponseException $exception) {
			$this->transformStatusCodeToServerErrorResponseException($exception);
		} catch (\Exception $exception) {
			throw new \Searchperience\Common\Exception\RuntimeException('Unknown error occurred; Please check parent exception for more details.', 1353579269, $exception);
		}

		return $response->getStatusCode();
	}

	/**
	 * {@inheritdoc}
	 */
	public function getByForeignId($foreignId) {
		try {
			/** @var $response \Guzzle\http\Message\Response */
			$response = $this->restClient->setBaseUrl($this->baseUrl)
				->get('/{customerKey}/documents?foreignId=' . $foreignId)
				->setAuth($this->username, $this->password)
				->send();
		} catch (\Guzzle\Http\Exception\ClientErrorResponseException $exception) {
			$this->transformStatusCodeToClientErrorResponseException($exception);
		} catch (\Guzzle\Http\Exception\ServerErrorResponseException $exception) {
			$this->transformStatusCodeToServerErrorResponseException($exception);
		} catch (\Exception $exception) {
			throw new \Searchperience\Common\Exception\RuntimeException('Unknown error occurred; Please check parent exception for more details.', 1353579279, $exception);
		}

		return $this->buildDocumentFromXml($response->xml());
	}

	/**
	 * {@inheritdoc}
	 */
	public function getByUrl($url) {
		try {
			$url = urlencode($url);
			/** @var $response \Guzzle\http\Message\Response */
			$response = $this->restClient->setBaseUrl($this->baseUrl)
					->get('/{customerKey}/documents?url=' . $url)
					->setAuth($this->username, $this->password)
					->send();
		} catch (\Guzzle\Http\Exception\ClientErrorResponseException $exception) {
			$this->transformStatusCodeToClientErrorResponseException($exception);
		} catch (\Guzzle\Http\Exception\ServerErrorResponseException $exception) {
			$this->transformStatusCodeToServerErrorResponseException($exception);
		} catch (\Exception $exception) {
			throw new \Searchperience\Common\Exception\RuntimeException('Unknown error occurred; Please check parent exception for more details.', 1353579279, $exception);
		}

		return $this->buildDocumentFromXml($response->xml());
	}

	/**
	 * {@inheritdoc}
	 */
	public function getAll($start = 0, $limit = 10, $source = '') {
		try {
			/** @var $response \Guzzle\http\Message\Response */
			$response = $this->restClient->setBaseUrl($this->baseUrl)
					->get('/{customerKey}/documents?source=' . $source.'&start='.$start . '&limit=' . $limit)
					->setAuth($this->username, $this->password)
					->send();
		} catch (\Guzzle\Http\Exception\ClientErrorResponseException $exception) {
			$this->transformStatusCodeToClientErrorResponseException($exception);
		} catch (\Guzzle\Http\Exception\ServerErrorResponseException $exception) {
			$this->transformStatusCodeToServerErrorResponseException($exception);
		} catch (\Exception $exception) {
			throw new \Searchperience\Common\Exception\RuntimeException('Unknown error occurred; Please check parent exception for more details.', 1353579279, $exception);
		}

		return $this->buildDocumentListFromXml($response->xml());
	}

	/**
	 * {@inheritdoc}
	 */
	public function deleteByForeignId($foreignId) {
		try {
			/** @var $response \Guzzle\http\Message\Response */
			$response = $this->restClient->setBaseUrl($this->baseUrl)
				->delete('/{customerKey}/documents?foreignId=' . $foreignId)
				->setAuth($this->username, $this->password)
				->send();
		} catch (\Guzzle\Http\Exception\ClientErrorResponseException $exception) {
			$this->transformStatusCodeToClientErrorResponseException($exception);
		} catch (\Guzzle\Http\Exception\ServerErrorResponseException $exception) {
			$this->transformStatusCodeToServerErrorResponseException($exception);
		} catch (\Exception $exception) {
			throw new \Searchperience\Common\Exception\RuntimeException('Unknown error occurred; Please check parent exception for more details.', 1353579284, $exception);
		}

		return $response->getStatusCode();
	}

	/**
	 * {@inheritdoc}
	 */
	public function deleteBySource($source) {
		try {
			/** @var $response \Guzzle\http\Message\Response */
			$response = $this->restClient->setBaseUrl($this->baseUrl)
					->delete('/{customerKey}/documents?source=' . $source)
					->setAuth($this->username, $this->password)
					->send();
		} catch (\Guzzle\Http\Exception\ClientErrorResponseException $exception) {
			$this->transformStatusCodeToClientErrorResponseException($exception);
		} catch (\Guzzle\Http\Exception\ServerErrorResponseException $exception) {
			$this->transformStatusCodeToServerErrorResponseException($exception);
		} catch (\Exception $exception) {
			throw new \Searchperience\Common\Exception\RuntimeException('Unknown error occurred; Please check parent exception for more details.', 1386845400, $exception);
		}

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
		$document->setForeignId((string)$xml->document->foreignId);
		$document->setSource((string)$xml->document->source);
		$document->setBoostFactor((integer)$xml->document->boostFactor);
		$document->setContent((string)$xml->document->content);
		$document->setGeneralPriority((integer)$xml->document->generalPriority);
		$document->setTemporaryPriority((integer)$xml->document->temporaryPriority);
		$document->setMimeType((string)$xml->document->mimeType);
		$document->setIsMarkedForProcessing((integer)$xml->document->isMarkedForProcessing);
		$document->setIsMarkedForDeletion((integer)$xml->document->isMarkedForDeletion);
		$document->setIsProminent((integer)$xml->document->isProminent);
		$document->setLastProcessing((string)$xml->document->lastProcessingTime);
		$document->setNoIndex((integer)$xml->document->noIndex);

		return $document;
	}

	/**
	 * @param \SimpleXMLElement $xml
	 *
	 * @return \Searchperience\Api\Client\Domain\Document
	 */
	protected function buildDocumentListFromXml(\SimpleXMLElement $xml) {

		$documents=$xml->xpath('document');
		$documentArray = array();

		foreach($documents as $document) {
			$documentAttributeArray = (array)$document->attributes();
			$documentObject = new \Searchperience\Api\Client\Domain\Document();
			$documentObject ->setId((integer)$documentAttributeArray['@attributes']['id']);
			$documentObject ->setUrl((string)$document->url);
			$documentObject ->setForeignId((string)$document->foreignId);
			$documentObject ->setSource((string)$document->source);
			$documentObject ->setBoostFactor((integer)$document->boostFactor);
			$documentObject ->setContent((string)$document->content);
			$documentObject ->setGeneralPriority((integer)$document->generalPriority);
			$documentObject ->setTemporaryPriority((integer)$document->temporaryPriority);
			$documentObject ->setMimeType((string)$document->mimeType);
			$documentObject ->setIsMarkedForProcessing((integer)$document->isMarkedForProcessing);
			$documentObject ->setIsMarkedForDeletion((integer)$document->isMarkedForDeletion);
			$documentObject ->setIsProminent((integer)$document->isProminent);
			$documentObject ->setLastProcessing((string)$document->lastProcessingTime);
			$documentObject ->setNoIndex((integer)$document->noIndex);
			$documentArray[]=$documentObject;
		}

		return $documentArray ;
	}

	/**
	 * Create an array containing only the available document property values.
	 *
	 * @param \Searchperience\Api\Client\Domain\Document $document
	 * @return array
	 */
	protected function buildRequestArrayFromDocument(\Searchperience\Api\Client\Domain\Document $document) {
		$valueArray = array();

		if (!is_null($document->getLastProcessing())) {
			$valueArray['lastProcessing'] = $document->getLastProcessing();
		}
		if (!is_null($document->getBoostFactor())) {
			$valueArray['boostFactor'] = $document->getBoostFactor();
		}
		if (!is_null($document->getIsProminent())) {
			$valueArray['isProminent'] = $document->getIsProminent();
		}
		if (!is_null($document->getIsMarkedForProcessing())) {
			$valueArray['isMarkedForProcessing'] = $document->getIsMarkedForProcessing();
		}
		if (!is_null($document->getIsMarkedForDeletion())) {
			$valueArray['isMarkedForDeletion'] = $document->getIsMarkedForDeletion();
		}
		if (!is_null($document->getNoIndex())) {
			$valueArray['noIndex'] = $document->getNoIndex();
		}
		if (!is_null($document->getForeignId())) {
			$valueArray['foreignId'] = $document->getForeignId();
		}
		if (!is_null($document->getUrl())) {
			$valueArray['url'] = $document->getUrl();
		}
		if (!is_null($document->getSource())) {
			$valueArray['source'] = $document->getSource();
		}
		if (!is_null($document->getMimeType())) {
			$valueArray['mimeType'] = $document->getMimeType();
		}
		if (!is_null($document->getContent())) {
			$valueArray['content'] = $document->getContent();
		}
		if (!is_null($document->getGeneralPriority())) {
			$valueArray['generalPriority'] = $document->getGeneralPriority();
		}
		if (!is_null($document->getTemporaryPriority())) {
			$valueArray['temporaryPriority'] = $document->getTemporaryPriority();
		}

		return $valueArray;
	}
}
