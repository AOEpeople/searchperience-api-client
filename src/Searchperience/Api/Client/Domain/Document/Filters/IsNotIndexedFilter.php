<?php

namespace Searchperience\Api\Client\Domain\Document\Filters;

use Symfony\Component\Validator\Constraints as Assert;
use Searchperience\Api\Client\Domain\Filters\AbstractFilter;

/**
 * Class IsNotIndexedFilter
 *
 * @package Searchperience\Api\Client\Domain\Filters
 */
class IsNotIndexedFilter extends AbstractFilter {

	protected $isNotIndexed = true;

	/**
	 * @var string
	 */
	protected $filterString;

	/**
	 * @return boolean
	 */
	public function isNotIndexed() {
		return $this->isNotIndexed;
	}

	/**
	 * @param boolean $isNotIndexed
	 */
	public function setIsNotIndexed($isNotIndexed) {
        $this->isNotIndexed = $isNotIndexed;
	}

	/**
	 * @return string
	 */
	public function getFilterString() {
		if (isset($this->isNotIndexed)) {
			$this->filterString = sprintf("&isNotIndexed=%s", rawurlencode((int)$this->isNotIndexed));
		}

		return $this->filterString;
	}
}
