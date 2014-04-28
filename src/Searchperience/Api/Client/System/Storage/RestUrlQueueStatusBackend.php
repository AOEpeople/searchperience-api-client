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
	 * {@inheritdoc}
	 * @return \Searchperience\Api\Client\Domain\Document\Document
	 * @throws \Searchperience\Common\Exception\RuntimeException
	 */
	public function get()
	{
		try {
			$xml = $this->getStatusXmlFromEndpoint();

		} catch (\Guzzle\Http\Exception\ClientErrorResponseException $exception) {
			$this->transformStatusCodeToClientErrorResponseException($exception);
		} catch (\Guzzle\Http\Exception\ServerErrorResponseException $exception) {
			$this->transformStatusCodeToServerErrorResponseException($exception);
		} catch (\Exception $exception) {
			throw new \Searchperience\Common\Exception\RuntimeException('Unknown error occurred; Please check parent exception for more details.', 1353579279, $exception);
		}

		return $this->buildUrlQueueStatusFromXml($xml);
	}

	/**
	 * @param $xml‚
	 */
	protected function buildUrlQueueStatusFromXml($xml) {
		$urlQueueStatus = new UrlQueueStatus();
		if(!$xml instanceof \SimpleXMLElement) {
			return $urlQueueStatus;
		}


		$urlQueueStatus->setWaitingCount((int) $xml->waitingCount);
		$urlQueueStatus->setProcessingCount((int) $xml->processingCount);
		$urlQueueStatus->setDeletedCount((int) $xml->deletedCount);
		$urlQueueStatus->setErrorCount((int) $xml->errorCount);
		$urlQueueStatus->setDeletedCount((int) $xml->deletedCount);
		$urlQueueStatus->setAllCount((int) $xml->allCount);

		return $urlQueueStatus;
	}

	/**
	 * @return \SimpleXMLElement
	 * @throws \Guzzle\Common\Exception\InvalidArgumentException
	 * @throws \Guzzle\Common\Exception\RuntimeException
	 */
	protected  function getStatusXmlFromEndpoint() {
		/** @var $response \Guzzle\http\Message\Response */
		$response = $this->restClient->setBaseUrl($this->baseUrl)
			->get('/{customerKey}/status/urlqueue')
			->setAuth($this->username, $this->password)
			->send();

		$xml = $response->xml();
		return $xml;
	}
}