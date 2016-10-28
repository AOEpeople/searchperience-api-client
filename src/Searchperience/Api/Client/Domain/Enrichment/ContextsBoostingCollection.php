<?php

namespace Searchperience\Api\Client\Domain\Enrichment;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Nikolay Diaur <nikolay.diaur@aoe.com>
 */
class ContextsBoostingCollection extends \ArrayObject {

	/**
	 * @return int
	 */
	public function getCount() {
		return $this->count();
	}
}