<?php

namespace Searchperience\Tests\Api\Client\Document\System\Storage;

use Searchperience\Api\Client\System\Storage\RestStopwordTagBackend;

/**
 * Class RestStopwordTagBackendTestCase
 * @package Searchperience\Tests\Api\Client\Document\System\Storage
 */
class RestStopwordTagBackendTestCase extends \Searchperience\Tests\BaseTestCase {

	/**
	 * @var RestStopwordTagBackend
	 */
	protected $stopwordTagBackend;

	/**
	 * @return void
	 */
	public function setUp() {
		$this->stopwordTagBackend = new RestStopwordTagBackend();
	}

	/**
	 * @test
	 */
	public function test() {
		$restClient = new \Guzzle\Http\Client('http://api.searchperience.com/');
		$mock = new \Guzzle\Plugin\Mock\MockPlugin();
		$mock->addResponse(new \Guzzle\Http\Message\Response(201, NULL, $this->getFixtureContent('Api/Client/System/Storage/Fixture/StopwordTags.xml')));
		$restClient->addSubscriber($mock);

		$this->stopwordTagBackend->injectRestClient($restClient);
		$synonymTags = $this->stopwordTagBackend->getAll();

		$this->assertEquals(3, $synonymTags->getTotalCount(), 'Could not reconstitute synonym collection');
		$this->assertEquals(3, $synonymTags->getCount(), 'Could not get count from synonyms');
		$secondSynonymTag = $synonymTags->offsetGet(1);
		$this->assertSame("de",$secondSynonymTag->getTagName(),'Could not restore synonym tag');
	}

}