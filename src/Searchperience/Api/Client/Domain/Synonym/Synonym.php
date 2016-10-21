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
     * @var array
     * @Assert\NotBlank
     */
    protected $synonyms = array();

    /**
     * @var string
     * @Assert\Length(min = 2, max = 40)
     * @Assert\NotBlank
     */
    protected $tagName = '';

    /**
     * @var array
     */
    protected $mappedWords = array();

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
     * @param string $synonym
     */
    public function addSynonym($synonym) {
        $this->synonyms[$synonym] = $synonym;
    }

    /**
     * @param string $synonym
     */
    public function removeSynonym($synonym) {
        if (isset($this->synonyms[$synonym])) {
            unset($this->synonyms[$synonym]);
        }
    }

    /**
     * @return array
     */
    public function getSynonyms() {
        return $this->synonyms;
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
     * @param string $mappedWord
     */
    public function addMappedWord($mappedWord) {
        $this->mappedWords[$mappedWord] = $mappedWord;
    }

    /**
     * @param string $mappedWord
     */
    public function removeMappedWord($mappedWord) {
        if (isset($this->mappedWords[$mappedWord])) {
            unset($this->mappedWords[$mappedWord]);
        }
    }

    /**
     * @return array
     */
    public function getMappedWords() {
        return $this->mappedWords;
    }
}