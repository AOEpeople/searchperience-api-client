<?php
/**
 * Created by IntelliJ IDEA.
 * User: pavelbogomolenko
 * Date: 07/07/14
 * Time: 13:57
 * To change this template use File | Settings | File Templates.
 */

namespace Searchperience\Api\Client\Domain\Insight;

use Searchperience\Api\Client\Domain\AbstractRepository;
use Searchperience\Api\Client\Domain\Insight\ArtifactTypeCollection;

/**
 * Class ArtifactTypeRepository
 * @package Searchperience\Api\Client\Domain\Insight
 */
class ArtifactTypeRepository extends AbstractRepository {
	/**
	 * get all artifact types
	 *
	 * @return ArtifactTypeCollection
	 */
	public function getAll() {
		$artifactTypes = $this->storageBackend->getAll();
		return $this->decorateAll($artifactTypes);
	}
}