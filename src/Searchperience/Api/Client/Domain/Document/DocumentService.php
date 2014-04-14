<?php

namespace Searchperience\Api\Client\Domain\Document;

use Searchperience\Api\Client\Domain\Document\Filters\FiltersCollection;
use \Searchperience\Api\Client\Domain\Document\Document;
use Symfony\Component\Validator\Validation;

/**
 * @author Timo Schmidt <timo.schmidt@aoe.com>
 */
class DocumentService {

	/**
	 * @var DocumentRepository
	 */
	protected $documentRepository;

	/**
	 * @param DocumentRepository $documentRepository
	 */
	public function injectDocumentRepository(\Searchperience\Api\Client\Domain\Document\DocumentRepository $documentRepository) {
		$this->documentRepository = $documentRepository;
	}

	/**
	 * @param integer $id
	 * @param int $priority
	 * @return bool
	 */
	public function markDocumentForReIndexingById($id, $priority = Document::INDEX_PRIORITY_HIGH) {
		$document = $this->documentRepository->getById($id);
		return $this->markDocumentForReIndexing($document,$priority);
	}

	/**
	 * @param string $foreignId
	 * @param int $priority
	 * @return bool
	 */
	public function markDocumentForReIndexingByForeignId($foreignId, $priority = Document::INDEX_PRIORITY_HIGH) {
		$document = $this->documentRepository->getByForeignId($foreignId);
		return $this->markDocumentForReIndexing($document,$priority);
	}

	/**
	 * @param string $url
	 * @param int $priority
	 * @return bool
	 */
	public function  markDocumentForReIndexingByUrl($url, $priority = Document::INDEX_PRIORITY_HIGH) {
		$document = $this->documentRepository->getByUrl($url);
		return $this->markDocumentForReIndexing($document,$priority);
	}

	/**
	 * @param Document $document
	 * @param int $priority
	 * @return bool
	 */
	public function markDocumentForReIndexing(\Searchperience\Api\Client\Domain\Document\Document $document, $priority = Document::INDEX_PRIORITY_HIGH) {
		$document->setIsMarkedForProcessing(1);
		$document->setTemporaryPriority($priority);
		$result =  $this->documentRepository->add($document);

		return $result == 200 || $result == 201;
	}

	/**
	 * @param integer $id
	 * @param int $priority
	 * @return bool
	 */
	public function markDocumentForNoIndexById($id, $priority = Document::INDEX_PRIORITY_HIGH) {
		$document = $this->documentRepository->getById($id);
		return $this->markDocumentForNoIndex($document, $priority);
	}

	/**
	 * @param string $foreignId
	 * @param int $priority
	 * @return bool
	 */
	public function markDocumentForNoIndexByForeignId($foreignId, $priority = Document::INDEX_PRIORITY_HIGH) {
		$document = $this->documentRepository->getByForeignId($foreignId);
		return $this->markDocumentForNoIndex($document, $priority);
	}

	/**
	 * @param string $url
	 * @param int $priority
	 * @return bool
	 */
	public function markDocumentForNoIndexByUrl($url, $priority = Document::INDEX_PRIORITY_HIGH) {
		$document = $this->documentRepository->getByForeignId($url);
		return $this->markDocumentForNoIndex($document, $priority);
	}

	/**
	 * @param Document $document
	 * @param int $priority
	 * @return bool
	 */
	public function markDocumentForNoIndex(\Searchperience\Api\Client\Domain\Document\Document $document, $priority = Document::INDEX_PRIORITY_HIGH) {
		$document->setNoIndex(1);
		$document->setIsMarkedForProcessing(1);
		$document->setTemporaryPriority($priority);

		$result = $this->documentRepository->add($document);
		return $result == 200 || $result == 201;
	}
}