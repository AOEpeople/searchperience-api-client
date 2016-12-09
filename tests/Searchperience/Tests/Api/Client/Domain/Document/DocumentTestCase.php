<?php

namespace Searchperience\Tests\Api\Client\Document;

/**
 * @author Michael Klapper <michael.klapper@aoemedia.de>
 * @date 14.11.12
 * @time 17:50
 */
class DocumentTestCase extends \Searchperience\Tests\BaseTestCase {

	/**
	 * @var \Searchperience\Api\Client\Domain\Document\Document
	 */
	protected $document;

	/**
	 * Initialize test environment
	 *
	 * @return void
	 */
	public function setUp() {
		$this->document = new \Searchperience\Api\Client\Domain\Document\Document();
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
		$dateTime = new \DateTime('2016-11-28 23:30:42', new \DateTimeZone('UTC'));
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
		$this->document->setIsMarkedForDeletion(1);
		$this->document->setNoIndex(1);
		$this->document->setCreatedAt($dateTime);
		$this->document->setUpdatedAt($dateTime);

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
		$this->assertEquals($this->document->getIsMarkedForDeletion(), 1);
		$this->assertEquals($this->document->getNoIndex(), 1);
		$this->assertEquals($this->document->getCreatedAt()->getTimestamp(), $dateTime->getTimestamp());
		$this->assertEquals($this->document->getUpdatedAt()->getTimestamp(), $dateTime->getTimestamp());
	}

	/**
	 * @test
	 */
	public function verifyDocumentStateIsDuplicateIsSet() {
		$expectedNotifications = array(
			\Searchperience\Api\Client\Domain\Document\Document::IS_DUPLICATE
		);
		$this->document->setIsDuplicateOf(12);

		$notifications = $this->document->getNotifications();

		$this->assertInternalType('array', $notifications);
		$this->assertEquals($notifications, $expectedNotifications);
	}

	/**
	 * @test
	 */
	public function verifyDocumentStateIsErrorIsSet() {
		$expectedNotifications = array(
			\Searchperience\Api\Client\Domain\Document\Document::IS_ERROR
		);
		$this->document->setErrorCount(12);

		$notifications = $this->document->getNotifications();
		$this->assertInternalType('array', $notifications);
		$this->assertEquals($notifications, $expectedNotifications);
	}

	/**
	 * @test
	 */
	public function verifyDocumentStateIsProcessingIsSet() {
		$expectedNotifications = array(
			\Searchperience\Api\Client\Domain\Document\Document::IS_PROCESSING
		);
		$this->document->setIsMarkedForProcessing(1);

		$notifications = $this->document->getNotifications();
		$this->assertInternalType('array', $notifications);
		$this->assertEquals($notifications, $expectedNotifications);
	}

	/**
	 * @test
	 */
	public function verifyDocumentStateIsMarkedForDeletionIsSet() {
		$expectedNotifications = array(
			\Searchperience\Api\Client\Domain\Document\Document::IS_DELETING
		);
		$this->document->setIsMarkedForDeletion(1);

		$notifications = $this->document->getNotifications();
		$this->assertInternalType('array', $notifications);
		$this->assertEquals($notifications, $expectedNotifications);
	}

	/**
	 * @test
	 */
	public function verifyDocumentStateIsErrorAndIsDuplicateAtTheSameTime() {
		$expectedNotifications = array(
			\Searchperience\Api\Client\Domain\Document\Document::IS_ERROR,
			\Searchperience\Api\Client\Domain\Document\Document::IS_DUPLICATE
		);
		$this->document->setIsDuplicateOf(12);
		$this->document->setErrorCount(1);

		$notifications = $this->document->getNotifications();
		$this->assertInternalType('array', $notifications);
		$this->assertEquals($notifications, $expectedNotifications);
	}

	/**
	 * @test
	 */
	public function canSetProperty() {
		$this->document->__setProperty('content','foo');
		$this->assertEquals('foo',$this->document->getContent());
	}

}
