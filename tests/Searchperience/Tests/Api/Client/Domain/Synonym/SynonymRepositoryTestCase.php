<?php

namespace Searchperience\Api\Client\Domain\Synonym;

/**
 * @author Pavlo Bogomolenko <pavlo.bogomolenko@aoe.com>
 */
class SynonymRepositoryTestCase extends \Searchperience\Tests\BaseTestCase {

	/**
	 * @var \Searchperience\Api\Client\Domain\Synonym\SynonymRepository
	 */
	protected $synonymRepository;

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
		$this->synonymRepository = NULL;
	}

	/**
	 * @test
	 */
	public function decorateNotCalledWhenNullObjectReturned() {
		$storageBackend = $this->getMockBuilder('\Searchperience\Api\Client\System\Storage\RestSynonymBackend')->setMethods(array('getBySynonyms'))->getMock();
		$storageBackend->expects($this->once())
				->method('getBySynonyms')
				->will($this->returnValue(null));

		$this->synonymRepository = $this->getMockBuilder('\Searchperience\Api\Client\Domain\Synonym\SynonymRepository')->setMethods(array('decorate'))->setConstructorArgs(array())->setMockClassName('')->disableOriginalConstructor(false)->getMock();
		$this->synonymRepository->expects($this->never())->method('decorate');
		$this->synonymRepository->injectStorageBackend($storageBackend);

		$result = $this->synonymRepository->getBySynonyms('test', 'test');
		$this->assertEquals(null, $result, 'Expected that result will be null when storage backend is returning null');
	}
}
