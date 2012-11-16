<?php

namespace Searchperience\Api\Client\Domain;

/**
 * @author Michael Klapper <michael.klapper@aoemedia.de>
 * @date 14.11.12
 * @time 15:13
 */
class DocumentRepository {

	/**
	 * @var StorageDocumentBackendInterface
	 */
	protected $storageBackend;

	/**
	 * Injects the storage backend.
	 *
	 * @param \Searchperience\Api\Client\System\Storage\StorageDocumentBackendInterface $storageBackend
	 * @return void
	 */
	public function injectStorageBackend(\Searchperience\Api\Client\System\Storage\StorageDocumentBackendInterface $storageBackend) {
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
