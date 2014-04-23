<?php

namespace Searchperience\Api\Client\System\Storage;

use Guzzle\Http\Client;
use Searchperience\Api\Client\Domain\UrlQueueItem\UrlQueueItemCollection;

/**
 * Class RestUrlqueueBackend
 * @package Searchperience\Api\Client\System\Storage
 */
class RestUrlQueueItemBackend extends \Searchperience\Api\Client\System\Storage\AbstractRestBackend implements \Searchperience\Api\Client\System\Storage\UrlQueueItemBackendInterface {

	/**
	 * {@inheritdoc}
	 */
	public function post(\Searchperience\Api\Client\Domain\UrlQueueItem\UrlQueueItem $urlQueue) {
		try {
			/** @var $response \Guzzle\http\Message\Response */
			$arguments 	= $this->buildRequestArrayFromUrlQueue($urlQueue);
			$response 	= $this->executePostRequest($arguments);
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
	 * @param int $documentId
	 * @return \Searchperience\Api\Client\Domain\Document\Document
	 * @throws \Searchperience\Common\Exception\RuntimeException
	 */
	public function getByDocumentId($documentId) {
		try {
			/** @var $response \Guzzle\http\Message\Response */
			$response = $this->restClient->setBaseUrl($this->baseUrl)
					->get('/{customerKey}/urlqueueitems/' . $documentId)
					->setAuth($this->username, $this->password)
					->send();
		} catch (\Guzzle\Http\Exception\ClientErrorResponseException $exception) {
			$this->transformStatusCodeToClientErrorResponseException($exception);
		} catch (\Guzzle\Http\Exception\ServerErrorResponseException $exception) {
			$this->transformStatusCodeToServerErrorResponseException($exception);
		} catch (\Exception $exception) {
			throw new \Searchperience\Common\Exception\RuntimeException('Unknown error occurred; Please check parent exception for more details.', 1353579279, $exception);
		}

		return $this->buildUrlQueueItemFromXml($response->xml());
	}


	/**
	 * {@inheritdoc}
	 * @param string $url
	 * @return \Searchperience\Api\Client\Domain\Document\Document
	 * @throws \Searchperience\Common\Exception\RuntimeException
	 */
	public function getByUrl($url) {
		try {
			$url = urlencode($url);
			/** @var $response \Guzzle\http\Message\Response */
			$response = $this->restClient->setBaseUrl($this->baseUrl)
					->get('/{customerKey}/urlqueueitems?url=' . $url)
					->setAuth($this->username, $this->password)
					->send();
		} catch (\Guzzle\Http\Exception\ClientErrorResponseException $exception) {
			$this->transformStatusCodeToClientErrorResponseException($exception);
		} catch (\Guzzle\Http\Exception\ServerErrorResponseException $exception) {
			$this->transformStatusCodeToServerErrorResponseException($exception);
		} catch (\Exception $exception) {
			throw new \Searchperience\Common\Exception\RuntimeException('Unknown error occurred; Please check parent exception for more details.', 1353579279, $exception);
		}

		return $this->buildUrlQueueItemFromXml($response->xml());
	}

	/**
	 * {@inheritdoc}
	 * @param int $start
	 * @param int $limit
	 * @param \Searchperience\Api\Client\Domain\Document\FilterCollection $filtersCollection
	 * @param string $sortingField = ''
	 * @param string $sortingType = desc
	 * @return \Searchperience\Api\Client\Domain\Document\UrlQueueItemCollection
	 * @throws \Searchperience\Common\Exception\RuntimeException
	 */
	public function getAllByFilterCollection($start, $limit, \Searchperience\Api\Client\Domain\Filters\FilterCollection $filtersCollection = null, $sortingField = '', $sortingType = self::SORTING_DESC ) {
		$filterUrlString 	= $this->getFilterQueryString($filtersCollection);
		$sortingUrlString 	= $this->getSortingQueryString($sortingField, $sortingType);

		try {
			/** @var $response \Guzzle\http\Message\Response */
			$response = $this->restClient->setBaseUrl($this->baseUrl)
					->get('/{customerKey}/urlqueueitems?start=' . $start . '&limit=' . $limit . $filterUrlString.$sortingUrlString)
					->setAuth($this->username, $this->password)
					->send();
		} catch (\Guzzle\Http\Exception\ClientErrorResponseException $exception) {
			$this->transformStatusCodeToClientErrorResponseException($exception);
		} catch (\Guzzle\Http\Exception\ServerErrorResponseException $exception) {
			$this->transformStatusCodeToServerErrorResponseException($exception);
		} catch (\Exception $exception) {
			throw new \Searchperience\Common\Exception\RuntimeException('Unknown error occurred; Please check parent exception for more details.', 1353579279, $exception);
		}

		$xmlElement = $response->xml();

		return $this->buildUrlQueueItemsFromXml($xmlElement);
	}

	/**
	 * {@inheritdoc}
	 */
	public function deleteByDocumentId($documentId) {
		try {
			/** @var $response \Guzzle\http\Message\Response */
			$response = $this->restClient->setBaseUrl($this->baseUrl)
				->delete('/{customerKey}/urlqueueitems/' . $documentId)
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
	public function deleteByUrl($url) {
		try {
			/** @var $response \Guzzle\http\Message\Response */
			$response = $this->restClient->setBaseUrl($this->baseUrl)
				->delete('/{customerKey}/urlqueueitems?url=' . rawurlencode($url))
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
	 * @param \SimpleXMLElement $xml
	 *
	 * @return \Searchperience\Api\Client\Domain\Document\Document
	 */
	protected function buildUrlQueueItemFromXml(\SimpleXMLElement $xml) {
		$urlQueues = $this->buildUrlQueueItemsFromXml($xml);
		return reset($urlQueues);
	}

	/**
	 * @param \SimpleXMLElement $xml
	 *
	 * @return \Searchperience\Api\Client\Domain\Document\Document[]
	 */
	protected function buildUrlQueueItemsFromXml(\SimpleXMLElement $xml) {
		$urlQueueArray = new UrlQueueItemCollection();
		if ($xml->totalCount instanceof \SimpleXMLElement) {
			$urlQueueArray->setTotalCount((integer) $xml->totalCount->__toString());
		}

		$urlQueues=$xml->xpath('item');
		foreach($urlQueues as $urlQueue) {
			$urlQueueAttributeArray = (array)$urlQueue->attributes();
			$urlQueueObject = new \Searchperience\Api\Client\Domain\UrlQueueItem\UrlQueueItem();
			$urlQueueObject->setDocumentId((integer)$urlQueueAttributeArray['@attributes']['id']);
			$urlQueueObject->setUrl((string)$urlQueue->url);
			$urlQueueObject->setDeleted((bool)(int)$urlQueue->deleted);
			$urlQueueObject->setFailCount((integer)$urlQueue->failCount);
			$urlQueueObject->setProcessingThreadId((integer)$urlQueue->processingThreadId);

			if (trim($urlQueue->processingStartTime) != '') {
				//we assume that the restapi always return y-m-d H:i:s in the utc format
				$processingStartTime = $this->dateTimeService->getDateTimeFromApiDateString($urlQueue->processingStartTime);
				if($processingStartTime instanceof  \DateTime) {
					$urlQueueObject->setProcessingStartTime($processingStartTime);
				}
			}

			$urlQueueObject->setLastError((string)$urlQueue->lastError);
			$urlQueueObject->setPriority((integer)$urlQueue->priority);

			$urlQueueArray->append($urlQueueObject);
		}
		return $urlQueueArray ;
	}

	/**
	 * Create an array containing only the available urlqueue property values.
	 *
	 * @param \Searchperience\Api\Client\Domain\UrlQueueItem\UrlQueueItem $urlQueue
	 * @return array
	 */
	protected function buildRequestArrayFromUrlQueue(\Searchperience\Api\Client\Domain\UrlQueueItem\UrlQueueItem $urlQueue) {
		$valueArray = array();

		if (!is_null($urlQueue->getDeleted()) && !$urlQueue->getDeleted() ) {
			$valueArray['deleted'] = 0;
		}
		if (!is_null($urlQueue->getDocumentId())) {
			$valueArray['documentId'] = $urlQueue->getDocumentId();
		}

			//only reset allowed
		if (!is_null($urlQueue->getFailCount()) && $urlQueue->getFailCount() == 0) {
			$valueArray['failCount'] = $urlQueue->getFailCount();
		}

		if (!is_null($urlQueue->getPriority())) {
			$valueArray['priority'] = $urlQueue->getPriority();
		}

		if (!is_null($urlQueue->getUrl())) {
			$valueArray['url'] = $urlQueue->getUrl();
		}

		// documentId is readonly and will not be persistet
		// lastError is readonly and will not be persistet
		// processingStartTime is readonly and will not be persistet
		// processingThreadId is readonly and will not be persistet

		return $valueArray;
	}

	/**
	 * @param array $arguments
	 * @return \Guzzle\Http\Message\Response
	 */
	protected function executePostRequest(array $arguments) {
		$response = $this->restClient->setBaseUrl($this->baseUrl)
				->post('/{customerKey}/urlqueueitems', NULL, $arguments)
				->setAuth($this->username, $this->password)
				->send();
		return $response;
	}

}
