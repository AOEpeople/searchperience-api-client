<?php

namespace Searchperience\Api\Client\Domain\ActivityLogs\Filters;

use Symfony\Component\Validator\Constraints as Assert;
use Searchperience\Api\Client\Domain\Filters\AbstractDateFilter;

/**
 * Class LogTimeFilter
 * @package Searchperience\Api\Client\Domain\ActivityLogs\Filters
 * @author: Nikolay Diaur <nikolay.diaur@aoe.com>
 */
class LogTimeFilter extends AbstractDateFilter {

	/**
	 * @var string
	 */
	protected $filterString;

	/**
	 * @var \DateTime $logTimeEnd
	 * @Assert\DateTime(message="The value {{ value }} is not a valid datetime.")
	 */
	protected $logTimeEnd;

	/**
	 * @var \DateTime $logTimeStart
	 * @Assert\DateTime(message="The value {{ value }} is not a valid datetime.")
	 */
	protected $logTimeStart;

	/**
	 * @param string $logTimeStart
	 */
	public function setLogTimeStart($logTimeStart) {
		$this->logTimeStart = $logTimeStart;
	}

	/**
	 * @return string
	 */
	public function getLogTimeStart() {
		return $this->logTimeStart;
	}

	/**
	 * @param string $logTimeEnd
	 */
	public function setLogTimeEnd($logTimeEnd) {
		$this->logTimeEnd = $logTimeEnd;
	}

	/**
	 * @return string
	 */
	public function getLogTimeEnd() {
		return $this->logTimeEnd;
	}

	/**
	 * @return string
	 */
	public function getFilterString() {
		if (!empty($this->logTimeEnd)) {
			$this->filterString = sprintf("&logtimeStart=%s", rawurlencode($this->toString($this->getLogTimeEnd())));
		}
		if (!empty($this->logTimeStart)) {
			$this->filterString .= sprintf("&logtimeEnd=%s", rawurlencode($this->toString($this->getLogTimeStart())));
		}
		return $this->filterString;
	}
}