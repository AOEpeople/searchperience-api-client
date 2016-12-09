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
	 * @var string
	 */
	protected static $defaultClass = '\Searchperience\Api\Client\Domain\Document\Document';

	/**
	 * @var array
	 */
	protected static $classMap = array(
		'\Searchperience\Api\Client\Domain\Document\Promotion' => array('text/searchperiencepromotion+xml'),
		'\Searchperience\Api\Client\Domain\Document\Product' => array('application/searchperience+xml')
	);

	/**
	 * Returns the name of the class that is able to handle the content of a
	 * specific mimeType.
	 *
	 * @param string $mimeType
	 * @return string
	 */
	public static function getClassNameForMimeType($mimeType) {
		foreach(self::$classMap as $className => $classMapEntry) {
			if(in_array($mimeType,$classMapEntry)) {
				return $className;
			}
		}

		return self::$defaultClass;
	}

	/**
	 * {@inheritdoc}
	 */
	public function post(\Searchperience\Api\Client\Domain\Document\AbstractDocument $document) {
		return $this->getPostResponseFromEndpoint($document);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getByForeignId($foreignId) {
		try {
			$response = $this->getGetResponseFromEndpoint('?foreignId=' . $foreignId);
			return $this->buildDocumentFromXml($response->xml());
		} catch (EntityNotFoundException $e) {
			return null;
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function getById($id) {
		try {
			$response = $this->getGetResponseFromEndpoint('/'.$id);
			return $this->buildDocumentFromXml($response->xml());
		} catch (EntityNotFoundException $e) {
			return null;
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function getByUrl($url) {
		try {
			$response = $this->getGetResponseFromEndpoint('?url=' . $url);
			return $this->buildDocumentFromXml($response->xml());
		} catch (EntityNotFoundException $e) {
			return null;
		}
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
	public function deleteByUrl($url) {
		$response = $this->getDeleteResponseFromEndpoint('?url=' . urlencode($url));
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
	 * @return \Searchperience\Api\Client\Domain\Document\AbstractDocument
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

			$mimeType       = (string)$document->mimeType;
			$className      = $this->getClassNameForMimeType($mimeType);
			$documentObject = new $className();
			$documentObject ->__setProperty('id',(integer)$documentAttributeArray['@attributes']['id']);
			$documentObject ->__setProperty('url',(string)$document->url);
			$documentObject ->__setProperty('foreignId',(string)$document->foreignId);
			$documentObject ->__setProperty('source',(string)$document->source);
			$documentObject ->__setProperty('boostFactor',(integer)$document->boostFactor);
			$documentObject ->__setProperty('content',(string)$document->content);
			$documentObject ->__setProperty('generalPriority',(integer)$document->generalPriority);
			$documentObject ->__setProperty('temporaryPriority',(integer)$document->temporaryPriority);
			$documentObject ->__setProperty('mimeType',(string)$document->mimeType);
			$documentObject ->__setProperty('isMarkedForProcessing',(integer)$document->isMarkedForProcessing);
			$documentObject ->__setProperty('isMarkedForDeletion',(integer)$document->isMarkedForDeletion);
			$documentObject ->__setProperty('isProminent',(integer)$document->isProminent);
			$documentObject	->__setProperty('isRedirectTo',(integer)$document->isRedirectTo);
			$documentObject	->__setProperty('isDuplicateOf',(integer)$document->isDuplicateOf);
			$documentObject ->__setProperty('errorCount',(integer)$document->errorCount);
			$documentObject ->__setProperty('lastErrorMessage',(string)$document->lastErrorMessage);
			$documentObject ->__setProperty('recrawlTimeSpan',(string)$document->recrawlTimeSpan);
			$documentObject ->__setProperty('internalNoIndex',(string)$document->internalNoIndex);
			$documentObject ->__setProperty('pageRank',(float)$document->pageRank);
			$documentObject ->__setProperty('solrCoreHints',(string)$document->solrCoreHints);

			if(trim($document->lastProcessingTime) != '') {
				//we assume that the restapi allways return y-m-d H:i:s in the utc format
				$lastProcessingDate = $this->dateTimeService->getDateTimeFromApiDateString($document->lastProcessingTime);
				$documentObject ->__setProperty('lastProcessingDate',$lastProcessingDate);
			}

			if(trim($document->lastCrawlingTime) != '') {
				//we assume that the restapi allways return y-m-d H:i:s in the utc format
				$lastCrawlingDateTime = $this->dateTimeService->getDateTimeFromApiDateString($document->lastCrawlingTime);
				$documentObject ->__setProperty('lastCrawlingDateTime',$lastCrawlingDateTime);
			}

			if(trim($document->updatedAt) != '') {
				//we assume that the restapi allways return y-m-d H:i:s in the utc format
				$updatedAt = $this->dateTimeService->getDateTimeFromApiDateString($document->updatedAt);
				$documentObject ->__setProperty('updatedAt',$updatedAt);
			}
			if(trim($document->createdAt) != '') {
				//we assume that the restapi allways return y-m-d H:i:s in the utc format
				$createdAt = $this->dateTimeService->getDateTimeFromApiDateString($document->createdAt);
				$documentObject ->__setProperty('createdAt',$createdAt);
			}

			$documentObject ->__setProperty('noIndex',(integer)$document->noIndex);
			$documentArray[]=$documentObject;

			$documentObject->afterReconstitution();
		}

		return $documentArray ;
	}

	/**
	 * Create an array containing only the available document property values.
	 *
	 * @param \Searchperience\Api\Client\Domain\Document\Document $document
	 * @return array
	 */
	protected function buildRequestArray(\Searchperience\Api\Client\Domain\AbstractEntity $document) {
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

		if ($document->getCreatedAt() instanceof \DateTime) {
			$valueArray['createdAt'] = $this->dateTimeService->getDateStringFromDateTime($document->getCreatedAt());
		}
		if ($document->getUpdatedAt() instanceof \DateTime) {
			$valueArray['updatedAt'] = $this->dateTimeService->getDateStringFromDateTime($document->getUpdatedAt());
		}

		return $valueArray;
	}
}
