<?php

namespace Searchperience\Api\Client\Domain\UrlQueueItem;

use Symfony\Component\Validator\Constraints as Assert;
use Searchperience\Api\Client\Domain\AbstractEntity;

/**
 * Class Urlqueue
 * @package Searchperience\Api\Client\Domain
 * @author: Nikolay Diaur <nikolay.diaur@aoe.com>
 */
class UrlQueueItem extends AbstractEntity {

	/**
	 * Indicates that document for this url was deleted.
	 *
	 * @var string
	 */
	const IS_DOCUMENT_DELETED = 'IS_DOCUMENT_DELETED';

	/**
	 * Indicates that this url is marked as waiting.
	 *
	 *
	 * @var string
	 */
	const IS_WAITING = 'IS_WAITING';

	/**
	 * Indicates that this url was deleted.
	 *
	 * @var string
	 */
	const IS_ERROR = 'IS_ERROR';

	/**
	 * Indicates that this url is marked as in process.
	 *
	 * @var string
	 */
	const IS_PROCESSING = 'IS_PROCESSING';

	/**
	 * @var array
	 */
	protected static $validNotifications = array(
		self::IS_DOCUMENT_DELETED,
		self::IS_WAITING,
		self::IS_ERROR,
		self::IS_PROCESSING
	);

	/**
	 * @var integer
	 */
	protected $documentId;

	/**
	 * @var string
	 * @Assert\Url
	 * @Assert\Length(max = 1000)
	 */
	protected $url;

	/**
	 * @var integer
	 */
	protected $processingThreadId = 0;

	/**
	 * @var \DateTime
	 */
	protected $processingStartTime = null;

	/**
	 * @var integer
	 */
	protected $failCount;

	/**
	 * @var bool
	 */
	protected $deleted = 0;

	/**
	 * @var string
	 * @Assert\Length(max = 1000)
	 */
	protected $lastError = null;

	/**
	 * @var integer
	 */
	protected $priority = null;

	/**
	 * @return array
	 */
	public static function getValidNotifications() {
		return self::$validNotifications;
	}

	/**
	 * @param boolean $deleted
	 */
	public function setDeleted($deleted) {
		$this->deleted = $deleted;
	}

	/**
	 * @return boolean
	 */
	public function getDeleted() {
		return $this->deleted;
	}

	/**
	 * @param int $documentId
	 */
	public function setDocumentId($documentId) {
		$this->documentId = $documentId;
	}

	/**
	 * @return int
	 */
	public function getDocumentId() {
		return $this->documentId;
	}

	/**
	 * @param int $failCount
	 */
	public function setFailCount($failCount) {
		$this->failCount = $failCount;
	}

	/**
	 * @return int
	 */
	public function getFailCount() {
		return $this->failCount;
	}

	/**
	 * @param string $lastError
	 */
	public function setLastError($lastError) {
		$this->lastError = $lastError;
	}

	/**
	 * @return string
	 */
	public function getLastError() {
		return $this->lastError;
	}

	/**
	 * @param int $priority
	 */
	public function setPriority($priority) {
		$this->priority = $priority;
	}

	/**
	 * @return int
	 */
	public function getPriority() {
		return $this->priority;
	}

	/**
	 * @param \DateTime $processingStartTime
	 */
	public function setProcessingStartTime(\DateTime $processingStartTime) {
		$this->processingStartTime = $processingStartTime;
	}

	/**
	 * @return \DateTime
	 */
	public function getProcessingStartTime() {
		return $this->processingStartTime;
	}

	/**
	 * @param int $processingThreadId
	 */
	public function setProcessingThreadId($processingThreadId) {
		$this->processingThreadId = $processingThreadId;
	}

	/**
	 * @return int
	 */
	public function getProcessingThreadId() {
		return $this->processingThreadId;
	}

	/**
	 * @param string $url
	 */
	public function setUrl($url) {
		$this->url = $url;
	}

	/**
	 * @return string
	 */
	public function getUrl() {
		return $this->url;
	}

	/**
	 * Retrieve the state of a current urlqueue:
	 * - has an error
	 * - is waiting of
	 * - is currently in processing
	 * - is deleted
	 *
	 * @return array
	 */

	public function getNotifications() {
		$notifications = array();

		if (trim($this->lastError) !== "") {
			$notifications[] = UrlQueueItem::IS_ERROR;
		}

		if ($this->processingThreadId == 0 && $this->deleted == 0) {
			$notifications[] = UrlQueueItem::IS_WAITING;
		}

		if ($this->processingThreadId != 0) {
			$notifications[] = UrlQueueItem::IS_PROCESSING;
		}

		if ($this->deleted == 1) {
			$notifications[] = UrlQueueItem::IS_DOCUMENT_DELETED;
		}

		return array_unique($notifications);
	}
}