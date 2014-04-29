<?php

namespace Searchperience\Api\Client\Domain\Filters;

use Symfony\Component\Validator\Constraints as Assert;
use Searchperience\Api\Client\Domain\Filters\AbstractFilter;

/**
 * Class QueryFilter
 * @package Searchperience\Api\Client\Domain\Filters
 * @author: Timo Schmidt <timo.schmidt@aoe.com>
 */
abstract class AbstractIsDeletedFilter extends AbstractFilter {

	protected $deleted = true;

	/**
	 * @var string
	 */
	protected $filterString;

	/**
	 * @param boolean $deleted
	 */
	public function setDeleted($deleted) {
		$this->deleted = $deleted;
	}

	/**
	 * @return boolean
	 */
	public function getDeleted() {
		return $this->deleted;
	}

	/**
	 * @return string
	 */
	public function getFilterString() {
		if (isset($this->deleted)) {
			$this->filterString = sprintf("&isDeleted=%s", rawurlencode((int)$this->deleted));
		}

		return $this->filterString;
	}
}