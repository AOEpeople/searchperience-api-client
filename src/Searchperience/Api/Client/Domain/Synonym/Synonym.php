<?php

namespace Searchperience\Api\Client\Domain\Synonym;

use Searchperience\Api\Client\Domain\AbstractEntity;
use Searchperience\Common\Exception\InvalidArgumentException;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Synonym
 * @package Searchperience\Api\Client\Domain\Synonym
 * @author Timo Schmidt <timo.schmidt@aoe.com>
 */
class Synonym extends AbstractEntity {

	const TYPE_MAPPING = 'mapping';

	const TYPE_GROUPING = 'grouping';


	/**
	 * @var string
	 * @Assert\Length(min = 2, max = 40)
	 * @Assert\NotBlank
	 */
	protected $mainWord = '';

	/**
	 * @var string
	 * @Assert\Length(min = 2, max = 40)
	 * @Assert\NotBlank
	 */
	protected $tagName = '';

	/**
	 * @var array
	 */
	protected $wordsWithSameMeaning = array();

	/**
	 * @var string
	 */
	protected $type = self::TYPE_GROUPING;

	/**
	 * @var array
	 */
	protected static $allowedTypes = array(
		self::TYPE_MAPPING,
		self::TYPE_GROUPING
	);

	/**
	 * @param string $matchingType
	 * @return bool
	 */
	public static function isAllowedType($matchingType) {
		return in_array($matchingType,self::$allowedTypes);
	}

	/**
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @param string $type
	 */
	public function setType($type) {
		if(!$this->isAllowedType($type)) {
			throw new InvalidArgumentException("Type ".htmlspecialchars($type)." is not supported!");
		}
		$this->type = $type;
	}

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