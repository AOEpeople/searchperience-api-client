<?php

namespace Searchperience\Tests\Api\Client\Document;
use Searchperience\Api\Client\Domain\Command\AddToUrlQueueCommand;

/**
 * Class AddToCrawlerQueueTestCase
 * @package Searchperience\Tests\Api\Client\Document
 */
class AddToCrawlerQueueTestCase extends \Searchperience\Tests\BaseTestCase {

	/**
	 * @var \Searchperience\Api\Client\Domain\Command\AddToUrlQueueCommand
	 */
	protected $addToUrlQueueCommand;

	/**
	 * @return void
	 */
	public function setUp() {
		$this->addToUrlQueueCommand = new AddToUrlQueueCommand();
	}

	/**
	 * @test
	 */
	public function canGetArguments() {
		$this->addToUrlQueueCommand->addDocumentId(100);
		$this->addToUrlQueueCommand->addDocumentId(101);
		$this->assertEquals(array('documentIds' => array(100,101)), $this->addToUrlQueueCommand->getArguments());
	}

}