<?php

namespace Searchperience\Tests\Api\Client\Document\System\Storage;

use Searchperience\Api\Client\System\Storage\RestEnrichmentBackend;

/**
 * @author Timo Schmidt
 * @date 14.11.12
 * @time 15:17
 */
class RestEnrichmentBackendTestCase extends \Searchperience\Tests\BaseTestCase {

	/**
	 * @var RestEnrichmentBackend
	 */
	protected $enrichmentBackend;

	/**
	 * @return void
	 */
	public function setUp() {
		$this->enrichmentBackend = new RestEnrichmentBackend();
	}

	/**
	 * @test
	 */
	public function testCanReconstituteEnrichmetnCollection() {
		$restClient = new \Guzzle\Http\Client('http://api.searchperience.com/');
		$mock = new \Guzzle\Plugin\Mock\MockPlugin();
		$mock->addResponse(new \Guzzle\Http\Message\Response(201, NULL, $this->getFixtureContent('Api/Client/System/Storage/Fixture/Enrichment1.xml')));
		$restClient->addSubscriber($mock);

		$this->enrichmentBackend->injectRestClient($restClient);
		$enrichment = $this->enrichmentBackend->getById(1);
		$this->assertEquals($enrichment->getFieldEnrichments()->getCount(),2,'Could not reconstitude field enrichments');
		$this->assertEquals($enrichment->getMatchingRules()->getCount(),1,'Could not reconstitude matching rules');
		$this->assertEquals($enrichment->getAddBoost(),2315.22,'Could not reconstitude add boost');
		$this->assertEquals($enrichment->getTitle(),'my enrichment','Could not reconstitude title');
	}
}