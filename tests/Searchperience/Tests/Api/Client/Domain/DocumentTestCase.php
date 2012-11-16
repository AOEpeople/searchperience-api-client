<?php

namespace Searchperience\Tests\Api\Client;

/**
 * @author Michael Klapper <michael.klapper@aoemedia.de>
 * @date 14.11.12
 * @time 17:50
 */
class DocumentTestCase extends \Searchperience\Tests\BaseTestCase {

	/**
	 * @var \Searchperience\Api\Client\Domain\Document
	 */
	protected $document;

	/**
	 * Initialize test environment
	 *
	 * @return void
	 */
	public function setUp() {

	}

	/**
	 * Cleanup test environment
	 *
	 * @return void
	 */
	public function tearDown() {
		$this->document = null;
	}

	/**
	 * @test
	 */
	public function verifyGetterAndSetter() {
		$this->document = new \Searchperience\Api\Client\Domain\Document();

		$this->assertEquals($this->document->getMimeType(), 'text/xml', 'Default mimeType is not set.');
		$this->assertNull($this->document->getForeignId(), 'Default foreignId is not NULL.');
		$this->assertNull($this->document->getSource(), 'Default source is not NULL.');
		$this->assertNull($this->document->getUrl(), 'Default url is not NULL.');
		$this->assertEquals($this->document->getBoostFactor(), 1, 'Default boost factor is not integer 1.');
		$this->assertEquals($this->document->getLastProcessing(), '', 'Default last processing is not empty string.');
		$this->assertEquals($this->document->getIsProminent(), 0, 'Default is prominent is not integer 0.');
		$this->assertEquals($this->document->getIsMarkedForProcessing(), 1, 'Default is marked for processing is not integer 1.');
		$this->assertEquals($this->document->getNoIndex(), 0, 'Default no index is not integer 0.');

		$this->document->setId(12);
		$this->document->setForeignId(312);
		$this->document->setMimeType('application/json');
		$this->document->setContent('<document></document>');
		$this->document->setGeneralPriority(0);
		$this->document->setTemporaryPriority(2);
		$this->document->setSource('someSourceString');
		$this->document->setUrl('https://api.searchperience.com/endpoint');
		$this->document->setBoostFactor(2);
		$this->document->setIsProminent(1);
		$this->document->setIsMarkedForProcessing(1);
		$this->document->setNoIndex(1);

		$this->assertEquals($this->document->getId(), 12);
		$this->assertEquals($this->document->getForeignId(), 312);
		$this->assertEquals($this->document->getMimeType(), 'application/json');
		$this->assertEquals($this->document->getContent(), '<document></document>');
		$this->assertEquals($this->document->getGeneralPriority(), 0);
		$this->assertEquals($this->document->getTemporaryPriority(), 2);
		$this->assertEquals($this->document->getSource(), 'someSourceString');
		$this->assertEquals($this->document->getUrl(), 'https://api.searchperience.com/endpoint');
		$this->assertEquals($this->document->getBoostFactor(), 2);
		$this->assertEquals($this->document->getIsProminent(), 1);
		$this->assertEquals($this->document->getIsMarkedForProcessing(), 1);
		$this->assertEquals($this->document->getNoIndex(), 1);
	}
}
