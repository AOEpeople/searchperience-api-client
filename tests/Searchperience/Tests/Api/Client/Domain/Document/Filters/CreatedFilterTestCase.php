<?php
/**
 * @Author: Nikolay Diaur <nikolay.diaur@aoe.com>
 * @Date: 2/24/14
 * @Time: 6:19 PM
 */

namespace Searchperience\Api\Client\Domain\Document\Filters;

/**
 * Class CreatedFilterTestCase
 * @package Searchperience\Api\Client\Domain\Filters
 */
class CreatedFilterTestCase extends \Searchperience\Tests\BaseTestCase {

	/**
	 * @return array
	 */
	public function filterParamsProvider() {
		return array(
				array(
					'createdFrom' => $this->getUTCDateTimeObject('2014-01-01 10:00:00'),
					'createdTo' => $this->getUTCDateTimeObject('2014-01-01 10:00:00'),
					'expectedResult' => '&createdFrom=2014-01-01%2010%3A00%3A00&createdTo=2014-01-01%2010%3A00%3A00'
				),
				array(
					'createdFrom' => $this->getUTCDateTimeObject('2014-01-01 10:00:00'),
					'createdTo' => null,
					'expectedResult' => '&createdFrom=2014-01-01%2010%3A00%3A00'
				),
				array(
					'createdFrom' => null,
					'createdTo' => $this->getUTCDateTimeObject('2014-01-01 10:00:00'),
					'expectedResult' => '&createdTo=2014-01-01%2010%3A00%3A00'
				),
				array(
					'createdFrom' => null,
					'createdTo' => null,
					'expectedResult' => ''
				),
		);
	}

	/**
	 * @params string $createdFrom
	 * @params string $createdTo
	 * @params string $expectedResult
	 * @test
	 * @dataProvider filterParamsProvider
	 */
	public function canSetFilterParams($createdFrom, $createdTo, $expectedResult) {
		$instance = new \Searchperience\Api\Client\Domain\Document\Filters\CreatedFilter();
		$instance->injectDateTimeService(new \Searchperience\Api\Client\System\DateTime\DateTimeService());

		if($createdFrom instanceof \DateTime) {
			$instance->setCreatedFrom($createdFrom);
		}

		if($createdTo instanceof \DateTime) {
			$instance->setCreatedTo($createdTo);
		}

		$this->assertEquals($expectedResult, $instance->getFilterString());
	}
}
