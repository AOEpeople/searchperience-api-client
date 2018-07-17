<?php

namespace Searchperience\Tests\Api\Client\Document\System\Storage;

use Searchperience\Api\Client\Domain\Filters\FilterCollection;
use Searchperience\Api\Client\Domain\Document\Document;

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
		$this->documentBackend->injectDateTimeService(new \Searchperience\Api\Client\System\DateTime\DateTimeService());
	}

	/**
	 * Cleanup test environment
	 *
	 * @return void
	 */
	public function tearDown() {
		$this->documentBackend = NULL;
	}

	/**
	 * Get a dummy Document
	 *
	 * @param array $default
	 * @return \Searchperience\Api\Client\Domain\Document\Document
	 */
	protected function getDocument($default = array()) {
		$document = new \Searchperience\Api\Client\Domain\Document\Document();

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


		$this->assertEquals('2012-11-14 17:35:03',$expectedDocument->getLastProcessing());

		/**
		 * @var $lastProcessingDate \DateTime
		 */
		$lastProcessingDate = $document->getLastProcessingDate();
		$this->assertEquals($lastProcessingDate->format('Y'),'2012');

		$this->assertInstanceOf('\Searchperience\Api\Client\Domain\Document\Document', $document);
		$this->assertEquals($expectedDocument, $document);
	}

	/**
	 * @test
	 */
	public function canGetDocumentByDocumentUrl() {
		$restClient = new \Guzzle\Http\Client('http://api.searchperience.com/');
		$mock = new \Guzzle\Plugin\Mock\MockPlugin();
		$mock->addResponse(new \Guzzle\Http\Message\Response(201, NULL, $this->getFixtureContent('Api/Client/System/Storage/Fixture/Qvc_foreignId_12.xml')));
		$restClient->addSubscriber($mock);

		$this->documentBackend->injectRestClient($restClient);
		$document = $this->documentBackend->getByUrl('http://www.dummy.tld/some/product');

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

		$this->assertInstanceOf('\Searchperience\Api\Client\Domain\Document\Document', $document);
		$this->assertEquals($expectedDocument, $document);
	}

	/**
	 * @test
	 */
	public function canGetDocumentByDyUrlAnReconstitudeNoIndex() {
		$restClient = new \Guzzle\Http\Client('http://api.searchperience.com/');
		$mock = new \Guzzle\Plugin\Mock\MockPlugin();
		$mock->addResponse(new \Guzzle\Http\Message\Response(201, NULL, $this->getFixtureContent('Api/Client/System/Storage/Fixture/Qvc_foreignId_12_noIndexTrue.xml')));
		$restClient->addSubscriber($mock);

		$this->documentBackend->injectRestClient($restClient);
		$document = $this->documentBackend->getByUrl('http://www.dummy.tld/some/product');

		$time = new \DateTime('2016-11-28 23:30:42', new \DateTimeZone('UTC'));

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
			'noIndex' => 1,
			'isProminent' => 1,
			'isMarkedForProcessing' => 0,
			'mimeType' => 'text/xml',
			'createdAt' => $time,
			'updatedAt' => $time
		));

		$this->assertInstanceOf('\Searchperience\Api\Client\Domain\Document\Document', $document);
		$this->assertEquals($expectedDocument, $document);
		$this->assertEquals($document->getCreatedAt()->getTimestamp(), $time->getTimestamp());
	}

	/**
	 * @test
	 */
	public function canGetDocumentByDyUrlAnReconstitudePageRank() {
		$restClient = new \Guzzle\Http\Client('http://api.searchperience.com/');
		$mock = new \Guzzle\Plugin\Mock\MockPlugin();
		$mock->addResponse(new \Guzzle\Http\Message\Response(201, NULL, $this->getFixtureContent('Api/Client/System/Storage/Fixture/Qvc_foreignId_12_withPageRank.xml')));
		$restClient->addSubscriber($mock);

		$this->documentBackend->injectRestClient($restClient);
		$document = $this->documentBackend->getByUrl('http://www.dummy.tld/some/product');

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
			'noIndex' => 1,
			'isProminent' => 1,
			'isMarkedForProcessing' => 0,
			'mimeType' => 'text/xml',
			'pageRank' => 5.55,
			'solrCoreHints' => ''
		));

		$this->assertInstanceOf('\Searchperience\Api\Client\Domain\Document\Document', $document);
		$this->assertEquals(5.55, $document->getPageRank(),'Page rank was not reconstituted as expected');
		$this->assertEquals($expectedDocument, $document);
	}

	/**
	 * @test
	 */
	public function canReconstitutePromotionFromXmlResponse() {
		$restClient = new \Guzzle\Http\Client('http://api.searchperience.com/');
		$mock = new \Guzzle\Plugin\Mock\MockPlugin();
		$promotionXml = $this->getFixtureContent('Api/Client/System/Storage/Fixture/Promotion.xml');
		$mock->addResponse(new \Guzzle\Http\Message\Response(201, NULL, $promotionXml));
		$restClient->addSubscriber($mock);

		$this->documentBackend->injectRestClient($restClient);
			/** @var $promotion \Searchperience\Api\Client\Domain\Document\Promotion */
		$promotion = $this->documentBackend->getByUrl('http://www.dummy.tld/some/product');

		$dom = new \DOMDocument();
		$dom->loadXML($promotionXml);

		$xpath = new \DOMXPath($dom);
		$node = $xpath->query('//document/content');
		$inputPromotionXml = '<?xml version="1.0" encoding="UTF-8"?>'.(string) $node->item(0)->textContent;

		$this->assertInstanceOf('\Searchperience\Api\Client\Domain\Document\Promotion',$promotion);
		$this->assertEquals('backend',$promotion->getSource());
		$this->assertEquals('http://www.foobar.de/test.gif',$promotion->getImageUrl());
		$this->assertEquals('organic',$promotion->getPromotionType());
		$this->assertEquals($this->cleanSpaces($inputPromotionXml),$this->cleanSpaces($promotion->getContent()),'Could not initialize and persist from promotion');

		//can we create a DOMDocument from the promotion content
		$dom = new \DOMDocument();
		$dom->loadXML($promotion->getPromotionContent());
		$xpath = new \DOMXPath($dom);
		$this->assertEquals('test test',(string)$xpath->query('//body')->item(0)->textContent,'could not get expected body content from promotion html');
	}

	/**
	 * @test
	 */
	public function canGetAllDocuments() {
		$restClient = new \Guzzle\Http\Client('http://api.searchperience.com/');
		$mock = new \Guzzle\Plugin\Mock\MockPlugin();
		$mock->addResponse(new \Guzzle\Http\Message\Response(201, NULL, $this->getFixtureContent('Api/Client/System/Storage/Fixture/Qvc_foreignId_12.xml')));
		$restClient->addSubscriber($mock);

		$this->documentBackend->injectRestClient($restClient);

		$filtersCollection = new \Searchperience\Api\Client\Domain\Filters\FilterCollection();
		$documents = $this->documentBackend->getAllByFilterCollection(0, 10, $filtersCollection);

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

		$this->assertInstanceOf('\Searchperience\Api\Client\Domain\Document\DocumentCollection', $documents);
		$this->assertInstanceOf('\Searchperience\Api\Client\Domain\Document\Document', $documents[0]);

		$this->assertEquals($expectedDocument, $documents[0]);
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
	 */
	public function canDeleteByUrl() {
		$expectedUrl =  '/{customerKey}/documents?url='.urlencode('http://www.google.de/');
		$responseMock = $this->getMockBuilder('\Guzzle\Http\Message\Response')->setMethods(array('getStatusCode'))->setConstructorArgs(array())->setMockClassName('')->disableOriginalConstructor(false)->getMock();
		$responseMock->expects($this->once())->method('getStatusCode')->will($this->returnValue(200));

		$requestMock = $this->getMockBuilder('\Guzzle\Http\Message\Request')->setMethods(array('setAuth','send'))->setConstructorArgs(array())->setMockClassName('')->disableOriginalConstructor(false)->getMock();
		$requestMock->expects($this->once())->method('setAuth')->will($this->returnCallback(function () use ($requestMock) {
			return $requestMock;
		}));
		$requestMock->expects($this->once())->method('send')->will($this->returnCallback(function () use ($responseMock) {
			return $responseMock;
		}));

		$restClient = $this->getMockBuilder('\Guzzle\Http\Client')->setMethods(array('delete','setAuth','send'))->setConstructorArgs(array('http://api.searcperience.com/'))->setMockClassName('')->disableOriginalConstructor(false)->getMock();
		$restClient->expects($this->once())->method('delete')->with($expectedUrl)->will($this->returnCallback(function() use ($requestMock) {
			return $requestMock;
		}));

		$this->documentBackend->injectRestClient($restClient);
		$this->assertEquals(200, $this->documentBackend->deleteByUrl('http://www.google.de/'));
	}

	/**
	 * @test
	 */
	public function canDeleteDocumentBySource() {
		$restClient = new \Guzzle\Http\Client('http://api.searchperience.com/');
		$mock = new \Guzzle\Plugin\Mock\MockPlugin();
		$mock->addResponse(new \Guzzle\Http\Message\Response(200));
		$restClient->addSubscriber($mock);

		$this->documentBackend->injectRestClient($restClient);
		$this->assertEquals(200, $this->documentBackend->deleteBySource("magento"));
	}

	/**
	 * @test
	 * @expectedException \Searchperience\Common\Http\Exception\ForbiddenException
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

	/**
	 * @test
	 */
	public function canGetAllByFiltersTriggersExpectedBackendUrl() {
		$filters = array(
						'crawl' => array(
							'crawlStart' => $this->getUTCDateTimeObject('2014-01-03 10:00:00'),
							'crawlEnd' => $this->getUTCDateTimeObject('2014-01-03 10:00:00')
						),
						'source' => array('source' => 'magento'),
						'query' => array('queryString' => 'test', 'queryFields' => 'id,url'),
						'boostFactor' => array('boostFactorEnd' => 123.00),
						'pageRank' => array('pageRankStart' => 0.00, 'pageRankEnd' => 123.00),
						'lastProcessed' => array(
							'processStart' => $this->getUTCDateTimeObject('2014-01-01 10:00:00'),
							'processEnd' => $this->getUTCDateTimeObject('2014-01-03 10:00:00')
						),
						'notifications' => array(
							'notifications' => array(Document::IS_DUPLICATE,Document::IS_ERROR,Document::IS_PROCESSING)
						),
		);

		$expectedUrl = '/{customerKey}/documents?start=0&limit=10&'.
						'crawlStart=2014-01-03%2010%3A00%3A00&'.
						'crawlEnd=2014-01-03%2010%3A00%3A00&'.
						'source=magento&query=test&'.
						'queryFields=id%2Curl&'.
						'boostFactorEnd=123&'.
						'pageRankEnd=123&'.
						'processStart=2014-01-01%2010%3A00%3A00&'.
						'processEnd=2014-01-03%2010%3A00%3A00&'.
						'isDuplicate=1&hasError=1&processingThreadIdStart=1&processingThreadIdEnd=65536&isDeleted=0';

		$responsetMock = $this->getMockBuilder('\Guzzle\Http\Message\Response')->setMethods(array('xml'))->setConstructorArgs(array())->setMockClassName('')->disableOriginalConstructor(false)->getMock();
		$responsetMock->expects($this->once())->method('xml')->will($this->returnValue(new \SimpleXMLElement('<?xml version="1.0"?><documents></documents>')));

		$resquestMock = $this->getMockBuilder('\Guzzle\Http\Message\Request')->setMethods(array('setAuth','send'))->setConstructorArgs(array())->setMockClassName('')->disableOriginalConstructor(false)->getMock();
		$resquestMock->expects($this->once())->method('setAuth')->will($this->returnCallback(function () use ($resquestMock) {
			return $resquestMock;
		}));
		$resquestMock->expects($this->once())->method('send')->will($this->returnCallback(function () use ($responsetMock) {
			return $responsetMock;
		}));

		$restClient = $this->getMockBuilder('\Guzzle\Http\Client')->setMethods(array('get','setAuth','send'))->setConstructorArgs(array('http://api.searcperience.com/'))->setMockClassName('')->disableOriginalConstructor(false)->getMock();
		$restClient->expects($this->once())->method('get')->with($expectedUrl)->will($this->returnCallback(function() use ($resquestMock) {
			return $resquestMock;
		}));


		$mock = new \Guzzle\Plugin\Mock\MockPlugin();
		$mock->addResponse(new \Guzzle\Http\Message\Response(201, NULL, $this->getFixtureContent('Api/Client/System/Storage/Fixture/Qvc_foreignId_12.xml')));
		$restClient->addSubscriber($mock);

		$this->documentBackend->injectRestClient($restClient);

		$filterCollectionFactory = new \Searchperience\Api\Client\Domain\Document\Filters\FilterCollectionFactory();
		$filterCollection = $filterCollectionFactory->createFromFilterArguments($filters);
		$this->documentBackend->getAllByFilterCollection(0, 10, $filterCollection);
	}

	/**
	 * @test
	 */
	public function sortingIsPassedToRestBackend() {
		$expectedUrl = '/{customerKey}/documents?start=0&limit=10&sortingField=foo&sortingType=DESC';

		$responsetMock = $this->getMockBuilder('\Guzzle\Http\Message\Response')->setMethods(array('xml'))->setConstructorArgs(array())->setMockClassName('')->disableOriginalConstructor(false)->getMock();
		$responsetMock->expects($this->once())->method('xml')->will($this->returnValue(new \SimpleXMLElement('<?xml version="1.0"?><documents></documents>')));
		
		$resquestMock = $this->getMockBuilder('\Guzzle\Http\Message\Request')->setMethods(array('setAuth','send'))->setConstructorArgs(array())->setMockClassName('')->disableOriginalConstructor(false)->getMock();
		$resquestMock->expects($this->once())->method('setAuth')->will($this->returnCallback(function () use ($resquestMock) {
			return $resquestMock;
		}));
		$resquestMock->expects($this->once())->method('send')->will($this->returnCallback(function () use ($responsetMock) {
			return $responsetMock;
		}));

		$restClient = $this->getMockBuilder('\Guzzle\Http\Client')->setMethods(array('get','setAuth','send'))->setConstructorArgs(array('http://api.searcperience.com/'))->setMockClassName('')->disableOriginalConstructor(false)->getMock();
		$restClient->expects($this->once())->method('get')->with($expectedUrl)->will($this->returnCallback(function() use ($resquestMock) {
			return $resquestMock;
		}));


		$mock = new \Guzzle\Plugin\Mock\MockPlugin();
		$mock->addResponse(new \Guzzle\Http\Message\Response(201, NULL, $this->getFixtureContent('Api/Client/System/Storage/Fixture/Qvc_foreignId_12.xml')));
		$restClient->addSubscriber($mock);

		$this->documentBackend->injectRestClient($restClient);
		$this->documentBackend->getAllByFilterCollection(0, 10, new FilterCollection(),'foo','DESC');
	}

	/**
	 * @test
	 * @expectedException \Searchperience\Common\Exception\InvalidArgumentException
	 */
	public function invalidSortingThrowsException() {
		$this->documentBackend->getAllByFilterCollection(0, 10, new FilterCollection(),'foo','Foo');
	}

	/**
	 * @test
	 */
	public function canGetClassNameForPromotionMimeType() {
		$className = $this->documentBackend->getClassNameForMimeType('text/searchperiencepromotion+xml');
		$this->assertEquals('\Searchperience\Api\Client\Domain\Document\Promotion',$className,'Retrieve unexpected classname for promotion');
	}

	/**
	 * @test
	 */
	public function canGetDefaultClassName() {
		$className = $this->documentBackend->getClassNameForMimeType('foobar');
		$this->assertEquals('\Searchperience\Api\Client\Domain\Document\Document',$className,'Can not retrieve default classname');
	}

	/**
	 * @test
	 */
	public function getByIdsReturnsNullForEmptyResponse() {
		$restClient = $this->getMockedRestClientWith404Response();
		$this->documentBackend->injectRestClient($restClient);
		$document = $this->documentBackend->getById('32');
		$this->assertNull($document,'Get by id did not return null for unexisting entity');
	}

	/**
	 * @test
	 */
	public function getByForeignIdsReturnsNullForEmptyResponse() {
		$restClient = $this->getMockedRestClientWith404Response();
		$this->documentBackend->injectRestClient($restClient);
		$document = $this->documentBackend->getByForeignId('32');
		$this->assertNull($document,'Get by foreignId did not return null for unexisting entity');
	}


	/**
	 * @test
	 */
	public function getByUrlReturnsNullForEmptyResponse() {
		$restClient = $this->getMockedRestClientWith404Response();
		$this->documentBackend->injectRestClient($restClient);
		$document = $this->documentBackend->getByUrl('http://foo');
		$this->assertNull($document,'Get by url did not return null for unexisting entity');
	}

}
