<?php

namespace Searchperience\Api\Client\Domain;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Timo Schmidt <timo.schmidt@aoemedia.de>
 */
class DocumentCollection extends \ArrayObject {
	/**
	 * How many total matching documents are found.
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