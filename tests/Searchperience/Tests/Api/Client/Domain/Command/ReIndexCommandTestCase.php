<?php

namespace Searchperience\Tests\Api\Client\Document;
use Searchperience\Api\Client\Domain\Command\ReIndexCommand;

/**
 * Class ReIndexCommandTestCase
 * @package Searchperience\Tests\Api\Client\Document
 */
class ReIndexCommandTestCase extends \Searchperience\Tests\BaseTestCase {

	/**
	 * @var \Searchperience\Api\Client\Domain\Command\ReIndexCommand
	 */
	protected $reIndexCommand;

	/**
	 * @return void
	 */
	public function setUp() {
		$this->reIndexCommand = new ReIndexCommand();
	}

	/**
	 * @test
	 */
	public function canGetArguments() {
		$this->reIndexCommand->addDocumentId(222);
		$this->reIndexCommand->addDocumentId(223);
		$this->assertEquals(array('documentIds' => array(222,223)), $this->reIndexCommand->getArguments());
	}

}