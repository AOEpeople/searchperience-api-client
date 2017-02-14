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

		$this->assertEquals(25,$documentStatus->getErrorCount(),'Could not reconstitute the error count from the response xml');
		$this->assertEquals(26,$documentStatus->getAllCount(),'Could not reconstitute the document count from the response xml');
		$this->assertEquals(10,$documentStatus->getWaitingCount(),'Could not reconstitute the waiting count from the response xml');
		$this->assertEquals(100,$documentStatus->getProcessingCount(),'Could not reconstitute the processing count from the response xml');
		$this->assertEquals(5,$documentStatus->getProcessedCount(),'Could not reconstitute the processed count from the response xml');
		$this->assertEquals(1,$documentStatus->getDeletedCount(),'Could not reconstitute the deleted count from the response xml');
		$this->assertEquals(43,$documentStatus->getProcessingCountLongerThan90Minutes(),'Could not reconstitute the processing longer than 90 minutes count from the response xml');
		$this->assertEquals(11,$documentStatus->getProcessedCountLast60Minutes(),'Could not reconstitute the processed last 60 minutes count from the response xml');
		$this->assertEquals(12,$documentStatus->getProcessedCountLast24Hours(),'Could not reconstitute the processed last 24 hours count from the response xml');
		$this->assertEquals(1486993801,$documentStatus->getLastProcessedDate(),'Could not reconstitute the last processed date from the response xml');
		$this->assertEquals(4,$documentStatus->getErrorCountLast60Minutes(),'Could not reconstitute the errors last 60 minutes count from the response xml');
		$this->assertEquals(13,$documentStatus->getErrorCountLast24Hours(),'Could not reconstitute the errors last 24 hours count from the response xml');
		$this->assertEquals(65,$documentStatus->getMarkedAsHiddenCount(),'Could not reconstitute the hidden count from the response xml');
		$this->assertEquals(33,$documentStatus->getMarkedAsHiddenCountByUser(),'Could not reconstitute the hidden by user count from the response xml');
		$this->assertEquals(32,$documentStatus->getMarkedAsHiddenCountInternal(),'Could not reconstitute the hidden internally count from the response xml');
	}
}
