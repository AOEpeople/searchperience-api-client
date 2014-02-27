<?php
/**
 * @Author: Nikolay Diaur <nikolay.diaur@aoe.com>
 * @Date: 2/24/14
 * @Time: 6:19 PM
 */

namespace Searchperience\Api\Client\Domain\Filters;

/**
 * Class PageRankFilterTestCase
 * @package Searchperience\Api\Client\Domain\Filters
 */
class PageRankFilterTestCase extends \Searchperience\Tests\BaseTestCase {

	/**
	 * @return array
	 */
	public function filterParamsProvider() {
		return array(
				array('prStart' => 0.001, 'prEnd' => 123.00, 'expectedResult' => '&prStart=0.001&prEnd=123'),
				array('prStart' => 0.001, 'prEnd' => null, 'expectedResult' => '&prStart=0.001'),
				array('prStart' => null, 'prEnd' => 158.569, 'expectedResult' => '&prEnd=158.569'),
				array('prStart' => null, 'prEnd' => null, 'expectedResult' => ''),
		);
	}

	/**
	 * @params string $prStart
	 * @params string $prEnd
	 * @params string $expectedResult
	 * @test
	 * @dataProvider filterParamsProvider
	 */
	public function canSetFilterParams($prStart, $prEnd, $expectedResult) {
		$instance = new \Searchperience\Api\Client\Domain\Filters\PageRankFilter;
		$instance->setPrStart($prStart);
		$instance->setPrEnd($prEnd);

		$this->assertEquals($expectedResult, $instance->getFilterString());
	}
}