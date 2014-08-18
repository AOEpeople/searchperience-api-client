<?php

namespace Searchperience\Api\Client\Domain\ActivityLogs\Filters;

use Symfony\Component\Validator\Constraints as Assert;
use Searchperience\Api\Client\Domain\Filters\AbstractFilter;

/**
 * Class SeverityFilter
 * @package Searchperience\Api\Client\Domain\ActivityLogs\Filters
 * @author: Nikolay Diaur <nikolay.diaur@aoe.com>
 */
class SeverityFilter extends AbstractFilter {

	/**
	 * @var string
	 */
	protected $filterString;

	/**
	 * @var string $severityStart
	 * @Assert\Type(type="integer", message="The value {{ value }} is not a valid {{ type }}.")
	 */
	protected $severityStart;

	/**
	 * @var string $severityEnd
	 * @Assert\Type(type="integer", message="The value {{ value }} is not a valid {{ type }}.")
	 */
	protected $severityEnd;

	/**
	 * @param string $severityEnd
	 */
	public function setSeverityEnd($severityEnd) {
		$this->severityEnd = $severityEnd;
	}

	/**
	 * @return string
	 */
	public function getSeverityEnd() {
		return $this->severityEnd;
	}

	/**
	 * @param string $severityStart
	 */
	public function setSeverityStart($severityStart) {
		$this->severityStart = $severityStart;
	}

	/**
	 * @return string
	 */
	public function getSeverityStart() {
		return $this->severityStart;
	}

	/**
	 * @return string
	 */
	public function getFilterString() {
		if (!empty($this->severityStart)) {
			$this->filterString .= sprintf("&severityStart=%s", rawurlencode($this->getSeverityStart()));
		}
		if (!empty($this->severityEnd)) {
			$this->filterString .= sprintf("&severityEnd=%s", rawurlencode($this->getSeverityEnd()));
		}
		return $this->filterString;
	}
}