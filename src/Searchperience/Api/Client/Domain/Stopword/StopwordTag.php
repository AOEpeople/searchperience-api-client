<?php

namespace Searchperience\Api\Client\Domain\Stopword;

use Searchperience\Api\Client\Domain\AbstractEntity;

/**
 * Class StopwordTag
 * @package Searchperience\Api\Client\Domain\Stopword
 */
class StopwordTag extends AbstractEntity {

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