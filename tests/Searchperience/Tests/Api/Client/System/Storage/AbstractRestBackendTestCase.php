<?php

namespace Searchperience\Tests\Api\Client\System\Storage;

/**
 * @author Michael Klapper <michael.klapper@aoemedia.de>
 * @date 17.11.12
 * @time 09:37
 */
class AbstractRestBackendTestCase extends \Searchperience\Tests\BaseTestCase {
	/**
	 * Initialize test environment
	 *
	 * @return void
	 */
	public function setUp() {
	}

	/**
	 * Cleanup test environment
	 *
	 * @return void
	 */
	public function tearDown() {
	}

	/**
	 * @test
	 */
	public function verifyAbstractRestBackendSetter() {
		$baseUrl = 'http://www.searchperience.com/';
		$username = 'user';
		$password = 'pass';
		/** @var $restBackend \Searchperience\Api\Client\System\Storage\AbstractRestBackend */
		$restBackend = $this->getAccessibleMockForAbstractClass('\Searchperience\Api\Client\System\Storage\AbstractRestBackend');
		$restBackend->setBaseUrl($baseUrl);
		$restBackend->setPassword($password);
		$restBackend->setUsername($username);

		$this->assertEquals($baseUrl, $restBackend->_get('baseUrl'));
		$this->assertEquals($username, $restBackend->_get('username'));
		$this->assertEquals($password, $restBackend->_get('password'));
	}

	/**
	 * Provides Status codes and their expected Exception class names
	 *
	 * @return array
	 */
	public function verifyTransformStatusCodeIntoRightExceptionDataProvider() {
		return array(
			array('200', NULL),
			array('201', NULL),
			array('404', NULL),
		);
	}

	/**
	 * @test
	 * @param string $statusCode
	 * @param string $exceptionClassName
	 * @dataProvider verifyTransformStatusCodeIntoRightExceptionDataProvider
	 */
	public function verifyTransformStatusCodeIntoRightException($statusCode, $exceptionClassName) {

	}
}
