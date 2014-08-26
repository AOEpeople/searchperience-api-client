<?php
/**
 * Created by PhpStorm.
 * User: mick
 * Date: 23.05.14
 * Time: 11:58
 */

namespace Searchperience\Api\Client\Domain\AdminSearch;


use Searchperience\Tests\BaseTestCase;
use Searchperience\Api\Client\Domain\AdminSearch\AdminSearchCollection;

/**
 * Verify that the AdminSearchRepository returns AdminSearch objects
 *
 * Class AdminSearchRepositoryTestCase
 *
 * @package Searchperience\Api\Client\Domain\AdminSearch
 * @author Michael Klapper <michael.klapper@aoe.com>
 */
class AdminSearchRepositoryTestCase extends BaseTestCase {

	/**
	 * @var AdminSearchRepository
	 */
	protected $adminSearchRepository;

	/**
	 * Instantiate a fresh instance of AdminSearchRepository
	 *
	 * @return void
	 */
	public function setUp() {
		$this->adminSearchRepository = new AdminSearchRepository();
	}

	/**
	 * Reset AdminSearchRepository in order to reduce side effects while testing.
	 *
	 * @return void
	 */
	public function tearDown() {
		$this->adminSearchRepository = NULL;
	}

	/**
	 * @test
	 * @group admin
	 */
	public function verifyRepositoryRetrievesAdminSearchCollection() {
		$storageBackend = $this->getMock('\Searchperience\Api\Client\System\Storage\RestAdminSearchBackend', array('getAll'));
		$storageBackend->expects($this->once())
			->method('getAll')
			->will($this->returnValue(new AdminSearchCollection()));

		$this->adminSearchRepository->setEntityCollection('\Searchperience\Api\Client\Domain\AdminSearch\AdminSearchCollection');
		$this->adminSearchRepository->injectStorageBackend($storageBackend);

		$this->adminSearchRepository->getAll();
	}
}