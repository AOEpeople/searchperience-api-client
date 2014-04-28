<?php

namespace Searchperience\Tests\Api\Client\Document;
use Searchperience\Api\Client\Domain\UrlQueueItem\UrlQueueStatus;

/**
 * Class UrlQueueStatus
 *
 * @package Searchperience\Api\Client\Domain
 * @author Timo Schmidt <timo.schmidt@aoe.com>
 */
class UrlQueueStatusTestCase extends \Searchperience\Tests\BaseTestCase {


	/**
	 * @var UrlQueueStatus
	 */
	protected $urlQueueStatus;

	/**
	 *
	 */
	public function setUp() {
		$this->urlQueueStatus = new UrlQueueStatus();
	}

	/**
	 * @test
	 */
	public function setGet() {
		$this->urlQueueStatus->setAllCount(5);
		$this->urlQueueStatus->setDeletedCount(2);
		$this->urlQueueStatus->setProcessingCount(4);
		$this->urlQueueStatus->setWaitingCount(3);
		$this->urlQueueStatus->setErrorCount(1);

		$this->assertEquals(5, $this->urlQueueStatus->getAllCount());
		$this->assertEquals(2, $this->urlQueueStatus->getDeletedCount());
		$this->assertEquals(4, $this->urlQueueStatus->getProcessingCount());
		$this->assertEquals(3, $this->urlQueueStatus->getWaitingCount());
		$this->assertEquals(1, $this->urlQueueStatus->getErrorCount());
	}
}

