<?php
/**
 * Created by IntelliJ IDEA.
 * User: pavelbogomolenko
 * Date: 07/07/14
 * Time: 13:54
 * To change this template use File | Settings | File Templates.
 */

namespace Searchperience\Api\Client\Domain\Insight;
use Searchperience\Api\Client\Domain\AbstractEntity;


/**
 * Class ArtifactType
 * @package Searchperience\Api\Client\Domain\Insight
 */
class ArtifactType extends AbstractEntity {
	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @param string $name
	 * @return $this
	 */
	public function setName($name) {
		$this->name = $name;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}
}