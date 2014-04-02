<?php

namespace Searchperience\Tests\Api\Client\Document;

/**
 * Class Urlqueue
 * @package Searchperience\Api\Client\Domain
 * @Author: Nikolay Diaur <nikolay.diaur@aoe.com>
 */
class UrlQueueItemTestCase extends \Searchperience\Tests\BaseTestCase {

	/**
	 * @var \Searchperience\Api\Client\Domain\Document\UrlQueueItem
	 */
	protected $urlQueueItem;

	/**
	 * Initialize test environment
	 *
	 * @return void
	 */
	public function setUp() {
		$this->urlQueueItem = new \Searchperience\Api\Client\Domain\UrlQueueItem\UrlQueueItem();
	}

	/**
	 * Cleanup test environment
	 *
	 * @return void
	 */
	public function tearDown() {
		$this->urlQueueItem = null;
	}

	/**
	 * @test
	 */
	public function verifyGetterAndSetter() {
		$date = \DateTime::createFromFormat("Y-m-d H:i:s", '2012-11-15 00:05:07', new \DateTimeZone("UTC"));

		$this->urlQueueItem->setUrl('http://google.com');
		$this->urlQueueItem->setProcessingStartTime($date);
		$this->urlQueueItem->setProcessingThreadId(1);
		$this->urlQueueItem->setLastError('test error');
		$this->urlQueueItem->setPriority(0);
		$this->urlQueueItem->setFailCount(2);
		$this->urlQueueItem->setDocumentId(123);
		$this->urlQueueItem->setDeleted(1);

		$this->assertEquals($this->urlQueueItem->getUrl(), 'http://google.com');
		$this->assertEquals($this->urlQueueItem->getProcessingStartTime(), $date);
		$this->assertEquals($this->urlQueueItem->getProcessingThreadId(), 1);
		$this->assertEquals($this->urlQueueItem->getLastError(), 'test error');
		$this->assertEquals($this->urlQueueItem->getPriority(), 0);
		$this->assertEquals($this->urlQueueItem->getFailCount(), 2);
		$this->assertEquals($this->urlQueueItem->getDocumentId(), 123);
		$this->assertEquals($this->urlQueueItem->getDeleted(), 1);
	}

	/**
	 * @test
	 */
	public function verifyUrlQueueItemStateIsErrorAndWaitingIsSet() {
		$expectedNotifications = array(
			\Searchperience\Api\Client\Domain\UrlQueueItem\UrlQueueItem::IS_ERROR,
			\Searchperience\Api\Client\Domain\UrlQueueItem\UrlQueueItem::IS_WAITING
		);
		$this->urlQueueItem->setLastError("Exception foobar");

		$notifications = $this->urlQueueItem->getNotifications();

		$this->assertInternalType('array', $notifications);
		$this->assertEquals($notifications, $expectedNotifications);
	}


	/**
	 * @test
	 */
	public function verifyUrlQueueItemStateIsErrorIsSet() {
		$expectedNotifications = array(
			\Searchperience\Api\Client\Domain\UrlQueueItem\UrlQueueItem::IS_ERROR,
			\Searchperience\Api\Client\Domain\UrlQueueItem\UrlQueueItem::IS_DOCUMENT_DELETED
		);

		$this->urlQueueItem->setLastError("Exception foobar");
		$this->urlQueueItem->setDeleted(1);
		$notifications = $this->urlQueueItem->getNotifications();

		$this->assertInternalType('array', $notifications);
		$this->assertEquals($notifications, $expectedNotifications);
	}

	/**
	 * @test
	 */
	public function verifyUrlQueueItemStateIsProcessingIsSet() {
		$expectedNotifications = array(
				\Searchperience\Api\Client\Domain\UrlQueueItem\UrlQueueItem::IS_PROCESSING
		);
		$this->urlQueueItem->setProcessingThreadId(1);

		$notifications = $this->urlQueueItem->getNotifications();

		$this->assertInternalType('array', $notifications);
		$this->assertEquals($notifications, $expectedNotifications);
	}

	/**
	 * @test
	 */
	public function verifyUrlQueueItemStateIsWaitingIsSet() {
		$expectedNotifications = array(
				\Searchperience\Api\Client\Domain\UrlQueueItem\UrlQueueItem::IS_DOCUMENT_DELETED
		);
		$this->urlQueueItem->setProcessingThreadId(0);
		$this->urlQueueItem->setDeleted(1);

		$notifications = $this->urlQueueItem->getNotifications();

		$this->assertInternalType('array', $notifications);
		$this->assertEquals($notifications, $expectedNotifications);

	}
}
