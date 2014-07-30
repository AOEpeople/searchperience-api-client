<?php

namespace Searchperience\Api\Client\Domain\CommandLog;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class CommandLogCollection
 * @package Searchperience\Api\Client\Domain
 * @author: Nikolay Diaur <nikolay.diaur@aoe.com>
 */
class CommandLogCollection extends \ArrayObject {
	/**
	 * How many total matching items are found.
	 * This can be more that in the current collection because of offset and limit in the REST-API request.
	 *
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