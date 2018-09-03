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
     * @var string
     */
    protected $instanceName = null;

    /**
     * @return string
     */
    public function getCommandName() {
        return $this->commandName;
    }

    /**
     * @return int
     */
    public function getDuration() {
        return $this->duration;
    }

    /**
     * @return string
     */
    public function getLogMessage() {
        return $this->logMessage;
    }

    /**
     * @return int
     */
    public function getProcessId() {
        return $this->processId;
    }

    /**
     * @return \DateTime
     */
    public function getStartTime() {
        return $this->startTime;
    }

    /**
     * @return \DateTime
     */
    public function getEndTime() {
        return $this->endTime;
    }

    /**
     * @return string
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getBinary() {
        return $this->binary;
    }

    /**
     * @return string
     */
    public function getInstanceName()
    {
        return $this->instanceName;
    }

    /**
     * @param $instanceName
     */
    public function setInstanceName($instanceName)
    {
        $this->instanceName = $instanceName;
    }
}