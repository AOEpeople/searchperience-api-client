<?php
/**
 * @author: Timo Schmidt <timo.schmidt@aoe.com>
 * @Date: 2/25/14
 * @Time: 3:59 PM
 */

namespace Searchperience\Api\Client\Domain\Filters;
use Searchperience\Api\Client\Domain\Enrichment\Enrichment;
use Searchperience\Api\Client\Domain\Enrichment\FieldEnrichment;
use Searchperience\Api\Client\Domain\Enrichment\MatchingRule;
use Searchperience\Api\Client\Domain\Enrichment\EnrichmentRepository;

/**
 * Class EnrichmentRepositoryTestCase
 * @package Searchperience\Api\Client\Domain\Filters
 */
class EnrichmentRepositoryTestCase extends \Searchperience\Tests\BaseTestCase {

	/**
	 * @var EnrichmentRepository
	 */
	protected $enrichmentRepository;

	/**
	 * @return void
	 */
	public function setUp() {
		$this->enrichmentRepository = new EnrichmentRepository();
	}

	/**
	 * @test
	 * @return void
	 */
	public function testIsPassingToStorageBackend() {
		$enrichment = new Enrichment();
		$enrichment->setTitle('test');
		$enrichment->setId(44);

		$violationList = $this->getMock('\Symfony\Component\Validator\ConstraintViolationList', array('count'), array(), '', FALSE);
		$violationList->expects($this->once())
			->method('count')
			->will($this->returnValue(0));
		$validator = $this->getMock('\Symfony\Component\Validator\Validator', array('validate'), array(), '', FALSE);
		$validator->expects($this->once())
			->method('validate')
			->will($this->returnValue($violationList));

		$storageBackendMock = $this->getMock('\Searchperience\Api\Client\System\Storage\RestEnrichmentBackend', array('post'), array(), '', false);

		$this->enrichmentRepository->injectStorageBackend($storageBackendMock);
		$this->enrichmentRepository->injectValidator($validator);
		$this->enrichmentRepository->add($enrichment);
	}
}