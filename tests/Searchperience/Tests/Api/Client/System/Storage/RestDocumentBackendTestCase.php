<?php

namespace Searchperience\Tests\Api\Client\System\Storage;

/**
 * @author Michael Klapper <michael.klapper@aoemedia.de>
 * @date 14.11.12
 * @time 15:17
 */
class RestDocumentBackendTestCase extends \Searchperience\Tests\BaseTestCase {

	/**
	 * @var \Searchperience\Api\Client\System\Storage\RestDocumentBackend
	 */
	protected $documentBackend;

	/**
	 * Initialize test environment
	 *
	 * @return void
	 */
	public function setUp() {
		$this->documentBackend = new \Searchperience\Api\Client\System\Storage\RestDocumentBackend();
	}

	/**
	 * Cleanup test environment
	 *
	 * @return void
	 */
	public function tearDown() {
		$this->backend = NULL;
	}

	/**
	 * Get a dummy Document
	 *
	 * @param array $default
	 */
	protected function getDocument($default = array(
		'content'           => '',
		'foreignId'         => '',
		'generalPriority'   => '',
		'temporaryPriority' => '',
		'source'            => '',
		'url'               => '',
		'mimeType'          => '',
	)) {
		$document = new \Searchperience\Api\Client\Domain\Document();
		$document->setContent($default['content']);
		$document->setForeignId($default['foreignId']);
		$document->setGeneralPriority($default['generalPriority']);
		$document->setTemporaryPriority($default['temporaryPriority']);
		$document->setSource($default['source']);
		$document->setUrl($default['url']);
		$document->setMimeType($default['mimeType']);
		return $document;
	}

	/**
	 * @test
	 */
	public function canGetDocumentByDocumentForeignId() {
		$restClient = new \Guzzle\Http\Client('http://api.searchperience.com/');
		$mock = new \Guzzle\Plugin\Mock\MockPlugin();
		$mock->addResponse(new \Guzzle\Http\Message\Response(201, NULL, $this->getFixtureContent('Api/Client/System/Storage/Fixture/Qvc_foreignId_12.xml')));
		$restClient->addSubscriber($mock);

		$this->documentBackend->injectRestClient($restClient);
		$document = $this->documentBackend->getByForeignId(13211);

		$expectedDocument = new \Searchperience\Api\Client\Domain\Document();
		$expectedDocument->setId(12);
		$expectedDocument->setForeignId(13211);
		$expectedDocument->setContent('<xml>some value</xml>');
		$expectedDocument->setUrl('http://www.dummy.tld/some/product');
		$expectedDocument->setForeignId(13211);
		$expectedDocument->setGeneralPriority(0);
		$expectedDocument->setTemporaryPriority(2);
		$expectedDocument->setLastProcessing('2012-11-14 17:35:03');
		$expectedDocument->setBoostFactor(1);
		$expectedDocument->setNoIndex(0);
		$expectedDocument->setIsProminent(0);
		$expectedDocument->setIsMarkedForProcessing(0);

		$this->assertInstanceOf('\Searchperience\Api\Client\Domain\Document', $document);
		$this->assertEquals($expectedDocument, $document);
	}

	/**
	 * @test
	 */
	public function canDeleteDocumentByForeignId() {
		$restClient = new \Guzzle\Http\Client('http://api.searchperience.com/');
		$mock = new \Guzzle\Plugin\Mock\MockPlugin();
		$mock->addResponse(new \Guzzle\Http\Message\Response(200));
		$restClient->addSubscriber($mock);

		$this->documentBackend->injectRestClient($restClient);
		$this->assertEquals(200, $this->documentBackend->deleteByForeignId(13211));
	}

	/**
	 * @test
	 * @expectedException \Guzzle\Http\Exception\ClientErrorResponseException
	 */
	public function verifyPostDocumentThrowsClientErrorResponseExceptionWhileInvalidAuthenticationGiven() {
		$restClient = new \Guzzle\Http\Client('http://api.searchperience.com/');
		$mock = new \Guzzle\Plugin\Mock\MockPlugin();
		$mock->addResponse(new \Guzzle\Http\Message\Response(403));
		$restClient->addSubscriber($mock);
		$this->documentBackend->injectRestClient($restClient);
		$this->documentBackend->post($this->getDocument());
	}
}
