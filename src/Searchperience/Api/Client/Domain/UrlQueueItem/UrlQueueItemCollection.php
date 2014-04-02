<?php

namespace Searchperience\Api\Client\Domain\UrlQueueItem;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class UrlQueueItemCollection
 * @package Searchperience\Api\Client\Domain
 * @Author: Nikolay Diaur <nikolay.diaur@aoe.com>
 */
class UrlQueueItemCollection extends \ArrayObject {
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
} 