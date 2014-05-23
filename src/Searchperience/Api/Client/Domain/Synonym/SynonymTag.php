<?php

namespace Searchperience\Api\Client\Domain\Synonym;

use Searchperience\Api\Client\Domain\AbstractEntity;

/**
 * Class Synonym
 * @package Searchperience\Api\Client\Domain\Synonym
 */
class SynonymTag extends AbstractEntity {

	/**
	 * @var string
	 */
	protected $tagName = 'default';

	/**
	 * @return string
	 */
	public function getTagName() {
		return $this->tagName;
	}

	/**
	 * @param string $tagName
	 */
	public function setTagName($tagName) {
		$this->tagName = $tagName;
	}
}