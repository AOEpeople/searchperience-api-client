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
	 * @return string
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @return string
	 */
	public function getUrl() {
		return $this->url;
	}
}