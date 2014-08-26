<?php
/**
 * Created by IntelliJ IDEA.
 * User: pavelbogomolenko
 * Date: 08/07/14
 * Time: 10:09
 * To change this template use File | Settings | File Templates.
 */

namespace Searchperience\Tests\Api\Client\Insight;

use Searchperience\Api\Client\Domain\Insight\ArtifactRepository;
use Searchperience\Api\Client\Domain\Insight\ArtifactCollection;
use Searchperience\Api\Client\Domain\Insight\ArtifactType;
use Searchperience\Api\Client\Domain\Insight\GenericArtifact;
use Searchperience\Api\Client\Domain\Insight\TopsellerArtifact;


/**
 * Class ArtifactRepositoryTestCase
 * @package Searchperience\Tests\Api\Client\Insight
 */
class ArtifactRepositoryTestCase extends \Searchperience\Tests\BaseTestCase {
	/**
	 * @var ArtifactRepository
	 */
	protected $repository;

	/**
	 * Initialize test environment
	 *
	 * @return void
	 */
	public function setUp() {
		$this->repository = new ArtifactRepository();
	}

	/**
	 * Cleanup test environment
	 *
	 * @return void
	 */
	public function tearDown() {
		$this->repository = NULL;
	}

	/**
	 * @test
	 */
	public function canGetAllByType() {
		$artifactType = new ArtifactType();
		$artifactType->setName('topseller');

		$validator = $this->getMock('\Symfony\Component\Validator\Validator', array('validate'), array(), '', FALSE);
		$storageBackend = $this->getMock('\Searchperience\Api\Client\System\Storage\RestArtifactBackend', array('getAllByType'));
		$storageBackend->expects($this->once())
			->method('getAllByType')
			->with($artifactType)
			->will($this->returnValue(new ArtifactCollection()));

		$this->repository->injectStorageBackend($storageBackend);
		$this->repository->setEntityCollection('\Searchperience\Api\Client\Domain\Insight\ArtifactCollection');
		$this->repository->injectValidator($validator);

		$this->repository->getAllByType($artifactType);
	}

	/**
	 * @test
	 */
	public function canGetOneByTypeAndId() {
		$artifactTypeName = 'topseller';
		$artifactId = '1';

		$artifact = new GenericArtifact();
		$artifact->setId($artifactId);
		$artifact->setTypeName($artifactTypeName);

		$validator = $this->getMock('\Symfony\Component\Validator\Validator', array('validate'), array(), '', FALSE);
		$storageBackend = $this->getMock('\Searchperience\Api\Client\System\Storage\RestArtifactBackend', array('getOneByTypeAndId'));
		$storageBackend->expects($this->once())
			->method('getOneByTypeAndId')
			->with($artifactTypeName, $artifactId)
			->will($this->returnValue(new ArtifactCollection()));

		$this->repository->injectStorageBackend($storageBackend);
		$this->repository->setEntityCollection('\Searchperience\Api\Client\Domain\Insight\ArtifactCollection');
		$this->repository->injectValidator($validator);

		$this->repository->getOneByTypeAndId($artifactTypeName, $artifactId);
	}
}