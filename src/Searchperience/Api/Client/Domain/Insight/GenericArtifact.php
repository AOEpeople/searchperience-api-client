<?php
/**
 * Created by IntelliJ IDEA.
 * User: pavelbogomolenko
 * Date: 07/07/14
 * Time: 13:20
 * To change this template use File | Settings | File Templates.
 */

namespace Searchperience\Api\Client\Domain\Insight;

use Searchperience\Api\Client\Domain\AbstractEntity;


/**
 * Class GenericArtifact
 * @package Searchperience\Api\Client\Domain\Insight
 */
class GenericArtifact extends AbstractEntity {
	/**
	 * @var string
	 * @Assert\Length(min = 1, max = 255)
	 */
	protected $id;

	/**
	 * @var string
	 * @Assert\Length(min = 1, max = 255)
	 */
	protected $typeName = 'generic';

	/**
	 * @var \stdClass
	 */
	protected $data;

	/**
	 * @param array $data
	 * @return $this
	 */
	public function setData($data) {
		$this->data = $data;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getData() {
		return $this->data;
	}

	/**
	 * @param string $id
	 * @return $this
	 */
	public function setId($id) {
		$this->id = $id;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param string $typeName
	 * @return $this
	 */
	public function setTypeName($typeName) {
		$this->typeName = $typeName;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getTypeName() {
		return $this->typeName;
	}
}