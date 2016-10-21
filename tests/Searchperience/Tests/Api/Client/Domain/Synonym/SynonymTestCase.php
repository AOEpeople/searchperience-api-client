<?php

namespace Searchperience\Tests\Api\Client\Document;
use Searchperience\Api\Client\Domain\Document\Promotion;
use Searchperience\Api\Client\Domain\Synonym\Synonym;

/**
 * @author Timo Schmidt <timo.schmidt@aoe.com>
 */
class SynonymTestCase extends \Searchperience\Tests\BaseTestCase {

	/**
	 * @var \Searchperience\Api\Client\Domain\Synonym\Synonym
	 */
	protected $synonym;

	/**
	 * Initialize test environment
	 *
	 * @return void
	 */
	public function setUp() {
		$this->synonym = new \Searchperience\Api\Client\Domain\Synonym\Synonym();
	}

	/**
	 * @test
	 */
	public function canSetMainWord() {
		$this->synonym->setSynonyms("foo");
		$this->assertSame("foo",$this->synonym->getSynonyms());
	}

	/**
	 * @test
	 */
	public function canAddWordsWithSameMeaning() {
		$this->assertSame(0, count($this->synonym->getMappedWords()),'Wrong initial state of words with same meaning of the synonym');
		$this->synonym->addMappedWord("foo");
		$this->synonym->addMappedWord("bar");
		$this->assertSame(2, count($this->synonym->getMappedWords()),'Could not add words with same meaning to the synonyms');
	}

	/**
	 * @test
	 */
	public function canRemoveWordWithSameMeaning() {
		$this->assertSame(0, count($this->synonym->getMappedWords()),'Wrong initial state of words with same meaning of the synonym');
		$this->synonym->addMappedWord("foo");
		$this->synonym->addMappedWord("bar");
		$this->assertSame(2, count($this->synonym->getMappedWords()),'Could not add words with same meaning to the synonyms');
		$this->synonym->removeMappedWord("foo");
		$this->assertSame(1, count($this->synonym->getMappedWords()),'Could not remove words with same meaning to the synonyms');
	}

	/**
	 * @test
	 */
	public function canSetType() {
		$this->synonym->setType(Synonym::TYPE_GROUPING);
		$this->assertEquals(Synonym::TYPE_GROUPING, $this->synonym->getType());
	}

	/**
	 * @test
	 * @expectedException \Searchperience\Common\Exception\InvalidArgumentException
	 */
	public function canNotSetInvalidMatchingType() {
		$this->synonym->setType('Nothing');
	}

}