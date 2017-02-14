<?php

namespace Searchperience\Api\Client\System\Storage;

use Guzzle\Http\Client;
use Searchperience\Api\Client\Domain\UrlQueueItem\UrlQueueItemCollection;
use Searchperience\Api\Client\Domain\UrlQueueItem\UrlQueueStatus;
use Searchperience\Common\Http\Exception\EntityNotFoundException;

/**
 * Class RestUrlqueueBackend
 * @package Searchperience\Api\Client\System\Storage
 */
class RestUrlQueueStatusBackend extends \Searchperience\Api\Client\System\Storage\AbstractRestBackend implements \Searchperience\Api\Client\System\Storage\UrlQueueStatusBackendInterface {

	/**
	 * @var string
	 */
	protected $endpoint = 'status/urlqueue';

	/**
	 * {@inheritdoc}
	 * @return \Searchperience\Api\Client\Domain\Document\Document
	 * @throws \Searchperience\Common\Exception\RuntimeException
	 */
	public function get() {
		try {
			$response = $this->getGetResponseFromEndpoint();
			return $this->buildUrlQueueStatusFromXml($response->xml());
		} catch (EntityNotFoundException $e) {
			return null;
		}
	}

	/**
	 * @param string $xml
	 * @return UrlQueueStatus
	 */
	protected function buildUrlQueueStatusFromXml($xml) {
		$urlQueueStatus = new UrlQueueStatus();
		if(!$xml instanceof \SimpleXMLElement) {
			return $urlQueueStatus;
		}

		$urlQueueStatus->__setProperty('waitingCount', (int) $xml->waiting->total);
		$urlQueueStatus->__setProperty('processingCount', (int) $xml->processing->total);
		$urlQueueStatus->__setProperty('deletedCount', (int) $xml->waiting->forDeletion);
		$urlQueueStatus->__setProperty('errorCount', (int) $xml->error->total);
		$urlQueueStatus->__setProperty('allCount', (int) $xml->total);

		$urlQueueStatus->afterReconstitution();

		return $urlQueueStatus;
	}
}
