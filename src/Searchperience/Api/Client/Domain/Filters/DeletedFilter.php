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
class DeletedFilter extends AbstractFilter {

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
			$this->filterString = sprintf("&deleted=%s", rawurlencode((int)$this->deleted));
		}

		return $this->filterString;
	}
}