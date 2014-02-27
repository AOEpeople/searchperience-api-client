<?php
/**
 * @Author: Nikolay Diaur <nikolay.diaur@aoe.com>
 * @Date: 2/24/14
 * @Time: 6:19 PM
 */

namespace Searchperience\Api\Client\Domain\Filters;

/**
 * Class CrawlFilterTestCase
 * @package Searchperience\Api\Client\Domain\Filters
 */
class CrawlFilterTestCase extends \Searchperience\Tests\BaseTestCase {

	/**
	 * @return array
	 */
	public function filterParamsProvider() {
		return array(
				array('crawlStart' => '2014-01-01 10:00:00', 'crawlEnd' => '2014-01-01 10:00:00', 'expectedResult' => '&crawlStart=2014-01-01%2010%3A00%3A00&crawlEnd=2014-01-01%2010%3A00%3A00'),
				array('crawlStart' => '2014-01-01 10:00:00', 'crawlEnd' => null, 'expectedResult' => '&crawlStart=2014-01-01%2010%3A00%3A00'),
				array('crawlStart' => null, 'crawlEnd' => '2014-01-01 10:00:00', 'expectedResult' => '&crawlEnd=2014-01-01%2010%3A00%3A00'),
				array('crawlStart' => null, 'crawlEnd' => null, 'expectedResult' => ''),
		);
	}

	/**
	 * @params string $crawlStart
	 * @params string $crawlEnd
	 * @params string $expectedResult
	 * @test
	 * @dataProvider filterParamsProvider
	 */
	public function canSetFilterParams($crawlStart, $crawlEnd, $expectedResult) {
		$instance = new \Searchperience\Api\Client\Domain\Filters\CrawlFilter;
		$instance->setCrawlStart($crawlStart);
		$instance->setCrawlEnd($crawlEnd);

		$this->assertEquals($expectedResult, $instance->getFilterString());
	}
}
 