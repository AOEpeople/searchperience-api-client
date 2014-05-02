<?php

namespace Searchperience\Api\Client\Domain\Document;

use Symfony\Component\Validator\Validation;
use Searchperience\Api\Client\System\Storage\AbstractRestBackend;
use Searchperience\Common\Exception\InvalidArgumentException;

/**
 * Class DocumentStatusRepository
 *
 * @package Searchperience\Api\Client\Domain
 * @author: Timo Schmidt <timo.schmidt@aoe.com>
 */
class DocumentStatusRepository {
	/**
	 * @var \Searchperience\Api\Client\System\Storage\DocumentStatusBackendInterface
	 */
	protected $storageBackend;

	/**
	 * Injects the storage backend.
	 *
	 * @param \Searchperience\Api\Client\System\Storage\DocumentStatusBackendInterface $storageBackend
	 * @return void
	 */
	public function injectStorageBackend(\Searchperience\Api\Client\System\Storage\DocumentStatusBackendInterface $storageBackend) {
		$this->storageBackend = $storageBackend;
	}

	/**
	 * Retrieves the status of the document repository.
	 *
	 * @throws \Searchperience\Common\Exception\InvalidArgumentException
	 * @return DocumentStatus
	 */
	public function get() {
		$documentStatus = $this->decorateDocumentStatus($this->storageBackend->get());
		return $documentStatus;
	}

	/**
	 * Overwrite this method to decorate the DocumentStatus response.
	 *
	 * @param $documentStatus
	 * @return mixed
	 */
	protected function decorateDocumentStatus($documentStatus) {
		return $documentStatus;
	}
}