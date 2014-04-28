<?php

namespace Searchperience\Tests\Api\Client\Document\System\Storage;
use Searchperience\Api\Client\Domain\UrlQueueItem\UrlQueueItem;

/**
 * @author Timo Schmidt <timo.schmidt@aoe.com>
‚ */
class RestUrlQueueStatusBackendTestCase extends \Searchperience\Tests\BaseTestCase {

	/**
	 * @var \Searchperience\Api\Client\System\Storage\RestUrlQueueStatusBackend
	 */
	protected $urlQueueStatusBackend = null;

	/**
	 * Initialize test environment
	 *
	 * @return void
	 */
	public function setUp() {
		$this->urlQueueStatusBackend = new \Searchperience\Api\Client\System\Storage\RestUrlQueueStatusBackend();
	}

	/**
	 * @test
	 */
	public function canBuildUrlQueueItemFromSingleXMLResponse() {
		$restClient = new \Guzzle\Http\Client('http://api.searchperience.com/');
		$mock = new \Guzzle\Plugin\Mock\MockPlugin();
		$mock->addResponse(new \Guzzle\Http\Message\Response(201, NULL, $this->getFixtureContent('Api/Client/System/Storage/Fixture/UrlqueueStatus.xml')));
		$restClient->addSubscriber($mock);

		$this->urlQueueStatusBackend->injectRestClient($restClient);
			/** @var $urlQueueItem \Searchperience\Api\Client\Domain\UrlQueueItem\UrlQueueStatus */
		$urlQueueStatus = $this->urlQueueStatusBackend->get();

		$this->assertEquals(4,$urlQueueStatus->getErrorCount(),'Could not reconstitude the error count from the response xml');
		$this->assertEquals(5,$urlQueueStatus->getAllCount(),'Could not reconstitude the error count from the response xml');
		$this->assertEquals(3,$urlQueueStatus->getWaitingCount(),'Could not reconstitude the error count from the response xml');
		$this->assertEquals(2,$urlQueueStatus->getProcessingCount(),'Could not reconstitude the error count from the response xml');
		$this->assertEquals(2,$urlQueueStatus->getDeletedCount(),'Could not reconstitude the error count from the response xml');
	}
}