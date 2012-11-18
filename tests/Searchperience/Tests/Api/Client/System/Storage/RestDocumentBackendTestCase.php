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

	public function canDeleteDocumentByForeignId() {
		$response = $this->getMock('\Guzzle\Http\Message\Response', array('getBody'), array(), '', FALSE);
		$response->expects($this->once())
			->method('getBody')
			->will($this->returnValue($this->getFixtureContent('Api/Client/System/Storage/Fixture/Qvc_foreignId_12.xml')));

		$request = $this->getMock('\Guzzle\Http\Message\Request', array('send', 'setAuth', 'setBaseUrl'), array(), '', FALSE);
		$request->expects($this->once())
			->method('setAuth')
			->will($this->returnValue($request));
		$request->expects($this->once())
			->method('setBaseUrl')
			->will($this->returnValue($request));
		$request->expects($this->once())
			->method('send')
			->will($this->returnValue($response));

		$restClient = $this->getMock('\Guzzle\Http\Client', array('delete'));
		$restClient->expects($this->once())
			->method('delete')
			->will($this->returnValue($request));

		$this->documentBackend->injectRestClient($restClient);
		$document = $this->documentBackend->getByForeignId(13211);
	}

	/**
	 * @test
	 * @expectedException \Searchperience\Api\Client\System\Exception\UnauthorizedRequestException
	 */
	public function canPostDocument() {
		$this->markTestIncomplete('');

		$restClient = $this->getMock('\Guzzle\Http\Client', array('post'));
		$restClient->expects($this->once())
			->method('post')
			->will($this->throwException(new \Searchperience\Api\Client\System\Exception\UnauthorizedRequestException));
		$this->documentBackend->injectRestClient($restClient);

		$this->documentBackend->post($this->getDocument());
	}
}
