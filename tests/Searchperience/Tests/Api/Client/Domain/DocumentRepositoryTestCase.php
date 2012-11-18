<?php

namespace Searchperience\Tests\Api\Client;

/**
 * @author Michael Klapper <michael.klapper@aoemedia.de>
 * @date 14.11.12
 * @time 15:13
 */
class DocumentRepositoryTestCase extends \Searchperience\Tests\BaseTestCase {

	/**
	 * @var \Searchperience\Api\Client\Domain\DocumentRepository
	 */
	protected $documentRepository;

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
		$this->documentRepository = NULL;
	}

	/**
	 * @test
	 */
	public function getDocumentReturnsValidDomainDocument() {
		$this->documentRepository = $this->getMock('\Searchperience\Api\Client\Domain\DocumentRepository', array('getByForeignId'));
		$this->documentRepository->expects($this->once())
			->method('getByForeignId')
			->will($this->returnValue(new \Searchperience\Api\Client\Domain\Document()));

		$document = $this->documentRepository->getByForeignId(312);
		$this->assertInstanceOf('\Searchperience\Api\Client\Domain\Document', $document);
	}

	/**
	 * @test
	 * @expectedException \Searchperience\Api\Client\System\Exception\UnauthorizedRequestException
	 */
	public function getDocumentWithoutCredentials() {
		$this->documentRepository = $this->getMock('\Searchperience\Api\Client\Domain\DocumentRepository', array('getByForeignId'));
		$this->documentRepository->expects($this->once())
			->method('getByForeignId')
			->will($this->throwException(new \Searchperience\Api\Client\System\Exception\UnauthorizedRequestException));

		$this->documentRepository->getByForeignId(312);
	}

	/**
	 * @test
	 * @expectedException \Searchperience\Api\Client\Domain\Exception\DocumentNotFoundException
	 */
	public function getDocumentByForeignIdThrowsExceptionIfDocumentNotFound() {
		$this->documentRepository = $this->getMock('\Searchperience\Api\Client\Domain\DocumentRepository', array('getByForeignId'));
		$this->documentRepository->expects($this->once())
			->method('getByForeignId')
			->will($this->throwException(new \Searchperience\Api\Client\Domain\Exception\DocumentNotFoundException));

		$this->documentRepository->getByForeignId(312);
	}

	/**
	 * @test
	 * @expectedException \Searchperience\Common\Exception\InvalidArgumentException
	 */
	public function getByForeignIdThrowsInvalidArgumentExceptionOnInvalidArgument() {
		$this->documentRepository = new \Searchperience\Api\Client\Domain\DocumentRepository();
		$this->documentRepository->getByForeignId('');
	}
}
