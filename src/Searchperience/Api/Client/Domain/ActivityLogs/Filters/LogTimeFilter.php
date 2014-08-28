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
	 * @var \DateTime $logtimeEnd
	 * @Assert\DateTime(message="The value {{ value }} is not a valid datetime.")
	 */
	protected $logtimeEnd;

	/**
	 * @var \DateTime $logtimeStart
	 * @Assert\DateTime(message="The value {{ value }} is not a valid datetime.")
	 */
	protected $logtimeStart;

	/**
	 * @param string $logtimeStart
	 */
	public function setLogtimeStart($logtimeStart) {
		$this->logtimeStart = $logtimeStart;
	}

	/**
	 * @return string
	 */
	public function getLogtimeStart() {
		return $this->logtimeStart;
	}

	/**
	 * @param string $logtimeEnd
	 */
	public function setLogtimeEnd($logtimeEnd) {
		$this->logtimeEnd = $logtimeEnd;
	}

	/**
	 * @return string
	 */
	public function getLogtimeEnd() {
		return $this->logtimeEnd;
	}

	/**
	 * @return string
	 */
	public function getFilterString() {
		if (!empty($this->logtimeStart)) {
			$this->filterString = sprintf("&logtimeStart=%s", rawurlencode($this->toString($this->getLogtimeStart())));
		}
		if (!empty($this->logtimeEnd)) {
			$this->filterString .= sprintf("&logtimeEnd=%s", rawurlencode($this->toString($this->getLogtimeEnd())));
		}
		return $this->filterString;
	}
}