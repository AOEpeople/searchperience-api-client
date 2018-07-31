<?php

namespace Searchperience\Api\Client\Domain\Settings;

use Searchperience\Api\Client\Domain\AbstractEntity;

class Language extends AbstractEntity {

    /**
     * @var string $name
     */
    protected $name;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}