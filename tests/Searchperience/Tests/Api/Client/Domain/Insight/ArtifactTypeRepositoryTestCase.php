<?php
/**
 * Created by IntelliJ IDEA.
 * User: pavelbogomolenko
 * Date: 07/07/14
 * Time: 16:24
 * To change this template use File | Settings | File Templates.
 */

namespace Searchperience\Tests\Api\Client\Insight;


use Searchperience\Api\Client\Domain\Insight\ArtifactTypeRepository;
use Searchperience\Api\Client\Domain\Insight\ArtifactTypeCollection;


/**
 * Class ArtifactTypeRepositoryTestCase
 * @package Searchperience\Tests\Api\Client\Insight
 */
class ArtifactTypeRepositoryTestCase extends \Searchperience\Tests\BaseTestCase {
	/**
	 * @var ArtifactTypeRepository
	 */
	protected $repository;

	/**
	 * Initialize test environment
	 *
	 * @return void
	 */
	public function setUp() {
		$this->repository = new ArtifactTypeRepository();
	}

	/**
	 * Cleanup test environment
	 *
	 * @return void
	 */
	public function tearDown() {
		$this->repository = NULL;
	}

	/**
	 * @test
	 */
	public function canGetAll() {
		$storageBackend = $this->getMock('\Searchperience\Api\Client\System\Storage\RestArtifactTypeBackend', array('getAll'));
		$storageBackend->expects($this->once())
			->method('getAll')
			->will($this->returnValue(new ArtifactTypeCollection()));

		$this->repository->injectStorageBackend($storageBackend);
		$this->repository->setEntityCollectionName('\Searchperience\Api\Client\Domain\Insight\ArtifactTypeCollection');

		$this->repository->getAll();
	}
}