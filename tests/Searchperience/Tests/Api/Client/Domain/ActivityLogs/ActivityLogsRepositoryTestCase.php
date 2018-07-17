<?php

namespace Searchperience\Tests\Api\Client\Document;

use Searchperience\Api\Client\Domain\ActivityLogs\Filters\FilterCollectionFactory;
use Searchperience\Api\Client\System\Storage;
use Searchperience\Api\Client\Domain\ActivityLogs\ActivityLogsRepository;

/**
 * @author: Nikolay Diaur <nikolay.diaur@aoe.com>
 */
class ActivityLogsRepositoryTestCase extends \Searchperience\Tests\BaseTestCase {

	/**
	 * @var \Searchperience\Api\Client\Domain\ActivityLogs\ActivityLogsRepository
	 */
	protected $activityLogsRepository;

	/**
	 * @return void
	 */
	public function setUp() {
		$this->activityLogsRepository = new ActivityLogsRepository();
	}

	/**
	 * @test
	 */
	public function getAll() {
		/** @var  $storageBackendMock \Searchperience\Api\Client\System\Storage\RestActivityLogsBackend */
		$storageBackendMock = $this->getMockBuilder("\Searchperience\Api\Client\System\Storage\RestActivityLogsBackend")->setMethods(array('getAllByFilterCollection'))->getMock();
		$storageBackendMock->expects($this->once())->method('getAllByFilterCollection')->will($this->returnValue(
			new \Searchperience\Api\Client\Domain\ActivityLogs\ActivityLogsCollection()
		));
		$this->activityLogsRepository->setEntityCollectionName('\Searchperience\Api\Client\Domain\ActivityLogs\ActivityLogsCollection');
		$this->activityLogsRepository->injectStorageBackend($storageBackendMock);
		$this->activityLogsRepository->injectFilterCollectionFactory(new FilterCollectionFactory());
		$this->activityLogsRepository->getAllByFilterCollection(0,10);
	}
}