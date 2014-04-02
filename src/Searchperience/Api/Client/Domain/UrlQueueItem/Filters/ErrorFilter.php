<?php

namespace Searchperience\Api\Client\Domain\UrlQueueItem\Filters;

use Symfony\Component\Validator\Constraints as Assert;
use Searchperience\Api\Client\Domain\Filters\AbstractFilter;

/**
 * Class QueryFilter
 * @package Searchperience\Api\Client\Domain\Filters
 * @author: Timo Schmidt <timo.schmidt@aoe.com>
 */
class ErrorFilter extends AbstractFilter {

	protected $error = true;

	/**
	 * @var string
	 */
	protected $filterString;

	/**
	 * @param boolean $error
	 */
	public function setError($error) {
		$this->error = $error;
	}

	/**
	 * @return boolean
	 */
	public function getError() {
		return $this->error;
	}


	/**
	 * @return string
	 */
	public function getFilterString() {
		if (isset($this->error)) {
			$this->filterString = sprintf("&error=%s", rawurlencode((int)$this->error));
		}

		return $this->filterString;
	}
}