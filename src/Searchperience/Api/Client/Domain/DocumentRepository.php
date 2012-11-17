<?php

namespace Searchperience\Api\Client\Domain;

/**
 * @author Michael Klapper <michael.klapper@aoemedia.de>
 * @date 14.11.12
 * @time 15:13
 */
class DocumentRepository {

	/**
	 * @var \Searchperience\Api\Client\System\Storage\DocumentBackendInterface
	 */
	protected $storageBackend;

	/**
	 * Injects the storage backend.
	 *
	 * @param \Searchperience\Api\Client\System\Storage\DocumentBackendInterface $storageBackend
	 * @return void
	 */
	public function injectStorageBackend(\Searchperience\Api\Client\System\Storage\DocumentBackendInterface $storageBackend) {
		$this->storageBackend = $storageBackend;
	}

	/**
	 * Add a new Document to the index
	 *
	 * @param \Searchperience\Api\Client\Domain\Document $document
	 * @return boolean
	 */
	public function add(\Searchperience\Api\Client\Domain\Document $document) {
		$status = $this->storageBackend->put('document', $document);

	}

	/**
	 * Get a Document by foreignId
	 *
	 * @param $foreignId
	 * @thorwsException DocumentNotFoundException
	 * @return \Searchperience\Api\Client\Domain\Document $document
	 */
	public function get($foreignId) {
		$document = $this->storageBackend->get($foreignId);

		return $document;
	}
}
