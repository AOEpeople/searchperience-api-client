<?php

namespace Searchperience\Tests\Api\Client\Document;
use Searchperience\Api\Client\Domain\Command\AddToUrlQueueCommand;

/**
 * Class RemoveFromCrawlerQueueTestCase
 * @package Searchperience\Tests\Api\Client\Document
 */
class RemoveFromCrawlerQueueTestCase extends \Searchperience\Tests\BaseTestCase {

	/**
	 * @var \Searchperience\Api\Client\Domain\Command\RemoveFromCrawlerQueueCommand
	 */
	protected $removeFromCrawlerQueueCommand;

	/**
	 * @return void
	 */
	public function setUp() {
		$this->removeFromCrawlerQueueCommand = new AddToUrlQueueCommand();
	}

	/**
	 * @test
	 */
	public function canGetArguments() {
		$this->removeFromCrawlerQueueCommand->addDocumentId(100);
		$this->removeFromCrawlerQueueCommand->addDocumentId(101);
		$this->assertEquals(array('documentIds' => array(100,101)), $this->removeFromCrawlerQueueCommand->getArguments());
	}
}