<?php
/**
 * @Author: Nikolay Diaur <nikolay.diaur@aoe.com>
 * @Date: 8/15/14
 * @Time: 3:01 PM
 */

namespace Searchperience\Api\Client\Domain\ActivityLogs;

use Searchperience\Api\Client\Domain\AbstractRepository;
use Searchperience\Api\Client\System\Storage\AbstractRestBackend;
use Searchperience\Common\Exception\InvalidArgumentException;

/**
 * Class ActivityLogsRepository
 * @package Searchperience\Api\Client\Domain\ActivityLogs
 */
class ActivityLogsRepository {

	/**
	 * @var \Searchperience\Api\Client\System\Storage\ActivityLogsBackendInterface
	 */
	protected $storageBackend;

	/**
	 * @var \Symfony\Component\Validator\ValidatorInterface
	 */
	protected $commandLogValidator;

	/**
	 * @var \Searchperience\Api\Client\Domain\ActivityLogs\Filters\FilterCollectionFactory
	 */
	protected $filterCollectionFactory;

	/**
	 * Injects the storage backend.
	 *
	 * @param \Searchperience\Api\Client\System\Storage\ActivityLogsBackendInterface $storageBackend
	 * @return void
	 */
	public function injectStorageBackend(\Searchperience\Api\Client\System\Storage\ActivityLogsBackendInterface $storageBackend) {
		$this->storageBackend = $storageBackend;
	}

	/**
	 * Injects the validation service
	 *
	 * @param \Symfony\Component\Validator\ValidatorInterface $commandLogValidator
	 * @return void
	 */
	public function injectValidator(\Symfony\Component\Validator\ValidatorInterface $commandLogValidator) {
		$this->commandLogValidator = $commandLogValidator;
	}

	/**
	 * Injects the filter collection factory
	 *
	 * @param \Searchperience\Api\Client\Domain\ActivityLogs\Filters\FilterCollectionFactory $filterCollectionFactory
	 * @return void
	 */
	public function injectFilterCollectionFactory(\Searchperience\Api\Client\Domain\ActivityLogs\Filters\FilterCollectionFactory $filterCollectionFactory) {
		$this->filterCollectionFactory = $filterCollectionFactory;
	}

	/**
	 * Method to retrieve all activity logs entities by filters
	 *
	 * @param int $start
	 * @param int $limit
	 * @param array $filterArguments
	 * @param string $sortingField
	 * @param $sortingType
	 * @return ActivityLogsCollection
	 * @throws InvalidArgumentException
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
		$documents = $this->getAllByFilterCollection($start, $limit, $filterCollection, $sortingField, $sortingType);

		return $documents;
	}

	/**
	 * @param int $start
	 * @param int $limit
	 * @param \Searchperience\Api\Client\Domain\Filters\FilterCollection $filtersCollection
	 * @param string $sortingField
	 * @param string $sortingType
	 *
	 * @return ActivityLogsCollection
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

		$commandLogs = $this->storageBackend->getAllByFilterCollection($start, $limit, $filtersCollection, $sortingField, $sortingType);
		return $this->decorateIndexerActivityLogs($commandLogs);
	}

	/**
	 * @param ActivityLogsCollection $activityLogs
	 * @return ActivityLogsCollection
	 */
	private function decorateIndexerActivityLogs(ActivityLogsCollection $activityLogs) {
		$newCollection = new ActivityLogsCollection();
		$newCollection->setTotalCount($activityLogs->getTotalCount());
		foreach ($activityLogs as $activityLog) {
			$newCollection->append($this->checkTypeAndDecorate($activityLog));
		}
		return $newCollection;
	}

	/**
	 * @param $activityLog
	 * @return ActivityLogs
	 */
	protected function checkTypeAndDecorate($activityLog) {
		if ($activityLog !== null) {
			return $this->decorateIndexerActivityLog($activityLog);
		}
		return $activityLog;
	}

	/**
	 * @param ActivityLogs $activityLog
	 * @return ActivityLogs
	 */
	protected function decorateIndexerActivityLog(ActivityLogs $activityLog) {
		return $activityLog;
	}
} 