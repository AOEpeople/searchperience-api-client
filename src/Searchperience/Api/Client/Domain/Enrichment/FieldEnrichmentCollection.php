<?php

namespace Searchperience\Api\Client\Domain\Enrichment;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Timo Schmidt <timo.schmidt@aoe.com>
 */
class FieldEnrichmentCollection extends \ArrayObject {

	/**
	 * @return int
	 */
	public function getCount() {
		return $this->count();
	}
}