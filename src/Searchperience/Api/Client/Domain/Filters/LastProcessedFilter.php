<?php
/**
 * @Author: Nikolay Diaur <nikolay.diaur@aoe.com>
 * @Date: 2/24/14
 * @Time: 6:19 PM
 */
namespace Searchperience\Api\Client\Domain\Filters;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class LastProcessedFilter
 * @package Searchperience\Api\Client\Domain\Filters
 */
class LastProcessedFilter {

	/**
	 * @var string
	 */
	protected $filterString;

	/**
	 * @var string $processStart
	 * @Assert\DateTime(message="The value {{ value }} is not a valid datetime.")
	 */
	protected $processStart;

	/**
	 * @var string $processEnd
	 * @Assert\DateTime(message="The value {{ value }} is not a valid datetime.")
	 */
	protected $processEnd;

	/**
	 * @param string $processEnd
	 */
	public function setProcessEnd($processEnd) {
		$this->processEnd = $processEnd;
	}

	/**
	 * @return string
	 */
	public function getProcessEnd() {
		return $this->processEnd;
	}

	/**
	 * @param string $processStart
	 */
	public function setProcessStart($processStart) {
		$this->processStart = $processStart;
	}

	/**
	 * @return string
	 */
	public function getProcessStart() {
		return $this->processStart;
	}

	/**
	 * @return string
	 */
	public function getFilterString() {
		if (!empty($this->processStart)) {
			$this->filterString = sprintf("&processStart=%s", rawurlencode($this->getProcessStart()));
		}
		if (!empty($this->processEnd)) {
			$this->filterString .= sprintf("&processEnd=%s", rawurlencode($this->getProcessEnd()));
		}
		return $this->filterString;
	}
} 