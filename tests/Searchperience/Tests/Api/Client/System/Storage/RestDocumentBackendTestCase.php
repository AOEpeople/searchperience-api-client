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
	 * @return \Searchperience\Api\Client\Domain\Document
	 */
	protected function getDocument($default = array()) {
		$document = new \Searchperience\Api\Client\Domain\Document();

		if (count($default) > 0) {
			foreach ($default as $key => $value) {
				$method = 'set' . ucfirst($key);
				$document->$method($value);
			}
		}

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

		$expectedDocument = $this->getDocument(array(
			'id' => 12,
			'foreignId' => '13211',
			'source' => 'magento',
			'content' => '<xml>some value</xml>',
			'url' => 'http://www.dummy.tld/some/product',
			'generalPriority' => 0,
			'temporaryPriority' => 2,
			'lastProcessing' => '2012-11-14 17:35:03',
			'boostFactor' => 1,
			'noIndex' => 0,
			'isProminent' => 1,
			'isMarkedForProcessing' => 0,
			'mimeType' => 'text/xml'
		));

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
	 * @expectedException \Searchperience\Common\Http\Exception\ClientErrorResponseException
	 */
	public function verifyPostDocumentThrowsClientErrorResponseExceptionWhileInvalidAuthenticationGiven() {
		$restClient = new \Guzzle\Http\Client('http://api.searchperience.com/');
		$mock = new \Guzzle\Plugin\Mock\MockPlugin();
		$mock->addResponse(new \Guzzle\Http\Message\Response(403));
		$restClient->addSubscriber($mock);
		$this->documentBackend->injectRestClient($restClient);
		$this->documentBackend->post($this->getDocument());
	}

	/**
	 * @test
	 */
	public function verifyPostCreateNewDocument() {
		$restClient = new \Guzzle\Http\Client('http://api.searchperience.com/');
		$mock = new \Guzzle\Plugin\Mock\MockPlugin();
		$mock->addResponse(new \Guzzle\Http\Message\Response(201));
		$restClient->addSubscriber($mock);
		$this->documentBackend->injectRestClient($restClient);
		$statusCode = $this->documentBackend->post($this->getDocument());
		$this->assertEquals($statusCode, 201);
	}
}
