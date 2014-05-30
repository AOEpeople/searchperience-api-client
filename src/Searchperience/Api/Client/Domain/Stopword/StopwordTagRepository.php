<?php

namespace Searchperience\Api\Client\Domain\Stopword;

/**
 * Class StopwordTagRepository
 * @package Searchperience\Api\Client\Domain\Stopword
 */
class StopwordTagRepository {

	/**
	 * @var \Searchperience\Api\Client\System\Storage\StopwordTagBackendInterface
	 */
	protected $storageBackend;

	/**
	 * Injects the storage backend.
	 *
	 * @param \Searchperience\Api\Client\System\Storage\StopwordTagBackendInterface $storageBackend
	 * @return void
	 */
	public function injectStorageBackend(\Searchperience\Api\Client\System\Storage\StopwordTagBackendInterface $storageBackend) {
		$this->storageBackend = $storageBackend;
	}

	/**
	 * @return StopwordTagCollection
	 */
	public function getAll() {
		return $this->storageBackend->getAll();
	}
}