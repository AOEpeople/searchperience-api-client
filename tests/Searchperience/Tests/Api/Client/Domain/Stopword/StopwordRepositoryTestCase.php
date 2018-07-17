<?php

namespace Searchperience\Api\Client\Domain\Stopword;

/**
 * @author Pavlo Bogomolenko <pavlo.bogomolenko@aoe.com>
 */
class StopwordRepositoryTestCase extends \Searchperience\Tests\BaseTestCase {

	/**
	 * @var \Searchperience\Api\Client\Domain\Stopword\StopwordRepository
	 */
	protected $stopwordRepository;

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
		$this->stopwordRepository = NULL;
	}

	/**
	 * @test
	 */
	public function decorateNotCalledWhenNullObjectReturned() {
		$storageBackend = $this->getMockBuilder('\Searchperience\Api\Client\System\Storage\RestStopwordBackend')->setMethods(array('getByWord'))->getMock();
		$storageBackend->expects($this->once())
				->method('getByWord')
				->will($this->returnValue(null));

		$this->stopwordRepository = $this->getMockBuilder('\Searchperience\Api\Client\Domain\Stopword\StopwordRepository')->setMethods(array('decorate'))->setConstructorArgs(array())->setMockClassName('')->disableOriginalConstructor(false)->getMock();
		$this->stopwordRepository->expects($this->never())->method('decorate');
		$this->stopwordRepository->injectStorageBackend($storageBackend);

		$result = $this->stopwordRepository->getByWord('test', 'test');
		$this->assertEquals(null, $result, 'Expected that result will be null when storage backend is returning null');
	}
}
