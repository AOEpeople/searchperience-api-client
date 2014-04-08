<?php

namespace Searchperience\Api\Client\Domain\Enrichment;

use Searchperience\Api\Client\Domain\Enrichment\EnrichmentCollection;
use Symfony\Component\Validator\Validation;

/**
 * Class EnrichmentRepository
 * @package Searchperience\Api\Client\Domain
 * @author: Timo Schmidt <timo.schmidt@aoe.com>
 */
class EnrichmentRepository {
	/**
	 * @var \Searchperience\Api\Client\System\Storage\EnrichmentBackendInterface
	 */
	protected $storageBackend;

	/**
	 * @var \Symfony\Component\Validator\ValidatorInterface
	 */
	protected $enrichmentValidator;

	/**
	 * @var \Searchperience\Api\Client\Domain\Filters\FilterCollectionFactory
	 */
	protected $filterCollectionFactory;

	/**
	 * Injects the storage backend.
	 *
	 * @param \Searchperience\Api\Client\System\Storage\EnrichmentBackendInterface $storageBackend
	 * @return void
	 */
	public function injectStorageBackend(\Searchperience\Api\Client\System\Storage\EnrichmentBackendInterface $storageBackend) {
		$this->storageBackend = $storageBackend;
	}

	/**
	 * Injects the validation service
	 *
	 * @param \Symfony\Component\Validator\ValidatorInterface $urlQueueValidator
	 * @return void
	 */
	public function injectValidator(\Symfony\Component\Validator\ValidatorInterface $urlQueueValidator) {
		$this->enrichmentValidator = $urlQueueValidator;
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
	 * Add a new enrichment to the indexer
	 *
	 * @param \Searchperience\Api\Client\Domain\Enrichment\Enrichment $enrichment
	 * @throws \Searchperience\Common\Exception\InvalidArgumentException
	 * @return integer HTTP Status code
	 */
	public function add(\Searchperience\Api\Client\Domain\Enrichment\Enrichment $enrichment) {
		$violations = $this->enrichmentValidator->validate($enrichment);

		if ($violations->count() > 0) {
			throw new \Searchperience\Common\Exception\InvalidArgumentException('Given object of type "' . get_class($enrichment) . '" is not valid: ' . PHP_EOL . $violations);
		}

		$status = $this->storageBackend->post($enrichment);
		return $status;
	}

	/**
	 * Get Enrichment by id
	 *
	 * @param integer $id
	 * @return Enrichment
	 * @throws \Searchperience\Common\Exception\InvalidArgumentException
	 */
	public function getById($id) {
		if (!is_numeric($id)) {
			throw new \Searchperience\Common\Exception\InvalidArgumentException('Method "' . __METHOD__ . '" accepts only integer values as $id. Input was: ' . serialize($id));
		}

		$enrichment = $this->decorateEnrichment($this->storageBackend->getById($id));
		return $enrichment;
	}

	/**
	 * Method to retrieve all enrichment items by filters
	 *
	 * @param int $start
	 * @param int $limit
	 * @param array $filterArguments
	 *
	 * @throws \Searchperience\Common\Exception\InvalidArgumentException
	 * @return \Searchperience\Api\Client\Domain\Document\EnrichmentCollection
	 */
	public function getAllByFilters($start = 0, $limit = 10, array $filterArguments = array()) {
		if (!is_integer($start)) {
			throw new \Searchperience\Common\Exception\InvalidArgumentException('Method "' . __METHOD__ . '" accepts only integer values as $start. Input was: ' . serialize($start));
		}
		if (!is_integer($limit)) {
			throw new \Searchperience\Common\Exception\InvalidArgumentException('Method "' . __METHOD__ . '" accepts only integer values as $limit. Input was: ' . serialize($limit));
		}
		if (!is_array($filterArguments)) {
			throw new \Searchperience\Common\Exception\InvalidArgumentException('Method "' . __METHOD__ . '" accepts only integer values as $filterArguments. Input was: ' . serialize($filterArguments));
		}

		$filterCollection = $this->filterCollectionFactory->createFromFilterArguments($filterArguments);
		$enrichments = $this->getAllByFilterCollection($start, $limit, $filterCollection);

		return $enrichments;
	}


	/**
	 * @param $start
	 * @param $limit
	 * @param \Searchperience\Api\Client\Domain\Filters\FilterCollection $filtersCollection
	 * @return EnrichmentCollection
	 * @throws \Searchperience\Common\Exception\InvalidArgumentException
	 */
	public function getAllByFilterCollection($start, $limit, \Searchperience\Api\Client\Domain\Filters\FilterCollection $filtersCollection = null) {
		if (!is_integer($start)) {
			throw new \Searchperience\Common\Exception\InvalidArgumentException('Method "' . __METHOD__ . '" accepts only integer values as $start. Input was: ' . serialize($start));
		}
		if (!is_integer($limit)) {
			throw new \Searchperience\Common\Exception\InvalidArgumentException('Method "' . __METHOD__ . '" accepts only integer values as $limit. Input was: ' . serialize($limit));
		}

		$enrichments = $this->storageBackend->getAllByFilterCollection($start, $limit, $filtersCollection);
		return $this->decorateEnrichments($enrichments);
	}

	/**
	 * Delete a UrlQueueItem by the related document id
	 *
	 * The id can be a integer of:
	 * 0-9:
	 * Is valid if it is an alphanumeric string, which is defined as [[:alnum:]]
	 *
	 * @param string $id
	 *
	 * @throws \Searchperience\Common\Exception\InvalidArgumentException
	 * @throws \Searchperience\Common\Http\Exception\DocumentNotFoundException
	 * @return integer HTTP status code
	 */
	public function deleteById($id) {
		if (!is_numeric($id) ) {
			throw new \Searchperience\Common\Exception\InvalidArgumentException('Method "' . __METHOD__ . '" accepts only integers values as $id. Input was: ' . serialize($id));
		}

		$statusCode = $this->storageBackend->deleteById($id);
		return $statusCode;
	}


	/**
	 * @param EnrichmentCollection $enrichments
	 * @return EnrichmentCollection
	 */
	private function decorateEnrichments(EnrichmentCollection $enrichments) {
		$newCollection = new EnrichmentCollection();
		$newCollection->setTotalCount($enrichments->getTotalCount());
		foreach ($enrichments as $enrichment) {
			$newCollection->append($this->decorateEnrichment($enrichment));
		}
		return $newCollection;
	}

	/**
	 * Extend the class and override this method:
	 * This method gives you the possibility to decorate the enrichment object
	 *
	 * @param Enrichment $enrichment
	 * @return Enrichment
	 */
	protected function decorateEnrichment(Enrichment $enrichment) {
		return $enrichment;
	}
}