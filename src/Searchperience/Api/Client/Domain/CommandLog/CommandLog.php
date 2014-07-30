<?php

namespace Searchperience\Api\Client\Domain\CommandLog;

use Symfony\Component\Validator\Constraints as Assert;
use Searchperience\Api\Client\Domain\AbstractEntity;

/**
 * Class CommandLog
 * @package Searchperience\Api\Client\Domain
 * @author: Nikolay Diaur <nikolay.diaur@aoe.com>
 */
class CommandLog extends AbstractEntity {

	/**
	 * @var string
	 */
	protected $commandName;

	/**
	 * @var integer
	 */
	protected $processId;

	/**
	 * @var string
	 */
	protected $status;

	/**
	 * @var \DateTime
	 */
	protected $startTime = null;

    /**
     * @var \DateTime
     */
    protected $endTime = null;

	/**
	 * @var integer
	 */
	protected $duration;

	/**
	 * @var string
	 */
	protected $logMessage = null;

    /**
     * @var string
     */
    protected $binary = null;

    /**
     * @param string $commandName
     */
    public function setCommandName($commandName) {
        $this->commandName = $commandName;
    }

    /**
     * @return string
     */
    public function getCommandName() {
        return $this->commandName;
    }

    /**
     * @param int $duration
     */
    public function setDuration($duration) {
        $this->duration = $duration;
    }

    /**
     * @return int
     */
    public function getDuration() {
        return $this->duration;
    }

    /**
     * @param string $logMessage
     */
    public function setLogMessage($logMessage) {
        $this->logMessage = $logMessage;
    }

    /**
     * @return string
     */
    public function getLogMessage() {
        return $this->logMessage;
    }

    /**
     * @param int $processId
     */
    public function setProcessId($processId) {
        $this->processId = $processId;
    }

    /**
     * @return int
     */
    public function getProcessId() {
        return $this->processId;
    }



    /**
     * @param \DateTime $startTime
     */
    public function setStartTime($startTime) {
        $this->startTime = $startTime;
    }

    /**
     * @return \DateTime
     */
    public function getStartTime() {
        return $this->startTime;
    }

    /**
     * @param \DateTime $endTime
     */
    public function setEndTime($endTime) {
        $this->endTime = $endTime;
    }

    /**
     * @return \DateTime
     */
    public function getEndTime() {
        return $this->endTime;
    }

    /**
     * @param string $status
     */
    public function setStatus($status) {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * @param string $binary
     */
    public function setBinary($binary) {
        $this->binary = $binary;
    }

    /**
     * @return string
     */
    public function getBinary() {
        return $this->binary;
    }
}