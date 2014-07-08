<?php
/**
 * Created by IntelliJ IDEA.
 * User: pavelbogomolenko
 * Date: 07/07/14
 * Time: 15:46
 * To change this template use File | Settings | File Templates.
 */

namespace Searchperience\Api\Client\System\Storage;

use Searchperience\Api\Client\Domain\Insight\ArtifactType;
use Searchperience\Api\Client\Domain\Insight\ArtifactTypeCollection;


/**
 * Class RestArtifactTypeBackend
 * @package Searchperience\Api\Client\System\Storage
 */
class RestArtifactTypeBackend extends \Searchperience\Api\Client\System\Storage\AbstractRestBackend {

	/**
	 * @return mixed
	 */
	public function getAll() {
		$response = $this->getGetResponseFromEndpoint();
		return $this->buildFromJson($response->json());
	}

	/**
	 * @param mixed $jsonData
	 * @return array|ArtifactTypeCollection
	 */
	protected function buildFromJson($jsonData) {
		$artifactTypeCollection = new ArtifactTypeCollection();

		$types = $jsonData["data"];
		foreach($types as $type) {
			$artifactType = new ArtifactType();
			if(isset($type["name"])) {
				$artifactType->setName($type["name"]);
			}
			$artifactTypeCollection[] = $artifactType;
		}

		return $artifactTypeCollection;
	}
}