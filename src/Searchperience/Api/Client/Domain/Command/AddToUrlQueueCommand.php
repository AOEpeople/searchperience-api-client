<?php

namespace Searchperience\Api\Client\Domain\Command;

use Searchperience\Api\Client\Domain\Document\Document;

/**
 * Class AddToUrlQueueCommand
 * @package Searchperience\Api\Client\Domain\Command
 * @author Timo Schmidt <timo.schmidt@aoe.com>
 */
class AddToUrlQueueCommand extends AbstractCommand {

	/**
	 * @var string
	 */
	protected $name = 'AddToCrawlerQueue';

	/**
	 * @param integer $documentId
	 */
	public function addDocumentId($documentId) {
		$this->arguments['documentIds'][] = $documentId;
	}

	/**
	 * @param Document $document
	 */
	public function addDocument(Document $document) {
		return $this->addDocumentId($document->getId());
	}
}