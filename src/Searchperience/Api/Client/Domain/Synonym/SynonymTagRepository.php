<?php

namespace Searchperience\Api\Client\Domain\Synonym;

use Searchperience\Api\Client\Domain\AbstractRepository;

/**
 * Class SynonymTagRepository
 * @package Searchperience\Api\Client\Domain\Synonym
 * @author Timo Schmidt <timo.schmidt@aoe.com>
 */
class SynonymTagRepository extends AbstractRepository {
	/**
	 * @return SynonymTagCollection
	 */
	public function getAll() {
		return $this->storageBackend->getAll();
	}
}