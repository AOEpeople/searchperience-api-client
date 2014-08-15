<?php
/**
 * @Author: Nikolay Diaur <nikolay.diaur@aoe.com>
 * @Date: 8/15/14
 * @Time: 3:00 PM
 */

namespace Searchperience\Api\Client\Domain\ActivityLogs;

use Searchperience\Api\Client\Domain\AbstractEntity;

/**
 * Class ActivityLogs
 * @package Searchperience\Api\Client\Domain\ActivityLogs
 */
class ActivityLogs extends AbstractEntity{

	/**
	 * Holds the id of the log record.
	 *
	 * @var int
	 */
	protected $id;

	/**
	 * Timestamp when the log was created.
	 *
	 * @var int
	 */
	protected $logTime = 0;

	/**
	 * @var string holds the severity of the log message
	 * @see System_Logger_LoggerInterface
	 */
	protected $severity = LOG_DEBUG;

	/**
	 * @var string
	 */
	protected $message = '';

	/**
	 * @var array
	 */
	protected $additionalData = array();

	/**
	 * @var string
	 */
	protected $packageKey = '';

	/**
	 * @var string
	 */
	protected $className = '';


	/**
	 * @var string
	 */
	protected $methodName = '';

	/**
	 * Method to return the id of the log record.
	 *
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Returns The passed logTime.
	 *
	 * @return int
	 */
	public function getLogTime() {
		return $this->logTime;
	}

	/**
	 * Returns the severity of the log entry.
	 *
	 * @return int
	 */
	public function getSeverity() {
		return $this->severity;
	}

	/**
	 * Returns the message as string.
	 *
	 * @return string
	 */
	public function getMessage() {
		return $this->message;
	}

	/**
	 * This method is used to retrieve additional attached data from the log
	 * record.
	 *
	 * @return mixed.
	 */
	public function getAdditionalData() {
		return $this->additionalData;
	}

	/**
	 * This method is used to retrieve the package key.
	 *
	 * @return string
	 */
	public function getPackageKey() {
		return $this->packageKey;
	}

	/**
	 * Method to retrieve the className.
	 *
	 * @return string
	 */
	public function getClassName() {
		return $this->className;
	}

	/**
	 * This method is used to retrieve the logged methodName.
	 *
	 * @return string
	 */
	public function getMethodName() {
		return $this->methodName;
	}
} 