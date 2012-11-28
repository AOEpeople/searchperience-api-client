<?php

namespace Searchperience\Api\Client\Domain;

use Symfony\Component\Validator\Validation;

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
	 * @var \Symfony\Component\Validator\ValidatorInterface
	 */
	protected $documentValidator;

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
	 * Injects the validation service
	 *
	 * @param \Symfony\Component\Validator\ValidatorInterface $documentValidator
	 * @return void
	 */
	public function injectValidator(\Symfony\Component\Validator\ValidatorInterface $documentValidator) {
		$this->documentValidator = $documentValidator;
	}

	/**
	 * Add a new Document to the index
	 *
	 * @param \Searchperience\Api\Client\Domain\Document $document
	 * @throws \Searchperience\Common\Exception\InvalidArgumentException
	 * @return integer HTTP Status code
	 */
	public function add(\Searchperience\Api\Client\Domain\Document $document) {
		$violations = $this->documentValidator->validate($document);

		if ($violations->count() > 0) {
			throw new \Searchperience\Common\Exception\InvalidArgumentException('Given object of type "' . get_class($document) . '" is not valid: ' . PHP_EOL . $violations);
		}

		$status = $this->storageBackend->post($document);
		return $status;
	}

	/**
	 * Get a Document by foreignId
	 *
	 * The foreignId can be a string of:
	 * 0-9a-zA-Z_-.:
	 * Is valid if it is an alphanumeric string, which is defined as [[:alnum:]]
	 * 
	 * @param string $foreignId
	 *
	 * @throws \Searchperience\Common\Exception\InvalidArgumentException
	 * @thorws \Searchperience\Common\Exception\DocumentNotFoundException
	 * @return \Searchperience\Api\Client\Domain\Document $document
	 */
	public function getByForeignId($foreignId) {
		if (!is_string($foreignId) && !is_integer($foreignId) || preg_match('/^[[:alnum:]]*$/u', $foreignId) !== 1) {
			throw new \Searchperience\Common\Exception\InvalidArgumentException('Method "' . __METHOD__ . '" accepts only strings values as $foreignId. Input was: ' . serialize($foreignId));
		}

		$document = $this->storageBackend->getByForeignId($foreignId);
		return $document;
	}

	/**
	 * Get a Document by foreignId
	 *
	 * The foreignId can be a string of:
	 * 0-9a-zA-Z_-.:
	 * Is valid if it is an alphanumeric string, which is defined as [[:alnum:]]
	 *
	 *  @param string $foreignId
	 *
	 * @throws \Searchperience\Common\Exception\InvalidArgumentException
	 * @thorws \Searchperience\Common\Exception\DocumentNotFoundException
	 * @return \Searchperience\Api\Client\Domain\Document $document
	 */
	public function deleteByForeignId($foreignId) {
		if (!is_string($foreignId) && !is_integer($foreignId) || preg_match('/^[[:alnum:]]*$/u', $foreignId) !== 1) {
			throw new \Searchperience\Common\Exception\InvalidArgumentException('Method "' . __METHOD__ . '" accepts only strings values as $foreignId. Input was: ' . serialize($foreignId));
		}

		$document = $this->storageBackend->deleteByForeignId($foreignId);
		return $document;
	}
}
