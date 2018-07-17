<?php

namespace Searchperience\Tests\Api\Client\Document;

use Searchperience\Api\Client\Domain\UrlQueueItem\Filters\FilterCollectionFactory;
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
		$storageBackendMock = $this->createMock("\Searchperience\Api\Client\System\Storage\RestUrlQueueItemBackend",array('getByUrl'));
		$storageBackendMock->expects($this->once())->method('getByUrl')->with('http://www.google.de/')->will($this->returnValue(
			new \Searchperience\Api\Client\Domain\UrlQueueItem\UrlQueueItem()
		));;
		$this->urlQueueItemRepository->injectStorageBackend($storageBackendMock);
		$this->urlQueueItemRepository->getByUrl('http://www.google.de/');
	}

	/**
	 * @test
	 */
	public function canDeleteByDocumentId() {
		/** @var  $storageBackendMock \Searchperience\Api\Client\System\Storage\RestUrlQueueItemBackend */
		$storageBackendMock = $this->getMockBuilder('\Searchperience\Api\Client\System\Storage\RestUrlQueueItemBackend')->setMethods(array('deleteByDocumentId'))->getMock();
		$storageBackendMock->expects($this->once())->method('deleteByDocumentId')->with(111)->will($this->returnValue(
			new \Searchperience\Api\Client\Domain\UrlQueueItem\UrlQueueItem()
		));;
		$this->urlQueueItemRepository->injectStorageBackend($storageBackendMock);
		$this->urlQueueItemRepository->deleteByDocumentId(111);
	}

	/**
	 * @test
	 */
	public function canDeleteByUrl() {
		/** @var  $storageBackendMock \Searchperience\Api\Client\System\Storage\RestUrlQueueItemBackend */
		$storageBackendMock = $this->getMockBuilder('\Searchperience\Api\Client\System\Storage\RestUrlQueueItemBackend')->setMethods(array('deleteByUrl'))->getMock();
		$storageBackendMock->expects($this->once())->method('deleteByUrl')->with('http://www.customer.com/')->will($this->returnValue(
			new \Searchperience\Api\Client\Domain\UrlQueueItem\UrlQueueItem()
		));;
		$this->urlQueueItemRepository->injectStorageBackend($storageBackendMock);
		$this->urlQueueItemRepository->deleteByUrl('http://www.customer.com/');
	}

	/**
	 * @test
	 */
	public function getAllByStates() {
		/** @var  $storageBackendMock \Searchperience\Api\Client\System\Storage\RestUrlQueueItemBackend */
		$storageBackendMock = $this->getMockBuilder('\Searchperience\Api\Client\System\Storage\RestUrlQueueItemBackend')->setMethods(array('getAllByFilterCollection'))->getMock();
		$storageBackendMock->expects($this->once())->method('getAllByFilterCollection')->will($this->returnValue(
			new \Searchperience\Api\Client\Domain\UrlQueueItem\UrlQueueItemCollection()
		));;
		$this->urlQueueItemRepository->injectStorageBackend($storageBackendMock);
		$this->urlQueueItemRepository->injectFilterCollectionFactory(new FilterCollectionFactory());
		$this->urlQueueItemRepository->getAllByStates(0,10,array(
			\Searchperience\Api\Client\Domain\UrlQueueItem\UrlQueueItem::IS_PROCESSING
		));
	}
}