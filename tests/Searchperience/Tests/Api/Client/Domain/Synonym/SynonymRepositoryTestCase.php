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
		$storageBackend = $this->getMock('\Searchperience\Api\Client\System\Storage\RestSynonymBackend', array('getByMainWord'));
		$storageBackend->expects($this->once())
				->method('getByMainWord')
				->will($this->returnValue(null));

		$this->synonymRepository = $this->getMock('\Searchperience\Api\Client\Domain\Synonym\SynonymRepository',array('decorate'),array(),'',false);
		$this->synonymRepository->expects($this->never())->method('decorate');
		$this->synonymRepository->injectStorageBackend($storageBackend);

		$result = $this->synonymRepository->getBySynonyms('test', 'test');
		$this->assertEquals(null, $result, 'Expected that result will be null when storage backend is returning null');
	}
}
