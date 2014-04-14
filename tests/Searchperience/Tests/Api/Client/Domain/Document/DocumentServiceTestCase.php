<?php

namespace Searchperience\Tests\Api\Client\Document;

use Searchperience\Api\Client\Domain\Document\Document;
use Searchperience\Api\Client\Domain\Document\DocumentService;
use Searchperience\Api\Client\Domain\Document\DocumentRepository;


/**
 * @author Timo Schmidt <timo.schmidt@aoe.com>
 * @date 14.04.13
 * @time 17:50
 */
class DocumentServiceTestCase extends \Searchperience\Tests\BaseTestCase {

	/**
	 * @var DocumentService
	 */
	protected $documentService;

	/**
	 * @var
	 */
	protected $documentRepositoryMock;

	/**
	 * @return void
	 */
	public function setUp() {
		$this->documentService = new DocumentService();
		$this->documentRepositoryMock = $this->getMock('Searchperience\Api\Client\Domain\Document\DocumentRepository',array(),array(),'',false);
		$this->documentService->injectDocumentRepository($this->documentRepositoryMock);
	}

	/**
	 * @test
	 */
	public function testNoIndex() {
		$documentMock = $this->getMock('Searchperience\Api\Client\Domain\Document\Document',array(),array(),'',false);
		$documentMock->expects($this->once())->method('setNoIndex');
		$this->documentService->markDocumentForNoIndex($documentMock);
	}

	/**
	 * @test
	 */
	public function testNeedProcessing() {
		$documentMock = $this->getMock('Searchperience\Api\Client\Domain\Document\Document',array(),array(),'',false);
		$documentMock->expects($this->once())->method('setIsMarkedForProcessing');
		$this->documentService->markDocumentForReIndexing($documentMock);
	}

}