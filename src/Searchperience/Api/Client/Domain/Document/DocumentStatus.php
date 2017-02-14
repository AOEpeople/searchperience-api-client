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
	 * @var int
	 */
	protected $lastProcessedDate;

	/**
	 * @var int
	 */
	protected $processingCountLongerThan90Minutes;

	/**
	 * @var int
	 */
	protected $processedCountLast60Minutes;

	/**
	 * @var int
	 */
	protected $processedCountLast24Hours;

	/**
	 * @var int
	 */
	protected $errorCountLast60Minutes;

	/**
	 * @var int
	 */
	protected $errorCountLast24Hours;

	/**
	 * @var int
	 */
	protected $markedAsHiddenCount;

	/**
	 * @var int
	 */
	protected $markedAsHiddenCountInternal;

	/**
	 * @var int
	 */
	protected $markedAsHiddenCountByUser;

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

	/**
	 * @param $lastProcessedDate
	 */
	public function setLastProcessedDate($lastProcessedDate) {
	    $this->lastProcessedDate = $lastProcessedDate;
	}

	/**
	 * @return mixed
	 */
	public function getLastProcessedDate() {
	    return $this->lastProcessedDate;
	}

	/**
	 * @return mixed
	 */
	public function getProcessingCountLongerThan90Minutes() {
	    return $this->processingCountLongerThan90Minutes;
	}
	/**
	 * @param mixed $processingCountLongerThan90Minutes
	 */
	public function setProcessingCountLongerThan90Minutes($processingCountLongerThan90Minutes) {
	    $this->processingCountLongerThan90Minutes = $processingCountLongerThan90Minutes;
	}

	/**
	 * @return mixed
	 */
	public function getProcessedCountLast60Minutes() {
	    return $this->processedCountLast60Minutes;
	}

	/**
	 * @param mixed $processedCountLast60Minutes
	 */
	public function setProcessedCountLast60Minutes($processedCountLast60Minutes) {
	    $this->processedCountLast60Minutes = $processedCountLast60Minutes;
	}

	/**
	 * @return mixed
	 */
	public function getProcessedCountLast24Hours() {
	    return $this->processedCountLast24Hours;
	}

	/**
	 * @param mixed $processedCountLast24Hours
	 */
	public function setProcessedCountLast24Hours($processedCountLast24Hours) {
	    $this->processedCountLast24Hours = $processedCountLast24Hours;
	}

	/**
	 * @return mixed
	 */
	public function getErrorCountLast60Minutes() {
	    return $this->errorCountLast60Minutes;
	}

	/**
	 * @param mixed $errorCountLast60Minutes
	 */
	public function setErrorCountLast60Minutes($errorCountLast60Minutes) {
	    $this->errorCountLast60Minutes = $errorCountLast60Minutes;
	}

	/**
	 * @return mixed
	 */
	public function getErrorCountLast24Hours() {
	    return $this->errorCountLast24Hours;
	}

	/**
	 * @param mixed $errorCountLast24Hours
	 */
	public function setErrorCountLast24Hours($errorCountLast24Hours) {
	    $this->errorCountLast24Hours = $errorCountLast24Hours;
	}

	/**
	 * @return mixed
	 */
	public function getMarkedAsHiddenCount() {
	    return $this->markedAsHiddenCount;
	}

	/**
	 * @param mixed $markedAsHiddenCount
	 */
	public function setMarkedAsHiddenCount($markedAsHiddenCount) {
	    $this->markedAsHiddenCount = $markedAsHiddenCount;
	}

	/**
	 * @return mixed
	 */
	public function getMarkedAsHiddenCountInternal() {
	    return $this->markedAsHiddenCountInternal;
	}

	/**
	 * @param mixed $markedAsHiddenCountInternal
	 */
	public function setMarkedAsHiddenCountInternal($markedAsHiddenCountInternal) {
	    $this->markedAsHiddenCountInternal = $markedAsHiddenCountInternal;
	}

	/**
	 * @return mixed
	 */
	public function getMarkedAsHiddenCountByUser() {
	    return $this->markedAsHiddenCountByUser;
	}

	/**
	 * @param mixed $markedAsHiddenCountByUser
	 */
	public function setMarkedAsHiddenCountByUser($markedAsHiddenCountByUser) {
	    $this->markedAsHiddenCountByUser = $markedAsHiddenCountByUser;
	}
}
