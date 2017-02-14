<?php

namespace Searchperience\Api\Client\System\Storage;

use Guzzle\Http\Client;
use Searchperience\Api\Client\Domain\Document\DocumentCollection;
use Searchperience\Api\Client\Domain\Document\DocumentStatus;
use Searchperience\Common\Http\Exception\EntityNotFoundException;

/**
 * Class RestUrlqueueBackend
 * @package Searchperience\Api\Client\System\Storage
 */
class RestDocumentStatusBackend extends \Searchperience\Api\Client\System\Storage\AbstractRestBackend implements \Searchperience\Api\Client\System\Storage\DocumentStatusBackendInterface {

	/**
	 * @var string
	 */
	protected $endpoint = 'status/document';

	/**
	 * {@inheritdoc}
	 * @return \Searchperience\Api\Client\Domain\Document\Document
	 * @throws \Searchperience\Common\Exception\RuntimeException
	 */
	public function get() {
		try {
			$xml = $this->getStatusXmlFromEndpoint();
			return $this->buildDocumentStatusFromXml($xml);
		} catch (EntityNotFoundException $e) {
			return null;
		}
	}

	/**
	 * @return \SimpleXMLElement
	 * @throws \Guzzle\Common\Exception\InvalidArgumentException
	 * @throws \Guzzle\Common\Exception\RuntimeException
	 */
	protected function getStatusXmlFromEndpoint() {
		/** @var $response \Guzzle\http\Message\Response */
		$response = $this->getGetResponseFromEndpoint();
		return $response->xml();
	}

	/**
	 * @param $xml
	 * @return DocumentStatus
	 */
	protected function buildDocumentStatusFromXml($xml) {
		$documentStatus = new DocumentStatus();
		if(!$xml instanceof \SimpleXMLElement) {
			return $documentStatus;
		}

		$documentStatus->__setProperty('waitingCount', (int) $xml->waiting->total);
		$documentStatus->__setProperty('processingCount', (int) $xml->processing->total);
		$documentStatus->__setProperty('deletedCount', (int) $xml->waiting->forDeletion);
		$documentStatus->__setProperty('errorCount', (int) $xml->error->total);
		$documentStatus->__setProperty('allCount', (int) $xml->total);
		$documentStatus->__setProperty('processedCount', (int) $xml->processed->total);
		$documentStatus->__setProperty('lastProcessedDate', (int) $xml->processed->lastDate);
		$documentStatus->__setProperty('processingCountLongerThan90Minutes', (int) $xml->processing->longerThan90Minutes);
		$documentStatus->__setProperty('processedCountLast60Minutes', (int) $xml->processed->last60Minutes);
		$documentStatus->__setProperty('processedCountLast24Hours', (int) $xml->processed->last24Hours);
		$documentStatus->__setProperty('waitingCountLongerThan60Mins', (int) $xml->waiting->longerThan60Minutes);
		$documentStatus->__setProperty('errorCountLast60Minutes', (int) $xml->error->last60Minutes);
		$documentStatus->__setProperty('errorCountLast24Hours', (int) $xml->error->last24Hours);
		$documentStatus->__setProperty('markedAsHiddenCount', (int) $xml->hidden->total);
		$documentStatus->__setProperty('markedAsHiddenCountInternal', (int) $xml->hidden->internal);
		$documentStatus->__setProperty('markedAsHiddenCountByUser', (int) $xml->hidden->byUser);

		$documentStatus->afterReconstitution();

		return $documentStatus;
	}
}
