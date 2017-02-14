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
	public function canBuilDocumentItemFromSingleXMLResponse() {
		$restClient = new \Guzzle\Http\Client('http://api.searchperience.com/');
		$mock = new \Guzzle\Plugin\Mock\MockPlugin();
		$mock->addResponse(new \Guzzle\Http\Message\Response(201, NULL, $this->getFixtureContent('Api/Client/System/Storage/Fixture/DocumentStatus.xml')));
		$restClient->addSubscriber($mock);

		$this->documentStatusBackend->injectRestClient($restClient);
			/** @var $documentStatus \Searchperience\Api\Client\Domain\Document\DocumentStatus */
		$documentStatus = $this->documentStatusBackend->get();

		$this->assertEquals(25,$documentStatus->getErrorCount(),'Could not reconstitude the error count from the response xml');
		$this->assertEquals(26,$documentStatus->getAllCount(),'Could not reconstitude the document count from the response xml');
		$this->assertEquals(10,$documentStatus->getWaitingCount(),'Could not reconstitude the waiting count from the response xml');
		$this->assertEquals(100,$documentStatus->getProcessingCount(),'Could not reconstitude the processing count from the response xml');
		$this->assertEquals(5,$documentStatus->getProcessedCount(),'Could not reconstitude the processed count from the response xml');
		$this->assertEquals(1,$documentStatus->getDeletedCount(),'Could not reconstitude the deleted count from the response xml');
		$this->assertEquals(43,$documentStatus->getProcessingCountLongerThan90Minutes(),'Could not reconstitude the processing longer than 90 minutes count from the response xml');
		$this->assertEquals(11,$documentStatus->getProcessedCountLast60Minutes(),'Could not reconstitude the processed last 60 minutes count from the response xml');
		$this->assertEquals(12,$documentStatus->getProcessedCountLast24Hours(),'Could not reconstitude the processed last 24 hours count from the response xml');
		$this->assertEquals(1486993801,$documentStatus->getLastProcessedDate(),'Could not reconstitude the last processed date from the response xml');
		$this->assertEquals(4,$documentStatus->getErrorCountLast60Minutes(),'Could not reconstitude the errors last 60 minutes count from the response xml');
		$this->assertEquals(13,$documentStatus->getErrorCountLast24Hours(),'Could not reconstitude the errors last 24 hours count from the response xml');
		$this->assertEquals(65,$documentStatus->getMarkedAsHiddenCount(),'Could not reconstitude the hidden count from the response xml');
		$this->assertEquals(33,$documentStatus->getMarkedAsHiddenCountByUser(),'Could not reconstitude the hidden by user count from the response xml');
		$this->assertEquals(32,$documentStatus->getMarkedAsHiddenCountInternal(),'Could not reconstitude the hidden internally count from the response xml');
	}
}
