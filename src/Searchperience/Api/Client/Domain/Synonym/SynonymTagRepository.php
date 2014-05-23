<?php

namespace Searchperience\Api\Client\Domain\Synonym;

/**
 * @author Timo Schmidt <timo.schmidt@aoe.com>
 */
class SynonymTagRepository {

	/**
	 * @var \Searchperience\Api\Client\System\Storage\SynonymTagBackendInterface
	 */
	protected $storageBackend;

	/**
	 * @var \Symfony\Component\Validator\ValidatorInterface
	 */
	protected $synonymValidator;

	/**
	 * Injects the storage backend.
	 *
	 * @param \Searchperience\Api\Client\System\Storage\SynonymTagBackendInterface $storageBackend
	 * @return void
	 */
	public function injectStorageBackend(\Searchperience\Api\Client\System\Storage\SynonymTagBackendInterface $storageBackend) {
		$this->storageBackend = $storageBackend;
	}

	/**
	 * @return SynonymTagCollection
	 */
	public function getAll() {
		return $this->storageBackend->getAll();
	}
}