<?php

namespace Searchperience\Tests\Api\Client\Document\System\Storage;
use Searchperience\Api\Client\Domain\UrlQueueItem\UrlQueueItem;

/**
 * @author Timo Schmidt <timo.schmidt@aoe.com>
 * @author Nikolay Diaur <nikolay.diaur@aoe.com>
 */
class RestUrlQueueItemBackendTestCase extends \Searchperience\Tests\BaseTestCase {

	/**
	 * @var \Searchperience\Api\Client\System\Storage\RestUrlQueueItemBackend
	 */
	protected $urlQueueItemBackend = null;

	/**
	 * Initialize test environment
	 *
	 * @return void
	 */
	public function setUp() {
		$this->urlQueueItemBackend = new \Searchperience\Api\Client\System\Storage\RestUrlQueueItemBackend();
		$this->urlQueueItemBackend->injectDateTimeService(new \Searchperience\Api\Client\System\DateTime\DateTimeService());
	}

	/**
	 * @test
	 */
	public function canBuildUrlQueueItemFromSingleXMLResponse() {
		$restClient = new \Guzzle\Http\Client('http://api.searchperience.com/');
		$mock = new \Guzzle\Plugin\Mock\MockPlugin();
		$mock->addResponse(new \Guzzle\Http\Message\Response(201, NULL, $this->getFixtureContent('Api/Client/System/Storage/Fixture/UrlqueueItem1.xml')));
		$restClient->addSubscriber($mock);

		$this->urlQueueItemBackend->injectRestClient($restClient);
			/** @var $urlQueueItem \Searchperience\Api\Client\Domain\Document\UrlQueueItem */
		$urlQueueItem = $this->urlQueueItemBackend->getByDocumentId(2223232);
		$this->assertEquals('http://google.com',$urlQueueItem->getUrl(),'Could not build url queue item with url from xml response');
		$this->assertEquals(3,$urlQueueItem->getFailCount(),'Could not restore failcount from xml');
		$this->assertEquals(4, $urlQueueItem->getPriority(), 'Could not restore priority from xml');
		$this->assertEquals('Whatever',$urlQueueItem->getLastError(),'Could not restore lasterror from xml');
		$this->assertEquals(1, $urlQueueItem->getProcessingThreadId(), 'Could not restore processingthreadid from xml');
		$this->assertInstanceOf('DateTime',$urlQueueItem->getProcessingStartTime(),'Urlqueue backend did not create date time from response');
		$this->assertEquals('2012-11-20 12:05:07', $urlQueueItem->getProcessingStartTime()->format('Y-m-d H:i:s'), 'Urlqueue backend did not create date time from response');
	}

	/**
	 * @test
	 */
	public function canBuildMultipleQueueItemsFromXmlRepsonse() {

		$restClient = new \Guzzle\Http\Client('http://api.searchperience.com/');
		$mock = new \Guzzle\Plugin\Mock\MockPlugin();
		$mock->addResponse(new \Guzzle\Http\Message\Response(201, NULL, $this->getFixtureContent('Api/Client/System/Storage/Fixture/UrlqueueItem2.xml')));
		$restClient->addSubscriber($mock);

		$this->urlQueueItemBackend->injectRestClient($restClient);
		$urlQueueItems = $this->urlQueueItemBackend->getAllByFilterCollection(0,10);
		$this->assertEquals(2, $urlQueueItems->getTotalCount());

		$this->assertEquals('http://aoe.com', $urlQueueItems[1]->getUrl(), 'Could not build url queue item with url from xml response');
		$this->assertEquals(3, $urlQueueItems[1]->getPriority(), 'Could not build url queue item with priority from xml response');
		$this->assertEquals('', $urlQueueItems[1]->getLastError(), 'Could not build url queue item with last error from xml response');
	}

	/**
	 *
	 * @test
	 */
	public function canPostDocument() {
		$this->urlQueueItemBackend = $this->getMockBuilder('\Searchperience\Api\Client\System\Storage\RestUrlQueueItemBackend')->setMethods(array('executePostRequest'))->getMock();
		$this->urlQueueItemBackend->injectDateTimeService(new \Searchperience\Api\Client\System\DateTime\DateTimeService());

		$restClient = new \Guzzle\Http\Client('http://api.searchperience.com/');
		$mock = new \Guzzle\Plugin\Mock\MockPlugin();
		$mock->addResponse(new \Guzzle\Http\Message\Response(201));
		$restClient->addSubscriber($mock);
		$this->urlQueueItemBackend->injectRestClient($restClient);

		$expectsArgumentsArray = Array(
				'deleted' => 0,
				'documentId' => '111',
				'priority' => 3,
				'url' => 'http://aoe.com'
		);
		$this->urlQueueItemBackend->expects($this->once())->method('executePostRequest')->with($expectsArgumentsArray)->will(
			$this->returnValue($this->createMock('\Guzzle\Http\Message\Response',array(),array(),'',false))
		);
		$this->urlQueueItemBackend->post($this->getTestUrlQueueItem());
	}

	/**
	 * Creates a dummy url queue item.
	 *
	 * @return UrlQueueItem
	 */
	protected function getTestUrlQueueItem(){
		$urlQueueItem = new UrlQueueItem();

		$urlQueueItem->setDocumentId('111');
		$urlQueueItem->setFailCount(1);
		$urlQueueItem->setDeleted(0);
		$urlQueueItem->setPriority(3);
		$urlQueueItem->setLastError('last error');
		$urlQueueItem->setProcessingThreadId(1);
		$date = \DateTime::createFromFormat("Y-m-d H:i:s", '2012-11-15 00:05:07', new \DateTimeZone("UTC"));
		$urlQueueItem->setProcessingStartTime($date);
		$urlQueueItem->setUrl('http://aoe.com');

		return $urlQueueItem;
	}

	/**
	 * @test
	 */
	public function canDeleteUrlQueueItemByDocumentId() {
		$expectedUrl =  '/{customerKey}/urlqueueitems/4711';
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

		$this->urlQueueItemBackend->injectRestClient($restClient);
		$this->assertEquals(200, $this->urlQueueItemBackend->deleteByDocumentId(4711));
	}

	/**
	 * @test
	 */
	public function canDeleteUrlQueueItemByUrl() {       
		$expectedUrl =  '/{customerKey}/urlqueueitems?url='.rawurlencode('http://www.google.de/');
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

		$this->urlQueueItemBackend->injectRestClient($restClient);
		$this->assertEquals(200, $this->urlQueueItemBackend->deleteByUrl('http://www.google.de/'));
	}


	/**
	 * @test
	 */
	public function getByUrlReturnsNullForEmptyResponse() {
		$restClient = $this->getMockedRestClientWith404Response();
		$this->urlQueueItemBackend->injectRestClient($restClient);
		$urlQueueItem = $this->urlQueueItemBackend->getByUrl('http://foo');
		$this->assertNull($urlQueueItem,'Get by url did not return null for unexisting entity');
	}

	/**
	 * @test
	 */
	public function getByDocumentIdNothingForEmptyResponse() {
		$restClient = $this->getMockedRestClientWith404Response();
		$this->urlQueueItemBackend->injectRestClient($restClient);
		$urlQueueItem = $this->urlQueueItemBackend->getByDocumentId(1234);
		$this->assertNull($urlQueueItem,'Get by documentId did not return null for unexisting entity');
	}
}