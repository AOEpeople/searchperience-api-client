<?php

namespace Searchperience\Api\Client\Domain\AdminSearch;

use Searchperience\Api\Client\Domain\AbstractEntity;

/**
 * Business object that holds the search page information
 *
 * Class AdminSearch
 *
 * @package Searchperience\Api\Client\Domain\AdminSearch
 * @author Michael Klapper <michael.klappper@aoe.com>
 */
class AdminSearch extends AbstractEntity {

	/**
	 * @var string
	 */
	protected $id;

	/**
	 * Title assigned to the admin search instance
	 *
	 * @var string
	 */
	protected $title;

	/**
	 * Description assigned to the admin search instance
	 *
	 * @var string
	 */
	protected $description;

	/**
	 * Admin search instance url
	 *
	 * @var string
	 */
	protected $url;

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
	 * @param string $description
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @param string $title
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @param string $url
	 */
	public function setUrl($url) {
		$this->url = $url;
	}

	/**
	 * @return string
	 */
	public function getUrl() {
		return $this->url;
	}
}