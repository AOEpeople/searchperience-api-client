<?php

namespace Searchperience\Api\Client\Domain\CommandLog;

use Symfony\Component\Validator\Validation;
use Searchperience\Api\Client\System\Storage\AbstractRestBackend;
use Searchperience\Common\Exception\InvalidArgumentException;

/**
 * Class IndexerCommandLogRepository
 *
 * @package Searchperience\Api\Client\Domain
 * @author: Nikolay Diaur <nikolay.diaur@aoe.com>
 */
class CommandLogRepository {
	/**
	 * @var \Searchperience\Api\Client\System\Storage\CommandLogBackendInterface
	 */
	protected $storageBackend;

	/**
	 * @var \Symfony\Component\Validator\ValidatorInterface
	 */
	protected $commandLogValidator;

	/**
	 * @var \Searchperience\Api\Client\Domain\CommandLog\Filters\FilterCollectionFactory
	 */
	protected $filterCollectionFactory;

	/**
	 * Injects the storage backend.
	 *
	 * @param \Searchperience\Api\Client\System\Storage\CommandLogBackendInterface $storageBackend
	 * @return void
	 */
	public function injectStorageBackend(\Searchperience\Api\Client\System\Storage\CommandLogBackendInterface $storageBackend) {
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
	 * @param \Searchperience\Api\Client\Domain\CommandLog\Filters\FilterCollectionFactory $filterCollectionFactory
	 * @return void
	 */
	public function injectFilterCollectionFactory(\Searchperience\Api\Client\Domain\CommandLog\Filters\FilterCollectionFactory $filterCollectionFactory) {
		$this->filterCollectionFactory = $filterCollectionFactory;
	}

    /**
     * Get Commands log by process id
     *
     * @param integer $processId
     * @return CommandLog
     * @throws \Searchperience\Common\Exception\InvalidArgumentException
     */
    public function getByProcessId($processId) {
        if (!is_numeric($processId)) {
            throw new InvalidArgumentException('Method "' . __METHOD__ . '" accepts only integer values as $processId. Input was: ' . serialize($processId));
        }

        $commandLog = $this->checkTypeAndDecorate($this->storageBackend->getByProcessId($processId));
        return $commandLog;
    }

	/**
	 * Method to retrieve all command logs entities by filters
	 *
	 * @param int $start
	 * @param int $limit
	 * @param array $filterArguments
	 * @param string $sortingField
	 * @param string $sortingType
	 *
	 * @throws \Searchperience\Common\Exception\InvalidArgumentException
	 * @throws \Searchperience\Common\Http\Exception\DocumentNotFoundException
	 * @return \Searchperience\Api\Client\Domain\CommandLog\CommandLogCollection
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
	 * @return CommandLogCollection
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
		return $this->decorateIndexerCommandLogs($commandLogs);
	}

	/**
	 * @param CommandLogCollection $commandLogs
	 * @return CommandLogCollection
	 */
	private function decorateIndexerCommandLogs(CommandLogCollection $commandLogs) {
		$newCollection = new CommandLogCollection();
		$newCollection->setTotalCount($commandLogs->getTotalCount());
		foreach ($commandLogs as $commandLog) {
			$newCollection->append($this->checkTypeAndDecorate($commandLog));
		}
		return $newCollection;
	}

    /**
     * @param mixed $commandLog
     * @return mixed
     */
    protected function checkTypeAndDecorate($commandLog) {
		if($commandLog !== null) {
			return $this->decorateIndexerCommandLog($commandLog);
		}

		return $commandLog;
	}

	/**
	 * @param CommandLog $commandLog
	 * @return CommandLog
	 */
	protected function decorateIndexerCommandLog(CommandLog $commandLog) {
		return $commandLog;
	}
}