<?php

namespace Searchperience\Api\Client\Domain\UrlQueueItem;

use Symfony\Component\Validator\Validation;
use Searchperience\Api\Client\System\Storage\AbstractRestBackend;
use Searchperience\Common\Exception\InvalidArgumentException;

/**
 * Class UrlQueueItemRepository
 *
 * @package Searchperience\Api\Client\Domain
 * @author: Nikolay Diaur <nikolay.diaur@aoe.com>
 */
class UrlQueueItemRepository {
	/**
	 * @var \Searchperience\Api\Client\System\Storage\UrlQueueItemBackendInterface
	 */
	protected $storageBackend;

	/**
	 * @var \Symfony\Component\Validator\ValidatorInterface
	 */
	protected $urlQueueValidator;

	/**
	 * @var \Searchperience\Api\Client\Domain\Filters\FilterCollectionFactory
	 */
	protected $filterCollectionFactory;

	/**
	 * Injects the storage backend.
	 *
	 * @param \Searchperience\Api\Client\System\Storage\UrlQueueItemBackendInterface $storageBackend
	 * @return void
	 */
	public function injectStorageBackend(\Searchperience\Api\Client\System\Storage\UrlQueueItemBackendInterface $storageBackend) {
		$this->storageBackend = $storageBackend;
	}

	/**
	 * Injects the validation service
	 *
	 * @param \Symfony\Component\Validator\ValidatorInterface $urlQueueValidator
	 * @return void
	 */
	public function injectValidator(\Symfony\Component\Validator\ValidatorInterface $urlQueueValidator) {
		$this->urlQueueValidator = $urlQueueValidator;
	}

	/**
	 * Injects the filter collection factory
	 *
	 * @param \Searchperience\Api\Client\Domain\UrlQueueItem\Filters\FilterCollectionFactory $filterCollectionFactory
	 * @return void
	 */
	public function injectFilterCollectionFactory(\Searchperience\Api\Client\Domain\UrlQueueItem\Filters\FilterCollectionFactory $filterCollectionFactory) {
		$this->filterCollectionFactory = $filterCollectionFactory;
	}

	/**
	 * Add a new Document to the index
	 *
	 * @param \Searchperience\Api\Client\Domain\UrlQueueItem\UrlQueueItem $urlQueueItem
	 * @throws \Searchperience\Common\Exception\InvalidArgumentException
	 * @return integer HTTP Status code
	 */
	public function add(\Searchperience\Api\Client\Domain\UrlQueueItem\UrlQueueItem $urlQueueItem) {
		$violations = $this->urlQueueValidator->validate($urlQueueItem);

		if ($violations->count() > 0) {
			throw new InvalidArgumentException('Given object of type "' . get_class($urlQueueItem) . '" is not valid: ' . PHP_EOL . $violations);
		}

		$status = $this->storageBackend->post($urlQueueItem);
		return $status;
	}

	/**
	 * Get url queue items by state
	 *
	 * @param int $start
	 * @param int $limit
	 * @param array $states
	 * @param string $sortingField
	 * @param string $sortingType
	 * @return UrlQueueItemCollection
	 * @throws \Searchperience\Common\Exception\InvalidArgumentException
	 */
	public function getAllByStates($start = 0, $limit = 10, $states = array(), $sortingField = '', $sortingType = AbstractRestBackend::SORTING_DESC) {
		$filterCollection = $this->filterCollectionFactory->createFromUrlQueueItemStates($states);

		if (!is_array($states)) {
			throw new InvalidArgumentException('Method "' . __METHOD__ . '" accepts only integer values as $state. Input was: ' . serialize($states));
		}
		if (!is_integer($start)) {
			throw new InvalidArgumentException('Method "' . __METHOD__ . '" accepts only integer values as $start. Input was: ' . serialize($start));
		}
		if (!is_integer($limit)) {
			throw new InvalidArgumentException('Method "' . __METHOD__ . '" accepts only integer values as $limit. Input was: ' . serialize($limit));
		}
		if (!is_string($sortingField)) {
			throw new InvalidArgumentException('Method "' . __METHOD__ . '" accepts only string values as $sortingField. Input was: ' . serialize($sortingField));
		}
		if (!is_string($sortingType)) {
			throw new InvalidArgumentException('Method "' . __METHOD__ . '" accepts only string values as $sortingType. Input was: ' . serialize($sortingType));
		}

		return $this->getAllByFilterCollection($start, $limit, $filterCollection, $sortingField, $sortingType);
	}

	/**
	 * Get UrlQueue by document id
	 *
	 * @param integer $documentId
	 * @return UrlQueueItem
	 * @throws \Searchperience\Common\Exception\InvalidArgumentException
	 */
	public function getByDocumentId($documentId) {
		if (!is_numeric($documentId)) {
			throw new InvalidArgumentException('Method "' . __METHOD__ . '" accepts only integer values as $documentId. Input was: ' . serialize($documentId));
		}

		$urqueue = $this->decorateUrlQueueItem($this->storageBackend->getByDocumentId($documentId));
		return $urqueue;
	}

	/**
	 * Get a Document by url
	 *
	 * @param string $url
	 * @return UrlQueueItem
	 * @throws \Searchperience\Common\Exception\InvalidArgumentException
	 */
	public function getByUrl($url) {
		if (!is_string($url)) {
			throw new InvalidArgumentException('Method "' . __METHOD__ . '" accepts only strings values as $url. Input was: ' . serialize($url));
		}

		$urqueue = $this->decorateUrlQueueItem($this->storageBackend->getByUrl($url));
		return $urqueue;
	}

	/**
	 * Method to retrieve all urlqueue items by filters
	 *
	 * @param int $start
	 * @param int $limit
	 * @param array $filterArguments
	 * @param string $sortingField
	 * @param string $sortingType
	 *
	 * @throws \Searchperience\Common\Exception\InvalidArgumentException
	 * @return \Searchperience\Api\Client\Domain\Document\UrlQueueItemCollection
	 */
	public function getAllByFilters($start = 0, $limit = 10, array $filterArguments = array(), $sortingField = '', $sortingType = AbstractRestBackend::SORTING_DESC) {
		if (!is_integer($start)) {
			throw new InvalidArgumentException('Method "' . __METHOD__ . '" accepts only integer values as $start. Input was: ' . serialize($start));
		}
		if (!is_integer($limit)) {
			throw new InvalidArgumentException('Method "' . __METHOD__ . '" accepts only integer values as $limit. Input was: ' . serialize($limit));
		}
		if (!is_array($filterArguments)) {
			throw new InvalidArgumentException('Method "' . __METHOD__ . '" accepts only integer values as $filterArguments. Input was: ' . serialize($filterArguments));
		}
		if (!is_string($sortingField)) {
			throw new InvalidArgumentException('Method "' . __METHOD__ . '" accepts only string values as $sortingField. Input was: ' . serialize($sortingField));
		}
		if (!is_string($sortingType)) {
			throw new InvalidArgumentException('Method "' . __METHOD__ . '" accepts only string values as $sortingType. Input was: ' . serialize($sortingType));
		}


		$filterCollection = $this->filterCollectionFactory->createFromFilterArguments($filterArguments);
		$urqueue = $this->getAllByFilterCollection($start, $limit, $filterCollection, $sortingField, $sortingType);

		return $urqueue;
	}

	/**
	 * @param int $start
	 * @param int $limit
	 * @param \Searchperience\Api\Client\Domain\Filters\FilterCollection $filtersCollection
	 * @param string $sortingField
	 * @param string $sortingType
	 *
	 * @return UrlQueueItemCollection
	 * @throws \Searchperience\Common\Exception\InvalidArgumentException
	 */
	public function getAllByFilterCollection($start, $limit, \Searchperience\Api\Client\Domain\Filters\FilterCollection $filtersCollection = null, $sortingField = '', $sortingType = AbstractRestBackend::SORTING_DESC) {
		if (!is_integer($start)) {
			throw new InvalidArgumentException('Method "' . __METHOD__ . '" accepts only integer values as $start. Input was: ' . serialize($start));
		}
		if (!is_integer($limit)) {
			throw new InvalidArgumentException('Method "' . __METHOD__ . '" accepts only integer values as $limit. Input was: ' . serialize($limit));
		}
		if (!is_string($sortingField)) {
			throw new InvalidArgumentException('Method "' . __METHOD__ . '" accepts only string values as $sortingField. Input was: ' . serialize($sortingField));
		}
		if (!is_string($sortingType)) {
			throw new InvalidArgumentException('Method "' . __METHOD__ . '" accepts only string values as $sortingType. Input was: ' . serialize($sortingType));
		}

		$urqueues = $this->storageBackend->getAllByFilterCollection($start, $limit, $filtersCollection, $sortingField, $sortingType);
		return $this->decorateUrlQueueItems($urqueues);
	}

	/**
	 * Delete a UrlQueueItem by the related document id
	 *
	 * The id can be a integer of:
	 * 0-9:
	 * Is valid if it is an alphanumeric string, which is defined as [[:alnum:]]
	 *
	 * @param string $documentId
	 *
	 * @throws \Searchperience\Common\Exception\InvalidArgumentException
	 * @throws \Searchperience\Common\Http\Exception\DocumentNotFoundException
	 * @return integer HTTP status code
	 */
	public function deleteByDocumentId($documentId) {
		if (!is_numeric($documentId) ) {
			throw new InvalidArgumentException('Method "' . __METHOD__ . '" accepts only integers values as $id. Input was: ' . serialize($documentId));
		}

		$statusCode = $this->storageBackend->deleteByDocumentId($documentId);
		return $statusCode;
	}

	/**
	 * Delete a UrlQueueItem by url
	 *
	 * @param string $url
	 *
	 * @throws \Searchperience\Common\Exception\InvalidArgumentException
	 * @throws \Searchperience\Common\Http\Exception\DocumentNotFoundException
	 * @return integer HTTP status code
	 */
	public function deleteByUrl($url) {
		if ( !is_string($url) ) {
			throw new InvalidArgumentException('Method "' . __METHOD__ . '" accepts only strings values as $url. Input was: ' . serialize($url));
		}

		$statusCode = $this->storageBackend->deleteByUrl($url);
		return $statusCode;
	}

	/**
	 * @param UrlQueueItemCollection $urqueues
	 * @return UrlQueueItemCollection
	 */
	private function decorateUrlQueueItems(UrlQueueItemCollection $urqueues) {
		$newCollection = new UrlQueueItemCollection();
		$newCollection->setTotalCount($urqueues->getTotalCount());
		foreach ($urqueues as $urqueue) {
			$newCollection->append($this->decorateUrlQueueItem($urqueue));
		}
		return $newCollection;
	}

	/**
	 * Extend the class and override this method:
	 * This method gives you the possibility to decorate the urlqueue object
	 *
	 * @param UrlQueueItem $urlQueueItem
	 * @return UrlQueueItem
	 */
	protected function decorateUrlQueueItem(UrlQueueItem $urlQueueItem) {
		return $urlQueueItem;
	}
}