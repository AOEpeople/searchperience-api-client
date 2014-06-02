<?php

namespace Searchperience\Tests\Api\Client\Document;
use Searchperience\Api\Client\Domain\Command\ReCrawlCommand;

/**
 * Class ReCrawlCommandTestCase
 * @package Searchperience\Tests\Api\Client\Document
 */
class ReCrawlCommandTestCase extends \Searchperience\Tests\BaseTestCase {

	/**
	 * @var \Searchperience\Api\Client\Domain\Command\ReCrawlCommand
	 */
	protected $reCrawlCommand;

	/**
	 * @return void
	 */
	public function setUp() {
		$this->reCrawlCommand = new ReCrawlCommand();
	}

	/**
	 * @test
	 */
	public function canGetArguments() {
		$this->reCrawlCommand->addDocumentId(100);
		$this->reCrawlCommand->addDocumentId(101);
		$this->assertEquals(array('documentIds' => array(100,101)), $this->reCrawlCommand->getArguments());
	}

}