<?php
/**
 * Created by PhpStorm.
 * User: mick
 * Date: 23.05.14
 * Time: 11:58
 */

namespace Searchperience\Api\Client\Domain\AdminSearch;


use Searchperience\Tests\BaseTestCase;

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
		$adminSearchCollection = $this->adminSearchRepository->getAll();

		$this->assertInstanceOf('\Searchperience\Api\Client\Domain\AdminSearch\AdminSearchCollection',
			$adminSearchCollection);
		$this->assertCount(2, $adminSearchCollection);
		$this->assertInstanceOf('\Searchperience\Api\Client\Domain\AdminSearch\AdminSearch',
			$adminSearchCollection->offsetGet(1));
		$this->assertEquals('German', $adminSearchCollection->offsetGet(0)->getTitle());
		$this->assertEquals('German search instance', $adminSearchCollection->offsetGet(0)->getDescription());
		$this->assertEquals('//bluestar.deploy.saascluster.aoe-works.de/index.php?id=1351',
			$adminSearchCollection->offsetGet(0)->getUrl());
	}
}