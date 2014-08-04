<?php

namespace Searchperience\Api\Client\Domain\CommandLog\Filters;

use Symfony\Component\Validator\Constraints as Assert;
use Searchperience\Api\Client\Domain\Filters\AbstractDateFilter;

/**
 * Class TimeFilter
 * @package Searchperience\Api\Client\Domain\Document\Filters
 * @author: Nikolay Diaur <nikolay.diaur@aoe.com>
 */
class TimeFilter extends AbstractDateFilter {

	/**
	 * @var string
	 */
	protected $filterString;

	/**
	 * @var \DateTime $processEnd
	 * @Assert\DateTime(message="The value {{ value }} is not a valid datetime.")
	 */
	protected $startTime;

	/**
	 * @var \DateTime $processEnd
	 * @Assert\DateTime(message="The value {{ value }} is not a valid datetime.")
	 */
	protected $endTime;

	/**
	 * @param string $endTime
	 */
	public function setEndTime($endTime) {
		$this->endTime = $endTime;
	}

	/**
	 * @return string
	 */
	public function getEndTime() {
		return $this->endTime;
	}

	/**
	 * @param string $startTime
	 */
	public function setStartTime($startTime) {
		$this->startTime = $startTime;
	}

	/**
	 * @return string
	 */
	public function getStartTime() {
		return $this->startTime;
	}

	/**
	 * @return string
	 */
	public function getFilterString() {
		if (!empty($this->startTime)) {
			$this->filterString = sprintf("&startTime=%s", rawurlencode($this->toString($this->getStartTime())));
		}
		if (!empty($this->endTime)) {
			$this->filterString .= sprintf("&endTime=%s", rawurlencode($this->toString($this->getEndTime())));
		}
		return $this->filterString;
	}
}