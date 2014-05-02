<?php

namespace Searchperience\Tests\Api\Client\Document\System\Storage;
use Searchperience\Api\Client\Domain\UrlQueueItem\UrlQueueItem;

/**
 * @author Timo Schmidt <timo.schmidt@aoe.com>
‚ */
class RestDocumentStatusBackendTestCase extends \Searchperience\Tests\BaseTestCase {

	/**
	 * @var \Searchperience\Api\Client\System\Storage\RestUrlQueueStatusBackend
	 */
	protected $documentStatusBackend = null;

	/**
	 * Initialize test environment
	 *
	 * @return void
	 */
	public function setUp() {
		$this->documentStatusBackend = new \Searchperience\Api\Client\System\Storage\RestDocumentStatusBackend();
	}

	/**
	 * @test
	 */
	public function canBuildUrlQueueItemFromSingleXMLResponse() {
		$restClient = new \Guzzle\Http\Client('http://api.searchperience.com/');
		$mock = new \Guzzle\Plugin\Mock\MockPlugin();
		$mock->addResponse(new \Guzzle\Http\Message\Response(201, NULL, $this->getFixtureContent('Api/Client/System/Storage/Fixture/DocumentStatus.xml')));
		$restClient->addSubscriber($mock);

		$this->documentStatusBackend->injectRestClient($restClient);
			/** @var $documentStatus \Searchperience\Api\Client\Domain\Document\DocumentStatus */
		$documentStatus = $this->documentStatusBackend->get();

		$this->assertEquals(2,$documentStatus->getErrorCount(),'Could not reconstitude the error count from the response xml');
		$this->assertEquals(25,$documentStatus->getAllCount(),'Could not reconstitude the error count from the response xml');
		$this->assertEquals(10,$documentStatus->getWaitingCount(),'Could not reconstitude the error count from the response xml');
		$this->assertEquals(12,$documentStatus->getProcessingCount(),'Could not reconstitude the error count from the response xml');
		$this->assertEquals(3,$documentStatus->getProcessedCount(),'Could not reconstitude the error count from the response xml');
		$this->assertEquals(1,$documentStatus->getDeletedCount(),'Could not reconstitude the error count from the response xml');
	}
}