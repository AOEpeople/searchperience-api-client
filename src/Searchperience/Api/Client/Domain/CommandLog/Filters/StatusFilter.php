<?php

namespace Searchperience\Api\Client\Domain\CommandLog\Filters;

use Symfony\Component\Validator\Constraints as Assert;
use Searchperience\Api\Client\Domain\Filters\AbstractFilter;

/**
 * Class StatusFilter
 * @package Searchperience\Api\Client\Domain\Filters
 * @author: Timo Schmidt <timo.schmidt@aoe.com>
 */
class StatusFilter extends AbstractFilter {

	/**
	 * @var string $status
	 * @Assert\Type(type="string", message="The value {{ value }} is not a valid {{ type }}.")
	 */
	protected $status = true;

	/**
	 * @var string
	 */
	protected $filterString;

	/**
	 * @param boolean $status
	 */
	public function setStatus($status) {
		$this->status = $status;
	}

	/**
	 * @return boolean
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * @return string
	 */
	public function getFilterString() {
		if (isset($this->status)) {
			$this->filterString = sprintf("&status=%s", rawurlencode((string)$this->status));
		}

		return $this->filterString;
	}
}