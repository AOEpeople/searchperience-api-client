<?php

namespace Searchperience\Tests\Api\Client\Document;

use Searchperience\Api\Client\System\Storage;
use Searchperience\Api\Client\Domain\UrlQueueItem\UrlQueueItemRepository;

/**
 * @author Timo Schmidt <timo.schmidt@aoe.com>
 */
class UrlQueueItemRepositoryTestCase extends \Searchperience\Tests\BaseTestCase {

	/**
	 * @var \Searchperience\Api\Client\Domain\UrlQueueItem\UrlQueueItemRepository
	 */
	protected $urlQueueItemRepository;

	/**
	 * @return void
	 */
	public function setUp() {
		$this->urlQueueItemRepository = new UrlQueueItemRepository();
	}

	/**
	 * @test
	 */
	public function canGetByUrl() {
			/** @var  $storageBackendMock \Searchperience\Api\Client\System\Storage\RestUrlQueueItemBackend */
		$storageBackendMock = $this->getMock("\Searchperience\Api\Client\System\Storage\RestUrlQueueItemBackend",array('getByUrl'));
		$storageBackendMock->expects($this->once())->method('getByUrl')->with('http://www.google.de/')->will($this->returnValue(
			new \Searchperience\Api\Client\Domain\UrlQueueItem\UrlQueueItem()
		));;
		$this->urlQueueItemRepository->injectStorageBackend($storageBackendMock);
		$this->urlQueueItemRepository->getByUrl('http://www.google.de/');
	}
}