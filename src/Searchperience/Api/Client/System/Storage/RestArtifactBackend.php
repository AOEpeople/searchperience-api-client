<?php
/**
 * Created by IntelliJ IDEA.
 * User: pavelbogomolenko
 * Date: 08/07/14
 * Time: 09:17
 * To change this template use File | Settings | File Templates.
 */

namespace Searchperience\Api\Client\System\Storage;

use Searchperience\Api\Client\Domain\Insight\ArtifactType;
use Searchperience\Api\Client\Domain\Insight\ArtifactCollection;
use Searchperience\Api\Client\Domain\Insight\GenericArtifact;


/**
 * Class RestArtifactBackend
 * @package Searchperience\Api\Client\System\Storage
 */
class RestArtifactBackend extends \Searchperience\Api\Client\System\Storage\AbstractRestBackend {
	/**
	 * @var string
	 */
	protected $endpoint = 'insights';

	/**
	 * @param ArtifactType $artifactType
	 * @return array|ArtifactCollection
	 */
	public function getAllByType(ArtifactType $artifactType) {
		$response = $this->getGetResponseFromEndpoint('/'.$artifactType->getName());
		return $this->buildArtifactListFromJson($response->json());
	}


	/**
	 * @param GenericArtifact $genericArtifact
	 * @return array|ArtifactCollection
	 */
	public function getOne(GenericArtifact $genericArtifact) {
		$response = $this->getGetResponseFromEndpoint('/' . $genericArtifact->getTypeName() . '/' . $genericArtifact->getId());
		return $this->buildArtifactListFromJson($response->json());
	}

	/**
	 * @param mixed $jsonData
	 * @return array|ArtifactCollection
	 * @throws UnexpectedValueException
	 */
	protected function buildArtifactListFromJson($jsonData) {
		$artifactClassName = 'Searchperience\Api\Client\Domain\Insight\GenericArtifact';

		if(isset($jsonData["type"])) {
			$type = $jsonData["type"];
			$artifactClassName = 'Searchperience\Api\Client\Domain\Insight\\' . strtoupper($type) . 'Artifact' ;
			if(! class_exists($artifactClassName)) {
				throw new UnexpectedValueException('Returned type does not have appropriate class to resolve dependency');
			}
		}

		$artifactCollection = new ArtifactCollection();
		$artifacts = $jsonData["data"];
		foreach($artifacts as $artifact) {
			/**
			 * @var $artifactObject GenericArtifact
			 */
			$artifactObject = new $artifactClassName;
			if(isset($artifact["id"])) {
				$artifactObject->setId($artifact["id"]);
			}

			if(isset($artifact["type"])) {
				$artifactObject->setTypeName($artifact["type"]);
			}

			if(isset($artifact["data"])) {
				$artifactObject->setData($artifact["data"]);
			}

			$artifactCollection[] = $artifactObject;
		}

		return $artifactCollection;
	}
}