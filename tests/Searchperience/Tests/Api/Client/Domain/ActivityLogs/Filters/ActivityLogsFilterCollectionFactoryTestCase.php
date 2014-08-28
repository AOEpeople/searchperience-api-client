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
	public function canBuildFilterCollectionFromArguments() {
		$arguments = array(
				'logTime' => array(
						'logtimeStart' => \DateTime::createFromFormat('Y-m-d H:i:s', '2014-01-01 09:00:00', new \DateTimeZone('UTC')),
						'logtimeEnd' => \DateTime::createFromFormat('Y-m-d H:i:s', '2014-01-01 10:00:00', new \DateTimeZone('UTC'))
				),
				'query' => array('queryString' => 'api', 'queryFields' => 'id,message,classname,methodname,processid,tag'),
		);

		$filterCollection = $this->instance->createFromFilterArguments($arguments);

		$this->assertEquals('&logtimeStart=2014-01-01%2009%3A00%3A00&logtimeEnd=2014-01-01%2010%3A00%3A00&query=api&queryFields=id%2Cmessage%2Cclassname%2Cmethodname%2Cprocessid%2Ctag', $filterCollection->getFilterStringFromAll(), 'Could not build filterCollection with expected filter string');
	}

	/**
	 * @test
	 */
	public function testCanBuildFilterCollectionForDuration() {
		$arguments = array('severity' => array('severityStart' => 10, 'severityEnd' => 20));

		$filterCollection = $this->instance->createFromFilterArguments($arguments);
		$this->assertEquals('&severityStart=10&severityEnd=20', $filterCollection->getFilterStringFromAll(), 'Could not build filterCollection with expected filter string');
	}
}