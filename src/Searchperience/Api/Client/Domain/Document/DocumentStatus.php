<?php

namespace Searchperience\Api\Client\Domain\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Searchperience\Api\Client\Domain\AbstractEntity;

/**
 * @author Timo Schmidt <timo.schmidt@aoe.com>
 */
class DocumentStatus extends AbstractEntity{

	/**
	 * @var integer
	 */
	protected $allCount;

	/**
	 * @var integer
	 */
	protected $processingCount;

	/**
	 * @var integer
	 */
	protected $waitingCount;

	/**
	 * @var integer
	 */
	protected $processedCount;

	/**
	 * @var integer
	 */
	protected $deletedCount;

	/**
	 * @var integer
	 */
	protected $errorCount;

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
	public function getProcessedCount() {
		return $this->processedCount;
	}

	/**
	 * @param int $processedCount
	 */
	public function setProcessedCount($processedCount) {
		$this->processedCount = $processedCount;
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
}