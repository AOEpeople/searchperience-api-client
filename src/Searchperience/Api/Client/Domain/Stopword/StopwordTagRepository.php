<?php

namespace Searchperience\Api\Client\Domain\Stopword;

use Searchperience\Api\Client\Domain\AbstractRepository;

/**
 * Class StopwordTagRepository
 * @package Searchperience\Api\Client\Domain\Stopword
 */
class StopwordTagRepository extends AbstractRepository {
	/**
	 * @return StopwordTagCollection
	 */
	public function getAll() {
		return $this->storageBackend->getAll();
	}
}