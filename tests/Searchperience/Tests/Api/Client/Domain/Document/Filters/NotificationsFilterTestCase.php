<?php
/**
 * @Author: Nikolay Diaur <nikolay.diaur@aoe.com>
 * @Date: 2/24/14
 * @Time: 6:19 PM
 */

namespace Searchperience\Api\Client\Domain\Document\Filters;

use Searchperience\Api\Client\Domain\Document\Document;

/**
 * Class NotificationsFilterTestCase
 * @package Searchperience\Api\Client\Domain\Filters
 */
class NotificationsFilterTestCase extends \Searchperience\Tests\BaseTestCase
{

	/**
	 * @return array
	 */
	public function filterParamsProvider() {
		return array(
			array(
				'notifications' => array(Document::IS_DUPLICATE,Document::IS_ERROR,Document::IS_PROCESSING),
				'expectedResult' => '&isDuplicate=1&hasError=1&processingThreadIdStart=1&processingThreadIdEnd=65536&isDeleted=0'),
			array(
				'notifications' => array(Document::IS_ERROR,Document::IS_PROCESSING),
				'expectedResult' => '&hasError=1&processingThreadIdStart=1&processingThreadIdEnd=65536&isDeleted=0'
			),
			array(
				'notifications' => array(Document::IS_PROCESSING),
				'expectedResult' => '&processingThreadIdStart=1&processingThreadIdEnd=65536&isDeleted=0'),
			array(
				'notifications' => array(),
				'expectedResult' => ''
			),
			array(
				'notifications' => array(Document::IS_DELETING),
				'expectedResult' => '&isDeleted=1'
			),
			array(
				'notifications' => array(Document::IS_WAITING),
				'expectedResult' => '&isWaiting=1'
			),
			array(
				'notifications' => array(Document::IS_REDIRECT),
				'expectedResult' => '&isRedirect=1'
			),
			array(
				'notifications' => array(Document::IS_REDIRECT,Document::IS_ERROR),
				'expectedResult' => '&isRedirect=1&hasError=1'

			)
		);
	}

	/**
	 * @param array $notifications
	 * @params string $expectedResult
	 * @test
	 * @dataProvider filterParamsProvider
	 */
	public function canSetFilterParams($notifications, $expectedResult) {

		$instance = new \Searchperience\Api\Client\Domain\Document\Filters\NotificationsFilter;
		$instance->setNotifications($notifications);

		$this->assertEquals($expectedResult, $instance->getFilterString());
	}
}