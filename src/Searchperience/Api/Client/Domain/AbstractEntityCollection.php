<?php
/**
 * Created by IntelliJ IDEA.
 * User: pavelbogomolenko
 * Date: 07/07/14
 * Time: 15:24
 * To change this template use File | Settings | File Templates.
 */

namespace Searchperience\Api\Client\Domain;


/**
 * Class AbstractEntityCollection
 * @package Searchperience\Api\Client\Domain\UrlQueueItem
 */
class AbstractEntityCollection extends \ArrayObject {
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