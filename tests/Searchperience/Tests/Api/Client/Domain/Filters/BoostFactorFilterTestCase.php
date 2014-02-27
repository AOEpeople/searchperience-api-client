<?php
/**
 * @Author: Nikolay Diaur <nikolay.diaur@aoe.com>
 * @Date: 2/24/14
 * @Time: 6:19 PM
 */

namespace Searchperience\Api\Client\Domain\Filters;

/**
 * Class BoostFactorFilterTestCase
 * @package Searchperience\Api\Client\Domain\Filters
 */
class BoostFactorFilterTestCase extends \Searchperience\Tests\BaseTestCase {

	/**
	 * @return array
	 */
	public function filterParamsProvider() {
		return array(
				array('bfStart' => 0.001, 'bfEnd' => 123.00, 'expectedResult' => '&bfStart=0.001&bfEnd=123'),
				array('bfStart' => 0.001, 'bfEnd' => null, 'expectedResult' => '&bfStart=0.001'),
				array('bfStart' => null, 'bfEnd' => 158.569, 'expectedResult' => '&bfEnd=158.569'),
				array('bfStart' => null, 'bfEnd' => null, 'expectedResult' => ''),
		);
	}
	/**
	 * @params string $bfStart
	 * @params string $bfEnd
	 * @params string $expectedResult
	 * @test
	 * @dataProvider filterParamsProvider
	 */
	public function canSetFilterParams($bfStart, $bfEnd, $expectedResult) {
		$instance = new \Searchperience\Api\Client\Domain\Filters\BoostFactorFilter;
		$instance->setBfStart($bfStart);
		$instance->setBfEnd($bfEnd);

		$this->assertEquals($expectedResult, $instance->getFilterString());
	}
}
 