<?php

namespace Searchperience\Tests\Api\Client\Document;

use Searchperience\Api\Client\Domain\CommandLog\Filters\FilterCollectionFactory;
use Searchperience\Api\Client\System\Storage;
use Searchperience\Api\Client\Domain\CommandLog\CommandLogRepository;

/**
 * @author: Nikolay Diaur <nikolay.diaur@aoe.com>
 */
class CommandLogRepositoryTestCase extends \Searchperience\Tests\BaseTestCase {

	/**
	 * @var \Searchperience\Api\Client\Domain\CommandLog\CommandLogRepository
	 */
	protected $commandLogRepository;

	/**
	 * @return void
	 */
	public function setUp() {
		$this->commandLogRepository = new CommandLogRepository();
	}

	/**
	 * @test
	 */
	public function getAll() {
		/** @var  $storageBackendMock \Searchperience\Api\Client\System\Storage\RestCommandLogBackend */
		$storageBackendMock = $this->getMock("\Searchperience\Api\Client\System\Storage\RestCommandLogBackend",array('getAllByFilterCollection'));
		$storageBackendMock->expects($this->once())->method('getAllByFilterCollection')->will($this->returnValue(
			new \Searchperience\Api\Client\Domain\CommandLog\CommandLogCollection()
		));;
		$this->commandLogRepository->injectStorageBackend($storageBackendMock);
		$this->commandLogRepository->injectFilterCollectionFactory(new FilterCollectionFactory());
		$this->commandLogRepository->getAllByFilterCollection(0,10);
	}
}