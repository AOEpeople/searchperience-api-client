<?php

namespace Searchperience\Api\Client\Domain\Enrichment;

use Symfony\Component\Validator\Constraints as Assert;
use Searchperience\Api\Client\Domain\AbstractEntity;

/**
 * @author Nikolay Diaur <nikolay.diaur@aoe.com>
 */
class ContextsBoosting extends AbstractEntity
{

    /**
     * @var string
     */
    protected $boostFieldName;

    /**
     * @var string
     */
    protected $boostFieldValue;

    /**
     * @var string
     */
    protected $boostOptionName;

    /**
     * @var bool
     */
    protected $boostOptionValue;

    /**
     * @var double
     */
    protected $boostingValue;

    /**
     * @return string
     */
    public function getBoostFieldName()
    {
        return $this->boostFieldName;
    }

    /**
     * @param string $boostFieldName
     */
    public function setBoostFieldName($boostFieldName)
    {
        $this->boostFieldName = $boostFieldName;
    }

    /**
     * @return string
     */
    public function getBoostFieldValue()
    {
        return $this->boostFieldValue;
    }

    /**
     * @param string $boostFieldValue
     */
    public function setBoostFieldValue($boostFieldValue)
    {
        $this->boostFieldValue = $boostFieldValue;
    }

    /**
     * @return string
     */
    public function getBoostOptionName()
    {
        return $this->boostOptionName;
    }

    /**
     * @param string $boostOptionName
     */
    public function setBoostOptionName($boostOptionName)
    {
        $this->boostOptionName = $boostOptionName;
    }

    /**
     * @return bool
     */
    public function getBoostOptionValue()
    {
        return $this->boostOptionValue;
    }

    /**
     * @param bool $boostOptionValue
     */
    public function setBoostOptionValue($boostOptionValue)
    {
        $this->boostOptionValue = $boostOptionValue;
    }

    /**
     * @return double
     */
    public function getBoostingValue()
    {
        return $this->boostingValue;
    }

    /**
     * @param double $boostingValue
     */
    public function setBoostingValue($boostingValue)
    {
        $this->boostingValue = $boostingValue;
    }
}