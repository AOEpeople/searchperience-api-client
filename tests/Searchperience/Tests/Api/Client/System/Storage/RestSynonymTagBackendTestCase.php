<?php

namespace Searchperience\Tests\Api\Client\Document\System\Storage;

use Searchperience\Api\Client\Domain\Synonym\SynonymTag;
use Searchperience\Api\Client\System\Storage\RestSynonymTagBackend;

/**
 * @author Timo Schmidt
 */
class RestSynonymTagBackendTestCase extends \Searchperience\Tests\BaseTestCase {

	/**
	 * @var RestSynonymTagBackend
	 */
	protected $synonymTagBackend;

	/**
	 * @return void
	 */
	public function setUp() {
		$this->synonymTagBackend = new RestSynonymTagBackend();
	}

	/**
	 * @test
	 */
	public function test() {
		$restClient = new \Guzzle\Http\Client('http://api.searchperience.com/');
		$mock = new \Guzzle\Plugin\Mock\MockPlugin();
		$mock->addResponse(new \Guzzle\Http\Message\Response(201, NULL, $this->getFixtureContent('Api/Client/System/Storage/Fixture/SynonymTags.xml')));
		$restClient->addSubscriber($mock);

		$this->synonymTagBackend->injectRestClient($restClient);
		$synonymTags = $this->synonymTagBackend->getAll();

		$this->assertEquals(3, $synonymTags->getTotalCount(), 'Could not reconstitute synonym collection');
		$this->assertEquals(3, $synonymTags->getCount(), 'Could not get count from synonyms');
		$secondSynonymTag = $synonymTags->offsetGet(1);
		$this->assertSame("de",$secondSynonymTag->getTagName(),'Could not restore synonym tag');
	}

}