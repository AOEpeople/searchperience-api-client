<?php
/**
 * @Author: Nikolay Diaur <nikolay.diaur@aoe.com>
 * @Date: 2/24/14
 * @Time: 6:19 PM
 */

namespace Searchperience\Api\Client\Domain\Document\Filters;

/**
 * Class UpdatedFilterTestCase
 * @package Searchperience\Api\Client\Domain\Filters
 */
class UpdatedFilterTestCase extends \Searchperience\Tests\BaseTestCase {

	/**
	 * @return array
	 */
	public function filterParamsProvider() {
		return array(
				array(
					'updatedFrom' => $this->getUTCDateTimeObject('2014-01-01 10:00:00'),
					'updatedTo' => $this->getUTCDateTimeObject('2014-01-01 10:00:00'),
					'expectedResult' => '&updatedFrom=2014-01-01%2010%3A00%3A00&updatedTo=2014-01-01%2010%3A00%3A00'
				),
				array(
					'updatedFrom' => $this->getUTCDateTimeObject('2014-01-01 10:00:00'),
					'updatedTo' => null,
					'expectedResult' => '&updatedFrom=2014-01-01%2010%3A00%3A00'
				),
				array(
					'updatedFrom' => null,
					'updatedTo' => $this->getUTCDateTimeObject('2014-01-01 10:00:00'),
					'expectedResult' => '&updatedTo=2014-01-01%2010%3A00%3A00'
				),
				array(
					'updatedFrom' => null,
					'updatedTo' => null,
					'expectedResult' => ''
				),
		);
	}

	/**
	 * @params string $updatedFrom
	 * @params string $updatedTo
	 * @params string $expectedResult
	 * @test
	 * @dataProvider filterParamsProvider
	 */
	public function canSetFilterParams($updatedFrom, $updatedTo, $expectedResult) {
		$instance = new \Searchperience\Api\Client\Domain\Document\Filters\UpdatedFilter();
		$instance->injectDateTimeService(new \Searchperience\Api\Client\System\DateTime\DateTimeService());

		if($updatedFrom instanceof \DateTime) {
			$instance->setUpdatedFrom($updatedFrom);
		}

		if($updatedTo instanceof \DateTime) {
			$instance->setUpdatedTo($updatedTo);
		}

		$this->assertEquals($expectedResult, $instance->getFilterString());
	}
}
