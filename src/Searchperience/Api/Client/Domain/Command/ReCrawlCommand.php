<?php

namespace Searchperience\Api\Client\Domain\Command;

use Searchperience\Api\Client\Domain\Document\Document;

/**
 * Class ReCrawlCommand
 * @package Searchperience\Api\Client\Domain\Command
 * @author Timo Schmidt <timo.schmidt@aoe.com>
 */
class ReCrawlCommand extends AbstractCommand {

	/**
	 * @var string
	 */
	protected $name = 'ReCrawl';

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