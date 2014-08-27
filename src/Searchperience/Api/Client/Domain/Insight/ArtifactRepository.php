<?php
/**
 * Created by IntelliJ IDEA.
 * User: pavelbogomolenko
 * Date: 07/07/14
 * Time: 13:23
 * To change this template use File | Settings | File Templates.
 */

namespace Searchperience\Api\Client\Domain\Insight;

use Searchperience\Api\Client\Domain\AbstractEntity;
use Searchperience\Api\Client\Domain\AbstractRepository;
use Searchperience\Api\Client\Domain\Insight\ArtifactType;
use Searchperience\Api\Client\Domain\Insight\GenericArtifact;
use Searchperience\Api\Client\Domain\Insight\ArtifactCollection;

/**
 * Class ArtifactRepository
 * @package Searchperience\Api\Client\Domain\Insight
 */
class ArtifactRepository extends AbstractRepository {
	/**
	 * @param ArtifactType $artifactType
	 * @return ArtifactCollection
	 */
	public function getAllByType(ArtifactType $artifactType) {
		$violations = $this->validator->validate($artifactType);

		$artifactCollection = $this->storageBackend->getAllByType($artifactType);
		return $this->decorateAll($artifactCollection);
	}

	/**
	 * @param GenericArtifact $genericArtifact
	 * @return ArtifactCollection
	 */
	public function getOne(GenericArtifact $genericArtifact) {
		$violations = $this->validator->validate($genericArtifact);

		return $this->getOneByTypeAndId($genericArtifact->getTypeName(), $genericArtifact->getId());
	}

	/**
	 * @param string $artifactTypeName
	 * @param string $artifactId
	 * @return \Searchperience\Api\Client\Domain\AbstractEntityCollection
	 */
	public function getOneByTypeAndId($artifactTypeName, $artifactId) {
		$artifactCollection = $this->storageBackend->getOneByTypeAndId($artifactTypeName, $artifactId);
		return $this->decorateAll($artifactCollection);
	}
}