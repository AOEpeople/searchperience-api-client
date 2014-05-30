<?php

namespace Searchperience\Api\Client\Domain\Stopword;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class StopwordTagCollection
 * @package Searchperience\Api\Client\Domain\Stopword
 */
class StopwordTagCollection extends \ArrayObject {

	/**
	 * @var integer
	 */
	protected $totalCount;

	/**
	 * @param int $totalCount
	 */
	public function setTotalCount($totalCount) {
		$this->totalCount = $totalCount;
	}

	/**
	 * @return int
	 */
	public function getTotalCount() {
		return $this->totalCount;
	}

	/**
	 * @return int
	 */
	public function getCount() {
		return $this->count();
	}
}