<?php

namespace Searchperience\Api\Client\Domain\AdminSearch;

use Searchperience\Api\Client\Domain\AbstractRepository;

/**
 * Repository to retrieve available AdminSearch objects from server.
 *
 * Class AdminSearchRepository
 *
 * @package Searchperience\Api\Client\Domain\AdminSearch
 * @author Michael Klapper <michael.klaper@aoe.com>
 */
class AdminSearchRepository extends AbstractRepository {

	/**
	 * Retrieves all available AdminSearch objects registered for current connection.
	 *
	 * @return AdminSearchCollection
	 */
	public function getAll() {
		$adminSearchCollection = $this->storageBackend->getAll();
		return $this->decorateAll($adminSearchCollection);
	}
}