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
     * @Assert\NotBlank
     */
    protected $synonyms;

    /**
     * @var string
     * @Assert\Length(min = 2, max = 40)
     * @Assert\NotBlank
     */
    protected $tagName = '';

    /**
     * @var string
     */
    protected $mappedWords = '';

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
     * @param string $synonyms
     */
    public function setSynonyms($synonyms) {
        $this->synonyms = $synonyms;
    }

    /**
     * @return string
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
     * @param string $mappedWords
     */
    public function setMappedWords($mappedWords) {
        $this->mappedWords = $mappedWords;
    }

    /**
     * @return array
     */
    public function getMappedWords() {
        return $this->mappedWords;
    }
}