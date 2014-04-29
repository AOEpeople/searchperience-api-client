<?php

namespace Searchperience\Api\Client\Domain\Document\Filters;

use Symfony\Component\Validator\Constraints as Assert;
use Searchperience\Api\Client\Domain\Filters\AbstractFilter;

/**
 * Class IsRedirectFilter
 * @package Searchperience\Api\Client\Domain\Filters
 * @author: Timo Schmidt <timo.schmidt@aoe.com>
 */
class IsRedirectFilter extends AbstractFilter {

	protected $isRedirect = true;

	/**
	 * @var string
	 */
	protected $filterString;

	/**
	 * @return boolean
	 */
	public function isIsRedirect() {
		return $this->isRedirect;
	}

	/**
	 * @param boolean $isRedirect
	 */
	public function setIsRedirect($isRedirect) {
		$this->isRedirect = $isRedirect;
	}

	/**
	 * @return string
	 */
	public function getFilterString() {
		if (isset($this->isRedirect)) {
			$this->filterString = sprintf("&isRedirect=%s", rawurlencode((int)$this->isRedirect));
		}

		return $this->filterString;
	}
}