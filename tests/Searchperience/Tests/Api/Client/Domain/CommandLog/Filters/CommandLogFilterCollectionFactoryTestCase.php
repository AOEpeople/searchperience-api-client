<?php

namespace Searchperience\Api\Client\Domain\CommandLog\Filters;

/**
 * @Author: Nikolay Diaur <nikolay.diaur@aoe.com>
 */

use Searchperience\Api\Client\Domain\CommandLog\CommandLog;

/**
 * Class CommandLogFilterCollectionFactoryTestCase
 * @package Searchperience\Api\Client\Domain\Filters
 */
class CommandLogFilterCollectionFactoryTestCase extends \Searchperience\Tests\BaseTestCase {
	/**
	 * @var \Searchperience\Api\Client\Domain\CommandLog\Filters\FilterCollectionFactory
	 */
	protected $instance;

	/**
	 * Initialize test environment
	 *
	 * @return void
	 */
	public function setUp() {
		$this->instance = new \Searchperience\Api\Client\Domain\CommandLog\Filters\FilterCollectionFactory;
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
			'time' => array(
				'startTime' => $this->getUTCDateTimeObject('2014-01-01 10:00:00'),
				'endTime' => $this->getUTCDateTimeObject('2014-01-01 10:00:00')
			),
			'query' => array('queryString' => 'api', 'queryFields' => 'log,command,binary'),
		);

		$filterCollection = $this->instance->createFromFilterArguments($arguments);
		$this->assertEquals('&startTime=2014-01-01%2010%3A00%3A00&endTime=2014-01-01%2010%3A00%3A00&query=api&queryFields=log%2Ccommand%2Cbinary',$filterCollection->getFilterStringFromAll(),'Could not build filterCollection with expected filter string');
	}

	/**
	 * @test
	 */
	public function testCanBuildFilterCollectionForStatus() {
		$arguments = array('status' => array('status' => 'finished'));

		$filterCollection = $this->instance->createFromFilterArguments($arguments);
		$this->assertEquals('&status=finished', $filterCollection->getFilterStringFromAll(), 'Could not build filterCollection with expected filter string');
	}

	/**
	 * @test
	 */
	public function testCanBuildFilterCollectionForDuration() {
		$arguments = array('duration' => array('duration' => 60, 'durationFrom' => 20, 'durationTo' => 30));

		$filterCollection = $this->instance->createFromFilterArguments($arguments);
		$this->assertEquals('&duration=60&durationFrom=20&durationTo=30', $filterCollection->getFilterStringFromAll(), 'Could not build filterCollection with expected filter string');
	}
}