<?php

namespace Searchperience\Api\Client\Domain\ActivityLogs\Filters;

/**
 * @Author: Nikolay Diaur <nikolay.diaur@aoe.com>
 */

use Searchperience\Api\Client\Domain\ActivityLogs\ActivityLogs;

/**
 * Class ActivityLogsFilterCollectionFactoryTestCase
 * @package Searchperience\Api\Client\Domain\Filters
 */
class ActivityLogsFilterCollectionFactoryTestCase extends \Searchperience\Tests\BaseTestCase {
	/**
	 * @var \Searchperience\Api\Client\Domain\ActivityLogs\Filters\FilterCollectionFactory
	 */
	protected $instance;

	/**
	 * Initialize test environment
	 *
	 * @return void
	 */
	public function setUp() {
		$this->instance = new \Searchperience\Api\Client\Domain\ActivityLogs\Filters\FilterCollectionFactory;
	}

	/**
	 * Cleanup test environment
	 *
	 * @return void
	 */
	public function tearDown() {
		$this->instance = null;
	}

	/**
	 * @test
	 */
	public function canGetAll() {
		$this->markTestIncomplete('finish me');
	}
}