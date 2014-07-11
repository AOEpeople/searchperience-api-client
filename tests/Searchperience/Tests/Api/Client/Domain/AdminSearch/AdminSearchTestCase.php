<?php
/**
 * Created by PhpStorm.
 * User: mick
 * Date: 20.05.14
 * Time: 17:07
 */

namespace Searchperience\Api\Client\Domain\AdminSearch;

use Searchperience\Tests\BaseTestCase;

/**
 * Class AdminSearchTest
 *
 * @package Searchperience\Api\Client\Domain\AdminSearch
 * @author Michael Klapper <michael.klappper@aoe.com>
 */
class AdminSearchTestCase extends BaseTestCase {

	/**
	 * @var AdminSearch
	 */
	protected $adminSearch;

	/**
	 * Initialize test class
	 */
	public function setUp() {
		$this->adminSearch = new AdminSearch();
	}

	/**
	 * Reset test case
	 */
	public function tearDown() {
		$this->adminSearch = NULL;
	}

	/**
	 * @test
	 * @group admin
	 */
	public function verifyAdminSearchHasProperties() {
		$this->adminSearch->__setProperty("title","test title");
		$this->adminSearch->__setProperty("description","test description");
		$this->adminSearch->__setProperty("url","http://www.your.domain");

		$this->assertEquals($this->adminSearch->getTitle(), "test title");
		$this->assertEquals($this->adminSearch->getDescription(), "test description");
		$this->assertEquals($this->adminSearch->getUrl(), "http://www.your.domain");
	}
}
