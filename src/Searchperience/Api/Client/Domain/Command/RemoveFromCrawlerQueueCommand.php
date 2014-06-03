<?php

namespace Searchperience\Api\Client\Domain\Command;

use Searchperience\Api\Client\Domain\Document\Document;

/**
 * Class RemoveFromCrawlerQueueCommand
 * @package Searchperience\Api\Client\Domain\Command
 * @author Timo Schmidt <timo.schmidt@aoe.com>
 */
class RemoveFromCrawlerQueueCommand extends AbstractCommand {

	/**
	 * @var string
	 */
	protected $name = 'RemoveFromCrawlerQueue';

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