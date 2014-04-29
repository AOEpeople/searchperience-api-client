<?php

namespace Searchperience\Api\Client\Domain\Document\Filters;

use Symfony\Component\Validator\Constraints as Assert;
use Searchperience\Api\Client\Domain\Filters\AbstractFilter;

/**
 * Class QueryFilter
 * @package Searchperience\Api\Client\Domain\Filters
 * @author: Timo Schmidt <timo.schmidt@aoe.com>
 */
class IsDuplicateFilter extends AbstractFilter {

	protected $isDuplicate = true;

	/**
	 * @var string
	 */
	protected $filterString;

	/**
	 * @return boolean
	 */
	public function isIsDuplicate() {
		return $this->isDuplicate;
	}

	/**
	 * @param boolean $isDuplicate
	 */
	public function setIsDuplicate($isDuplicate) {
		$this->isDuplicate = $isDuplicate;
	}

	/**
	 * @return string
	 */
	public function getFilterString() {
		if (isset($this->isDuplicate)) {
			$this->filterString = sprintf("&isDuplicate=%s", rawurlencode((int)$this->isDuplicate));
		}

		return $this->filterString;
	}
}