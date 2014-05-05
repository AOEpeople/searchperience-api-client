<?php

namespace Searchperience\Api\Client\Domain\UrlQueueItem;

use Searchperience\Api\Client\Domain\AbstractEntity;

/**
 * Describes the status of the UrlQueue
 *
 * @package Searchperience\Api\Client\Domain
 * @author: Timo Schmidt <timo.schmidt@aoe.com>
 */
class UrlQueueStatus extends AbstractEntity {

	/**
	 * @var int
	 */
	protected $allCount = 0;

	/**
	 * @var int
	 */
	protected $waitingCount = 0;

	/**
	 * @var int
	 */
	protected $processingCount = 0;

	/**
	 * @var int
	 */
	protected $errorCount = 0;

	/**
	 * @var int
	 */
	protected $deletedCount = 0;

	/**
	 * @return int
	 */
	public function getAllCount() {
		return $this->allCount;
	}

	/**
	 * @param int $allCount
	 */
	public function setAllCount($allCount) {
		$this->allCount = $allCount;
	}

	/**
	 * @return int
	 */
	public function getDeletedCount() {
		return $this->deletedCount;
	}

	/**
	 * @param int $deletedCount
	 */
	public function setDeletedCount($deletedCount) {
		$this->deletedCount = $deletedCount;
	}

	/**
	 * @return int
	 */
	public function getErrorCount() {
		return $this->errorCount;
	}

	/**
	 * @param int $errorCount
	 */
	public function setErrorCount($errorCount) {
		$this->errorCount = $errorCount;
	}

	/**
	 * @return int
	 */
	public function getProcessingCount() {
		return $this->processingCount;
	}

	/**
	 * @param int $processingCount
	 */
	public function setProcessingCount($processingCount) {
		$this->processingCount = $processingCount;
	}

	/**
	 * @return int
	 */
	public function getWaitingCount() {
		return $this->waitingCount;
	}

	/**
	 * @param int $waitingCount
	 */
	public function setWaitingCount($waitingCount) {
		$this->waitingCount = $waitingCount;
	}
} 