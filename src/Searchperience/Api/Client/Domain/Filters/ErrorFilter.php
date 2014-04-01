<?php
/**
 * @Author: Timo Schmidt <timo.schmidt@aoe.com>
 * @Date: 2/24/14
 * @Time: 6:19 PM
 */

namespace Searchperience\Api\Client\Domain\Filters;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class QueryFilter
 * @package Searchperience\Api\Client\Domain\Filters
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