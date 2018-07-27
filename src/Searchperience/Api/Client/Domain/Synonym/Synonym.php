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
     * @var int
     */
    protected $id;

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
    protected $language = 'en';

    /**
     * @var string
     */
    protected $mappedWords = '';

    /**
     * @var boolean
     */
    protected $isActive;

    /**
     * @var string
     */
    protected $matchingType = self::TYPE_GROUPING;

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
    public function getMatchingType() {
        return $this->matchingType;
    }

    /**
     * @param string $matchingType
     */
    public function setMatchingType($matchingType) {
        if(!$this->isAllowedType($matchingType)) {
            throw new InvalidArgumentException("Type ".htmlspecialchars($matchingType)." is not supported!");
        }
        $this->matchingType = $matchingType;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
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
    public function getLanguage() {
        return $this->language;
    }

    /**
     * @param string $language
     */
    public function setLanguage($language) {
        $this->language = $language;
    }

    /**
     * @param string $mappedWords
     */
    public function setMappedWords($mappedWords) {
        $this->mappedWords = $mappedWords;
    }

    /**
     * @return string
     */
    public function getMappedWords() {
        return $this->mappedWords;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }
}