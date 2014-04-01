<?php
/**
 * @Author: Nikolay Diaur <nikolay.diaur@aoe.com>
 * @Date: 2/25/14
 * @Time: 3:59 PM
 */

namespace Searchperience\Api\Client\Domain\Filters;
use Searchperience\Api\Client\Domain\UrlQueueItem;

/**
 * Class FilterFilterRepositoryTestCase
 * @package Searchperience\Api\Client\Domain\Filters
 */
class FilterCollectionFactoryTestCase extends \Searchperience\Tests\BaseTestCase {
	/**
	 * @var \Searchperience\Api\Client\Domain\Filters\FilterCollectionFactory
	 */
	protected $instance;

	/**
	 * Initialize test environment
	 *
	 * @return void
	 */
	public function setUp() {
		$this->instance = new \Searchperience\Api\Client\Domain\Filters\FilterCollectionFactory;
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
	 * Data test provider
	 * @return array
	 */
	public function filtersProvider() {
		return array(
				array('data' =>
						array('boostFactor' => array('boostFactorStart' => 0.0, 'boostFactorEnd' => 123.05),
								'crawl' => array(
									'crawlStart' => $this->getUTCDateTimeObject('2014-01-01 10:00:00'),
									'crawlEnd' => $this->getUTCDateTimeObject('2014-01-01 10:00:00')
								),
								'lastProcessed' => array(
									'processStart' => $this->getUTCDateTimeObject('2014-01-01 10:00:00'),
									'processEnd' => $this->getUTCDateTimeObject('2014-01-01 10:00:00')
								),
								'notifications' => array('isduplicateof' => true, 'lasterror' => true, 'processingthreadid' => true),
								'pageRank' => array('pageRankStart' => 0.0, 'pageRankEnd' => 123.05),
								'query' => array('queryString' => 'test', 'queryFields' => 'id,url'),
								'source' => array('source' => 'magento')),
								'expectedResult' => '&boostFactorStart=0&boostFactorEnd=123.05&crawlStart=2014-01-01%2010%3A00%3A00&crawlEnd=2014-01-01%2010%3A00%3A00&processStart=2014-01-01%2010%3A00%3A00&processEnd=2014-01-01%2010%3A00%3A00&isduplicateof=1&lasterror=1&processingthreadid=1&pageRankEnd=123.05&query=test&queryFields=id%2Curl&source=magento'
				),
		);
	}

	/**
	 * @params $data
	 * @test
	 * @dataProvider filtersProvider
	 */
	public function canAddFilters($data) {
		try {
			$this->instance->createFromFilterArguments($data);
		} catch (\Exception $e) {
			$this->assertFalse(false, $e->getMessage());
		}

	}

	/**
	 * @params $data
	 * @params $expectedResult
	 * @test
	 * @dataProvider filtersProvider
	 */
	public function canGetFilters($data, $expectedResult) {
		$filterCollection 	= $this->instance->createFromFilterArguments($data);
		$loadResult 		= $filterCollection->getFilterStringFromAll();

		$this->assertEquals($expectedResult, $loadResult);
	}

	/**
	 * Test filter invalid name
	 * @test
	 * @expectedException \UnexpectedValueException
	 */
	public function testFiltersInvalidName() {
		$this->instance->createFromFilterArguments(array('foo' => array('boostFactorStart' => 0.0, 'boostFactorEnd' => 123.05)));
	}

	/**
	 * Test filter invalid parameters
	 * @test
	 * @expectedException \InvalidArgumentException
	 */
	public function testInvalidFilterParam() {
		$this->instance->createFromFilterArguments(array('boostFactor' => array('qqqq' => 0.0, 'boostFactorEnd' => 123.05)));
	}


	/**
	 * Data test provider
	 * @return array
	 */
	public function filtersInvalidValuesProvider() {
		return array(
				array('data' => array('boostFactor' => array('boostFactorStart' => 'wrong', 'boostFactorEnd' => 123.05))),
				array('data' => array('crawl' => array('crawlStart' => 'wrong', 'crawlEnd' => $this->getUTCDateTimeObject('2014-01-01 10:00:00')))),
				array('data' => array('lastProcessed' => array('processStart' => 'wrong', 'processEnd' => $this->getUTCDateTimeObject('2014-01-01 10:00:00')))),
				array('data' => array('notifications' => array('isduplicateof' => 132, 'lasterror' => true, 'processingthreadid' => true))),
				array('data' => array('pageRank' => array('pageRankStart' => 'wrong', 'pageRankEnd' => 123.00,))),
				array('data' => array('query' => array('query' => 'test case', 'queryFields' => 132))),
				array('data' => array('source' => array('source' => 1321))),
		);
	}

	/**
	 * Test filter invalid values assert
	 * @params $data
	 * @test
	 * @dataProvider filtersInvalidValuesProvider
	 * @expectedException \InvalidArgumentException
	 */
	public function testInvalidFilterValues($data) {
		$this->instance->createFromFilterArguments($data);
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