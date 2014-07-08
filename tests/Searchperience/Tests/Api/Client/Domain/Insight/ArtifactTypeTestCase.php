<?php
/**
 * Created by IntelliJ IDEA.
 * User: pavelbogomolenko
 * Date: 07/07/14
 * Time: 16:16
 * To change this template use File | Settings | File Templates.
 */

namespace Searchperience\Tests\Api\Client\Insight;

use Searchperience\Api\Client\Domain\Insight\ArtifactType;


/**
 * Class ArtifactTypeTestCase
 * @package Searchperience\Tests\Api\Client\Insight
 */
class ArtifactTypeTestCase extends \Searchperience\Tests\BaseTestCase {
	/**
	 * @var ArtifactType
	 */
	protected $entity;

	/**
	 * Initialize test environment
	 *
	 * @return void
	 */
	public function setUp() {
		$this->entity = new ArtifactType();
	}

	/**
	 * Cleanup test environment
	 *
	 * @return void
	 */
	public function tearDown() {
		$this->entity = null;
	}

	/**
	 * @test
	 */
	public function verifyGetterAndSetter() {
		$this->entity->setName('topseller');

		$this->assertEquals($this->entity->getName(), 'topseller');
	}
}