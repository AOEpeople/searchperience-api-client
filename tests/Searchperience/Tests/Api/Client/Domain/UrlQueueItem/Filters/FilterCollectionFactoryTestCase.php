<?php

namespace Searchperience\Api\Client\Domain\UrlQueueItem\Filters;

/**
 * @Author: Nikolay Diaur <nikolay.diaur@aoe.com>
 * @Date: 2/25/14
 * @Time: 3:59 PM
 */

use Searchperience\Api\Client\Domain\UrlQueueItem\UrlQueueItem;

/**
 * Class FilterFilterRepositoryTestCase
 * @package Searchperience\Api\Client\Domain\Filters
 */
class FilterCollectionFactoryTestCase extends \Searchperience\Tests\BaseTestCase {
	/**
	 * @var \Searchperience\Api\Client\Domain\UrlQueueItem\Filters\FilterCollectionFactory
	 */
	protected $instance;

	/**
	 * Initialize test environment
	 *
	 * @return void
	 */
	public function setUp() {
		$this->instance = new \Searchperience\Api\Client\Domain\UrlQueueItem\Filters\FilterCollectionFactory;
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
	 * This testcase is used to check it a filter collection can be created based on the document states.
	 *
	 * @test
	 */
	public function testCanBuildFilterCollectionForWaiting() {
		$states = array(UrlQueueItem::IS_WAITING);

		$filterCollection 		= $this->instance->createFromUrlQueueItemStates($states);
		$filterString 			= $filterCollection->getFilterStringFromAll();
		$expectedFilterString 	= '&processingThreadIdStart=0&processingThreadIdEnd=0&deleted=0';
		$this->assertEquals($expectedFilterString, $filterString);
	}

	/**
	 * This testcase is used to check if a filter collection can be build to filter for
	 * items that are currently in progress.
	 *
	 * @test
	 */
	public function testCanBuildFilterCollectionForProcessing() {
		$states = array(UrlQueueItem::IS_PROCESSING);
		$filterCollection 		= $this->instance->createFromUrlQueueItemStates($states);
		$filterString 			= $filterCollection->getFilterStringFromAll();
		$expectedFilterString 	= '&processingThreadIdStart=1&processingThreadIdEnd=65536&deleted=0';
		$this->assertEquals($expectedFilterString, $filterString);
	}

	/**
	 * @test
	 */
	public function testCanBuildFiltersForDeleted() {
		$states = array(UrlQueueItem::IS_DOCUMENT_DELETED);
		$filterCollection 		= $this->instance->createFromUrlQueueItemStates($states);
		$filterString 			= $filterCollection->getFilterStringFromAll();
		$expectedFilterString 	= '&deleted=1';
		$this->assertEquals($expectedFilterString, $filterString);
	}

	/**
	 * @test
	 */
	public function testCanBuildFiltersForErrorDocuments() {
		$states = array(UrlQueueItem::IS_ERROR);
		$filterCollection 		= $this->instance->createFromUrlQueueItemStates($states);
		$filterString 			= $filterCollection->getFilterStringFromAll();
		$expectedFilterString 	= '&error=1';
		$this->assertEquals($expectedFilterString, $filterString);
	}
}