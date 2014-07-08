<?php
/**
 * Created by IntelliJ IDEA.
 * User: pavelbogomolenko
 * Date: 08/07/14
 * Time: 14:38
 * To change this template use File | Settings | File Templates.
 */

namespace Searchperience\Tests\Api\Client\Document\System\Storage;

use Searchperience\Api\Client\System\Storage\RestArtifactTypeBackend;
use Searchperience\Api\Client\Domain\Insight\ArtifactTypeCollection;
use Searchperience\Api\Client\Domain\Insight\ArtifactType;


/**
 * Class RestArtifactTypeBackendTestCase
 * @package Searchperience\Tests\Api\Client\Document\System\Storage
 */
class RestArtifactTypeBackendTestCase extends \Searchperience\Tests\BaseTestCase {

	/**
	 * @var RestArtifactTypeBackend
	 */
	protected $artifactTypeBackend;

	/**
	 * Initialize test environment
	 *
	 * @return void
	 */
	public function setUp() {
		$this->artifactTypeBackend = new RestArtifactTypeBackend();
	}

	/**
	 * Cleanup test environment
	 *
	 * @return void
	 */
	public function tearDown() {
		$this->artifactTypeBackend = NULL;
	}

	/**
	 * @test
	 */
	public function canGetAllArtifactType() {
		$restClient = new \Guzzle\Http\Client('http://api.searchperience.me/qvc/');
		$mock = new \Guzzle\Plugin\Mock\MockPlugin();
		$mock->addResponse(new \Guzzle\Http\Message\Response(201, NULL, $this->getFixtureContent('Api/Client/System/Storage/Fixture/artifact_types.json')));
		$restClient->addSubscriber($mock);

		$this->artifactTypeBackend->injectRestClient($restClient);

		$expectedArtifactTypeCollection = new ArtifactTypeCollection();

		$artifactType1 = new ArtifactType();
		$artifactType1->setName("topseller");

		$expectedArtifactTypeCollection->append($artifactType1);

		$artifactType2 = new ArtifactType();
		$artifactType2->setName("recommendation");

		$expectedArtifactTypeCollection->append($artifactType2);

		$actualArtifactTypeCollection = $this->artifactTypeBackend->getAll();

		$this->assertEquals($expectedArtifactTypeCollection, $actualArtifactTypeCollection);
	}
}