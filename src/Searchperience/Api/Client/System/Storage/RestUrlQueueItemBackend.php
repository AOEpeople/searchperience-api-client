<?php

namespace Searchperience\Api\Client\System\Storage;

use Guzzle\Http\Client;
use Searchperience\Api\Client\Domain\UrlQueueItem\UrlQueueItemCollection;
use Searchperience\Common\Http\Exception\EntityNotFoundException;

/**
 * Class RestUrlqueueBackend
 * @package Searchperience\Api\Client\System\Storage
 */
class RestUrlQueueItemBackend extends \Searchperience\Api\Client\System\Storage\AbstractRestBackend implements \Searchperience\Api\Client\System\Storage\UrlQueueItemBackendInterface {

	/**
	 * @var string
	 */
	protected $endpoint = 'urlqueueitems';

	/**
	 * {@inheritdoc}
	 */
	public function post(\Searchperience\Api\Client\Domain\UrlQueueItem\UrlQueueItem $urlQueue) {
		return $this->getPostResponseFromEndpoint($urlQueue);
	}

	/**
	 * {@inheritdoc}
	 * @param int $documentId
	 * @return \Searchperience\Api\Client\Domain\Document\Document
	 * @throws \Searchperience\Common\Exception\RuntimeException
	 */
	public function getByDocumentId($documentId) {
		$response = $this->getGetResponseFromEndpoint('/'.$documentId);
		return $this->buildUrlQueueItemFromXml($response->xml());
	}

	/**
	 * {@inheritdoc}
	 * @param string $url
	 * @return \Searchperience\Api\Client\Domain\Document\Document
	 * @throws \Searchperience\Common\Exception\RuntimeException
	 */
	public function getByUrl($url) {
		$response = $this->getGetResponseFromEndpoint('?url=' . $url);
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
		try {
			$response   = $this->getListResponseFromEndpoint($start, $limit, $filtersCollection, $sortingField, $sortingType);
			$xmlElement = $response->xml();
		} catch (EntityNotFoundException $e) {
			return new UrlQueueItemCollection();
		}

		return $this->buildUrlQueueItemsFromXml($xmlElement);
	}

	/**
	 * {@inheritdoc}
	 */
	public function deleteByDocumentId($documentId) {
		$response = $this->getDeleteResponseFromEndpoint('/' . $documentId);
		return $response->getStatusCode();
	}

	/**
	 * {@inheritdoc}
	 */
	public function deleteByUrl($url) {
		$response = $this->getDeleteResponseFromEndpoint('?url=' . rawurlencode($url));
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
			$urlQueueObject->__setProperty('documentId',(integer)$urlQueueAttributeArray['@attributes']['id']);
			$urlQueueObject->__setProperty('url',(string)$urlQueue->url);
			$urlQueueObject->__setProperty('deleted',(bool)(int)$urlQueue->deleted);
			$urlQueueObject->__setProperty('failCount',(integer)$urlQueue->failCount);
			$urlQueueObject->__setProperty('processingThreadId',(integer)$urlQueue->processingThreadId);

			if (trim($urlQueue->processingStartTime) != '') {
				//we assume that the restapi always return y-m-d H:i:s in the utc format
				$processingStartTime = $this->dateTimeService->getDateTimeFromApiDateString($urlQueue->processingStartTime);
				if($processingStartTime instanceof  \DateTime) {
					$urlQueueObject->__setProperty('processingStartTime',$processingStartTime);
				}
			}

			$urlQueueObject->__setProperty('lastError',(string)$urlQueue->lastError);
			$urlQueueObject->__setProperty('priority',(integer)$urlQueue->priority);

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
	protected function buildRequestArray($urlQueue) {
		if(!$urlQueue instanceof \Searchperience\Api\Client\Domain\UrlQueueItem\UrlQueueItem  ) {
			throw new \Searchperience\Common\Exception\RuntimeException('Wrong object passed to buildRequestArray method',1386845451);
		}

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
}
