<?php

namespace Searchperience\Api\Client\Domain\Stopword;

use Searchperience\Api\Client\Domain\AbstractEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Stopword
 * @package Searchperience\Api\Client\Domain\Stopword
 */
class Stopword extends AbstractEntity {

	/**
	 * @var string
	 * @Assert\Length(min = 2, max = 40)
	 * @Assert\NotBlank
	 */
	protected $word = '';

	/**
	 * @var string
	 * @Assert\Length(min = 2, max = 40)
	 * @Assert\NotBlank
	 */
	protected $tagName = '';

	/**
	 * @return string
	 */
	public function getWord() {
		return $this->word;
	}

	/**
	 * @param string $mainWord
	 */
	public function setWord($mainWord) {
		$this->word = $mainWord;
	}

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