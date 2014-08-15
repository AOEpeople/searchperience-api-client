<?php
/**
 * @Author: Nikolay Diaur <nikolay.diaur@aoe.com>
 * @Date: 8/15/14
 * @Time: 3:00 PM
 */

namespace Searchperience\Api\Client\Domain\ActivityLogs;


use Searchperience\Api\Client\Domain\AbstractEntityCollection;

/**
 * Class ActivityLogsCollection
 * @package Searchperience\Api\Client\Domain\ActivityLogs
 */
class ActivityLogsCollection extends AbstractEntityCollection{
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