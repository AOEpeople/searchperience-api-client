<?php

namespace Searchperience\Api\Client\Domain\Document\Filters;

use Symfony\Component\Validator\Constraints as Assert;
use Searchperience\Api\Client\Domain\Filters\AbstractFilter;

/**
 * Class IsWaitingFilter
 *
 * @package Searchperience\Api\Client\Domain\Filters
 * @author: Timo Schmidt <timo.schmidt@aoe.com>
 */
class IsWaitingFilter extends AbstractFilter {

	protected $isWaiting = true;

	/**
	 * @var string
	 */
	protected $filterString;

	/**
	 * @return boolean
	 */
	public function isIsWaiting() {
		return $this->isWaiting;
	}

	/**
	 * @param boolean $isWaiting
	 */
	public function setIsWaiting($isWaiting) {
		$this->isWaiting = $isWaiting;
	}

	/**
	 * @return string
	 */
	public function getFilterString() {
		if (isset($this->isWaiting)) {
			$this->filterString = sprintf("&isWaiting=%s", rawurlencode((int)$this->isWaiting));
		}

		return $this->filterString;
	}
}