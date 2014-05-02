<?php

namespace Searchperience\Api\Client\System\Storage;

use Guzzle\Http\Client;
use Searchperience\Api\Client\Domain\Document\DocumentCollection;
use Searchperience\Common\Exception\InvalidArgumentException;
use Searchperience\Common\Http\Exception\EntityNotFoundException;

/**
 * @author Michael Klapper <michael.klapper@aoemedia.de>
 * @date 14.11.12
 * @time 15:17
 */
class RestDocumentBackend extends \Searchperience\Api\Client\System\Storage\AbstractRestBackend implements \Searchperience\Api\Client\System\Storage\DocumentBackendInterface {

	/**
	 * @var string
	 */
	protected $endpoint = 'documents';

	/**
	 * {@inheritdoc}
	 */
	public function post(\Searchperience\Api\Client\Domain\Document\Document $document) {
		return $this->getPostResponseFromEndpoint($document);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getByForeignId($foreignId) {
		$response = $this->getGetResponseFromEndpoint('?foreignId=' . $foreignId);
		return $this->buildDocumentFromXml($response->xml());
	}

	/**
	 * {@inheritdoc}
	 */
	public function getById($id) {
		$response = $this->getGetResponseFromEndpoint('/'.$id);
		return $this->buildDocumentFromXml($response->xml());
	}

	/**
	 * {@inheritdoc}
	 */
	public function getByUrl($url) {
		$response = $this->getGetResponseFromEndpoint('?url=' . $url);
		return $this->buildDocumentFromXml($response->xml());
	}

	/**
	 * {@inheritdoc}
	 * @param int $start
	 * @param int $limit
	 * @param string $sortingField = ''
	 * @param string $sortingType = desc
	 * @param \Searchperience\Api\Client\Domain\Filters\FilterCollection $filtersCollection
	 * @return \Searchperience\Api\Client\Domain\Document\DocumentCollection
	 * @throws \Searchperience\Common\Exception\RuntimeException
	 */
	public function getAllByFilterCollection($start, $limit, \Searchperience\Api\Client\Domain\Filters\FilterCollection $filtersCollection = null, $sortingField = '', $sortingType = self::SORTING_DESC) {
		try {
			$response   = $this->getListResponseFromEndpoint($start, $limit, $filtersCollection, $sortingField, $sortingType);
			$xmlElement = $response->xml();
		} catch (EntityNotFoundException $e) {
			return new DocumentCollection();
		}

		return $this->buildDocumentsFromXml($xmlElement);
	}

	/**
	 * {@inheritdoc}
	 */
	public function deleteByForeignId($foreignId) {
		$response = $this->getDeleteResponseFromEndpoint('?foreignId=' . $foreignId);
		return $response->getStatusCode();
	}

	/**
	 * {@inheritdoc}
	 */
	public function deleteById($id) {
		$response = $this->getDeleteResponseFromEndpoint('/' . $id);
		return $response->getStatusCode();
	}

	/**
	 * {@inheritdoc}
	 */
	public function deleteBySource($source) {
		$response = $this->getDeleteResponseFromEndpoint('?source=' . $source);
		return $response->getStatusCode();
	}

	/**
	 * @param \SimpleXMLElement $xml
	 *
	 * @return \Searchperience\Api\Client\Domain\Document\Document
	 */
	protected function buildDocumentFromXml(\SimpleXMLElement $xml) {
		$documents = $this->buildDocumentsFromXml($xml);
		return reset($documents);
	}

	/**
	 * @param \SimpleXMLElement $xml
	 *
	 * @return \Searchperience\Api\Client\Domain\Document\DocumentCollection
	 */
	protected function buildDocumentsFromXml(\SimpleXMLElement $xml) {
		$documentArray = new DocumentCollection();
		if ($xml->totalCount instanceof \SimpleXMLElement) {
			$documentArray->setTotalCount((integer) $xml->totalCount->__toString());
		}
		$documents=$xml->xpath('document');
		foreach($documents as $document) {
			$documentAttributeArray = (array)$document->attributes();
			$documentObject = new \Searchperience\Api\Client\Domain\Document\Document();
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
			$documentObject	->setIsRedirectTo((integer)$document->isRedirectTo);
			$documentObject	->setIsDuplicateOf((integer)$document->isDuplicateOf);
			$documentObject ->setErrorCount((integer)$document->errorCount);
			$documentObject ->setLastErrorMessage((string)$document->lastErrorMessage);
			$documentObject ->setRecrawlTimeSpan((string)$document->recrawlTimeSpan);
			$documentObject ->setInternalNoIndex((string)$document->internalNoIndex);
			$documentObject ->setPageRank((float)$document->pageRank);
			$documentObject ->setSolrCoreHints((string)$document->solrCoreHints);


			if(trim($document->lastProcessingTime) != '') {
				//we assume that the restapi allways return y-m-d H:i:s in the utc format
				$lastProcessingDate = $this->dateTimeService->getDateTimeFromApiDateString($document->lastProcessingTime);
				$documentObject ->setLastProcessingDate($lastProcessingDate);
			}

			if(trim($document->lastCrawlingTime) != '') {
				//we assume that the restapi allways return y-m-d H:i:s in the utc format
				$lastCrawlingDateTime = $this->dateTimeService->getDateTimeFromApiDateString($document->lastCrawlingTime);
				$documentObject ->setLastCrawlingDateTime($lastCrawlingDateTime);
			}

			$documentObject ->setNoIndex((integer)$document->noIndex);
			$documentArray[]=$documentObject;
		}

		return $documentArray ;
	}

	/**
	 * Create an array containing only the available document property values.
	 *
	 * @param \Searchperience\Api\Client\Domain\Document\Document $document
	 * @return array
	 */
	protected function buildRequestArray($document) {
		if(!$document instanceof \Searchperience\Api\Client\Domain\Document\Document) {
			throw new \Searchperience\Common\Exception\RuntimeException('Wrong object passed to buildRequestArray method',1386845432);
		}

		$valueArray = array();

		if ($document->getLastProcessingDate() instanceof \DateTime) {
			$valueArray['lastProcessing'] = $this->dateTimeService->getDateStringFromDateTime($document->getLastProcessingDate());
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
		if (!is_null($document->getPageRank())) {
			$valueArray['pageRank'] = $document->getPageRank();
		}
		if (!is_null($document->getSolrCoreHints())) {
			$valueArray['solrCoreHints'] = $document->getSolrCoreHints();
		}

		return $valueArray;
	}
}
