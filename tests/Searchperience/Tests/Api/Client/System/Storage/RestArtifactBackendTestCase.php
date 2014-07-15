<?php
/**
 * Created by IntelliJ IDEA.
 * User: pavelbogomolenko
 * Date: 08/07/14
 * Time: 12:07
 * To change this template use File | Settings | File Templates.
 */

namespace Searchperience\Tests\Api\Client\Document\System\Storage;

use Searchperience\Api\Client\System\Storage\RestArtifactBackend;
use Searchperience\Api\Client\Domain\Insight\ArtifactType;
use Searchperience\Api\Client\Domain\Insight\GenericArtifact;
use Searchperience\Api\Client\Domain\Insight\TopsellerArtifact;
use Searchperience\Api\Client\Domain\Insight\ArtifactCollection;


/**
 * Class RestArtifactBackendTestCase
 * @package Searchperience\Tests\Api\Client\Document\System\Storage
 */
class RestArtifactBackendTestCase extends \Searchperience\Tests\BaseTestCase {

	/**
	 * @var RestArtifactBackend
	 */
	protected $artifactBackend;

	/**
	 * Initialize test environment
	 *
	 * @return void
	 */
	public function setUp() {
		$this->artifactBackend = new RestArtifactBackend();
	}

	/**
	 * Cleanup test environment
	 *
	 * @return void
	 */
	public function tearDown() {
		$this->artifactBackend = NULL;
	}

	/**
	 * @test
	 */
	public function canGetAllByType() {
		$restClient = new \Guzzle\Http\Client('http://api.searchperience.me/qvc/');
		$mock = new \Guzzle\Plugin\Mock\MockPlugin();
		$mock->addResponse(new \Guzzle\Http\Message\Response(201, NULL, $this->getFixtureContent('Api/Client/System/Storage/Fixture/artifacts.json')));
		$restClient->addSubscriber($mock);

		$this->artifactBackend->injectRestClient($restClient);

		$expectedArtifactCollection = new ArtifactCollection();

		$expectedArtifact1 = new GenericArtifact();
		$expectedArtifact1->setId("1");
		$expectedArtifact1->setTypeName("topseller");

		$expectedArtifactCollection->append($expectedArtifact1);

		$expectedArtifact2 = new GenericArtifact();
		$expectedArtifact2->setId("2");
		$expectedArtifact2->setTypeName("topseller");

		$expectedArtifactCollection->append($expectedArtifact2);


		$artifactType = new ArtifactType();
		$artifactType->setName("topseller");
		$actualArtifacts = $this->artifactBackend->getAllByType($artifactType);

		$this->assertEquals($expectedArtifactCollection, $actualArtifacts);
	}

	/**
	 * @test
	 */
	public function canGetOneArtifact() {
		$restClient = new \Guzzle\Http\Client('http://api.searchperience.me/qvc/');
		$mock = new \Guzzle\Plugin\Mock\MockPlugin();
		$mock->addResponse(new \Guzzle\Http\Message\Response(201, NULL, $this->getFixtureContent('Api/Client/System/Storage/Fixture/topseller_artifact.json')));
		$restClient->addSubscriber($mock);

		$this->artifactBackend->injectRestClient($restClient);

		$expectedArtifactCollection = new ArtifactCollection();

		$expectedArtifact = new TopsellerArtifact();
		$expectedArtifact->setData(array("2121121", "2121"));

		$expectedArtifactCollection->append($expectedArtifact);

		$genericArtifact = new GenericArtifact();
		$genericArtifact->setId("1");
		$genericArtifact->setTypeName("topseller");

		$actualArtifactCollection = $this->artifactBackend->getOne($genericArtifact);
		$this->assertEquals($expectedArtifactCollection, $actualArtifactCollection);
	}

	/**
	 * @test
	 */
	public function getByUrlReturnsNullForEmptyResponse() {
		$restClient = $this->getMockedRestClientWith404Response();
		$this->artifactBackend->injectRestClient($restClient);
		$artifact = $this->artifactBackend->getOneByTypeAndId('topseller',1);
		$this->assertNull($artifact,'Get one by type and did not return null for unexisting entity');
	}
}