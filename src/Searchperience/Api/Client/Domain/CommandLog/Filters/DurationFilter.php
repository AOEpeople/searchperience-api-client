<?php

namespace Searchperience\Api\Client\Domain\CommandLog\Filters;

use Symfony\Component\Validator\Constraints as Assert;
use Searchperience\Api\Client\Domain\Filters\AbstractFilter;

/**
 * Class DurationFilter
 * @package Searchperience\Api\Client\Domain\Document\Filters
 * @author: Nikolay Diaur <nikolay.diaur@aoe.com>
 */
class DurationFilter extends AbstractFilter {

	/**
	 * @var string
	 */
	protected $filterString;

	/**
	 * @var string $duration
	 * @Assert\Type(type="integer", message="The value {{ value }} is not a valid {{ type }}.")
	 */
	protected $duration;

	/**
	 * @var string $durationFrom
	 * @Assert\Type(type="integer", message="The value {{ value }} is not a valid {{ type }}.")
	 */
	protected $durationFrom;

	/**
	 * @var string $durationTo
	 * @Assert\Type(type="integer", message="The value {{ value }} is not a valid {{ type }}.")
	 */
	protected $durationTo;

	/**
	 * @param string $durationTo
	 */
	public function setDurationTo($durationTo) {
		$this->durationTo = $durationTo;
	}

	/**
	 * @return string
	 */
	public function getDurationTo() {
		return $this->durationTo;
	}

	/**
	 * @param string $durationFrom
	 */
	public function setDurationFrom($durationFrom) {
		$this->durationFrom = $durationFrom;
	}

	/**
	 * @return string
	 */
	public function getDurationFrom() {
		return $this->durationFrom;
	}

	/**
	 * @param string $duration
	 */
	public function setDuration($duration) {
		$this->duration = $duration;
	}

	/**
	 * @return string
	 */
	public function getDuration() {
		return $this->duration;
	}

	/**
	 * @return string
	 */
	public function getFilterString() {
		if (!empty($this->duration)) {
			$this->filterString = sprintf("&duration=%s", rawurlencode($this->getDuration()));
		}
		if (!empty($this->durationFrom)) {
			$this->filterString .= sprintf("&durationFrom=%s", rawurlencode($this->getDurationFrom()));
		}
		if (!empty($this->durationTo)) {
			$this->filterString .= sprintf("&durationTo=%s", rawurlencode($this->getDurationTo()));
		}
		return $this->filterString;
	}
}