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
	protected $language = 'en';

    /**
     * @var int
     */
    protected $id;

    /**
     * @var boolean
     */
    protected $isActive;

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
}