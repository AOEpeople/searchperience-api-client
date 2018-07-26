<?php

namespace Searchperience\Tests\Api\Client\Document;

/**
 * Class StopwordTestCase
 * @package Searchperience\Tests\Api\Client\Document
 */
class StopwordTestCase extends \Searchperience\Tests\BaseTestCase {

	/**
	 * @var \Searchperience\Api\Client\Domain\Stopword\Stopword
	 */
	protected $stopword;

	/**
	 * Initialize test environment
	 *
	 * @return void
	 */
	public function setUp() {
		$this->stopword = new \Searchperience\Api\Client\Domain\Stopword\Stopword();
	}

	/**
	 * @test
	 */
	public function canSetMainWord() {
		$this->stopword->setWord("foo");
		$this->assertSame("foo",$this->stopword->getWord());
	}

	/**
	 * @test
	 */
	public function canSetTagName() {
		$this->stopword->setLanguage("en");
		$this->assertSame("en", $this->stopword->getLanguage());
	}
}