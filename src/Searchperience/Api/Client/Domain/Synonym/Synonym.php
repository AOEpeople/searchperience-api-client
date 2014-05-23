<?php

namespace Searchperience\Api\Client\Domain\Synonym;

use Searchperience\Api\Client\Domain\AbstractEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Synonym
 * @package Searchperience\Api\Client\Domain\Synonym
 */
class Synonym extends AbstractEntity {

	/**
	 * @var string
	 * @Assert\Length(min = 3, max = 40)
	 * @Assert\NotBlank
	 */
	protected $mainWord = '';

	/**
	 * @var string
	 * @Assert\Length(min = 3, max = 40)
	 * @Assert\NotBlank
	 */
	protected $tagName = '';

	/**
	 * @var array
	 */
	protected $wordsWithSameMeaning = array();

	/**
	 * @return string
	 */
	public function getMainWord() {
		return $this->mainWord;
	}

	/**
	 * @param string $mainWord
	 */
	public function setMainWord($mainWord) {
		$this->mainWord = $mainWord;
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

	/**
	 * @param string $wordWithSameMeaning
	 */
	public function addWordWithSameMeaning($wordWithSameMeaning) {
		$this->wordsWithSameMeaning[$wordWithSameMeaning] = $wordWithSameMeaning;
	}

	/**
	 * @param string $wordWithSameMeaning
	 */
	public function removeWordWithSameMeaning($wordWithSameMeaning) {
		if(isset($this->wordsWithSameMeaning[$wordWithSameMeaning])) {
			unset($this->wordsWithSameMeaning[$wordWithSameMeaning]);
		}
	}

	/**
	 * @return array
	 */
	public function getWordsWithSameMeaning() {
		return $this->wordsWithSameMeaning;
	}
}