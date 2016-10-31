<?php

namespace Searchperience\Api\Client\Domain\Enrichment;

use Symfony\Component\Validator\Constraints as Assert;
use Searchperience\Api\Client\Domain\AbstractEntity;

/**
 * @author Timo Schmidt <timo.schmidt@aoe.com>
 */
class Enrichment extends AbstractEntity
{

    const MATCH_ALL = 'all';
    const MATCH_ATLEASTONE = 'one';
    const MATCH_NONE = 'none';

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $addBoost;

    /**
     * @var boolean
     */
    protected $enabled;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var MatchingRuleCollection
     */
    protected $matchingRules = null;

    /**
     * @var FieldEnrichmentCollection
     */
    protected $fieldEnrichments = null;

    /**
     * @var string
     */
    protected $matchingRulesCombinationType = self::MATCH_ALL;

    /**
     * @var bool
     */
    protected $matchingRulesExpectedResult = TRUE;

    /**
     * @var ContextsBoostingCollection
     */
    protected $contextsBoosting = null;

    /**
     * @var array
     */
    protected static $allowedMatchingRulesCombinationTypes = array(
        Enrichment::MATCH_ALL, Enrichment::MATCH_ATLEASTONE, Enrichment::MATCH_NONE
    );

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->matchingRules = new MatchingRuleCollection();
        $this->fieldEnrichments = new FieldEnrichmentCollection();
        $this->contextsBoosting = new ContextsBoostingCollection();
    }

    /**
     * Should be a float with %
     *
     * eg: 90.00%
     *
     * @param string $addBoost
     */
    public function setAddBoost($addBoost)
    {
        $this->addBoost = $addBoost;
    }

    /**
     * @return string
     */
    public function getAddBoost()
    {
        return $this->addBoost;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param boolean $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param FieldEnrichment $fieldEnrichment
     * @return Enrichment
     */
    public function addFieldEnrichment(FieldEnrichment $fieldEnrichment)
    {
        $this->fieldEnrichments[] = $fieldEnrichment;

        return $this;
    }


    /**
     * @param string $fieldName
     * @return bool
     */
    public function hasFieldEnrichmentForFieldName($fieldName)
    {
        $result = false;

        if (!$this->fieldEnrichments instanceof FieldEnrichmentCollection) {
            return $result;
        }

        foreach ($this->fieldEnrichments as $key => $fieldEnrichment) {
            /** @var $fieldEnrichment FieldEnrichment */
            if ($fieldEnrichment->getFieldName() === $fieldName) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $fieldName
     * @return void
     */
    public function removeFieldEnrichmentsForFieldName($fieldName)
    {
        $keysToRemove = array();
        $iterator = $this->fieldEnrichments->getIterator();
        foreach ($iterator as $key => $fieldEnrichment) {
            /** @var $fieldEnrichment FieldEnrichment */
            if ($fieldEnrichment->getFieldName() === $fieldName) {
                $keysToRemove[] = $key;
            }
        }

        foreach ($keysToRemove as $keyToRemove) {
            $iterator->offsetUnset($keyToRemove);
        }
    }

    /**
     * @return FieldEnrichmentCollection
     */
    public function getFieldEnrichments()
    {
        return $this->fieldEnrichments;
    }

    /**
     * @param MatchingRule $matchingRule
     * @return Enrichment
     */
    public function addMatchingRule(MatchingRule $matchingRule)
    {
        $this->matchingRules[] = $matchingRule;

        return $this;
    }

    /**
     * @return MatchingRuleCollection
     */
    public function getMatchingRules()
    {
        return $this->matchingRules;
    }

    /**
     * @param string $matchingRulesCombinationType
     * @throws \Searchperience\Common\Exception\InvalidArgumentException
     */
    public function setMatchingRulesCombinationType($matchingRulesCombinationType)
    {
        if (!self::isAllowedMatchingRuleCombinationType($matchingRulesCombinationType)) {
            throw new \Searchperience\Common\Exception\InvalidArgumentException("Invalid matchingRulesCombinationType: " . htmlspecialchars($matchingRulesCombinationType));
        }

        $this->matchingRulesCombinationType = $matchingRulesCombinationType;
    }

    /**
     * @return string
     */
    public function getMatchingRulesCombinationType()
    {
        return $this->matchingRulesCombinationType;
    }

    /**
     * @param string $matchingRulesCombinationType
     * @return bool
     */
    public static function isAllowedMatchingRuleCombinationType($matchingRulesCombinationType)
    {
        return in_array($matchingRulesCombinationType, self::$allowedMatchingRulesCombinationTypes);
    }

    /**
     * @param boolean $matchingRulesExpectedResult
     */
    public function setMatchingRulesExpectedResult($matchingRulesExpectedResult)
    {
        $this->matchingRulesExpectedResult = $matchingRulesExpectedResult;
    }

    /**
     * @return boolean
     */
    public function getMatchingRulesExpectedResult()
    {
        return $this->matchingRulesExpectedResult;
    }

    /**
     * Keywords seperated by whitespace.
     *
     * @param string
     */
    public function setLowBoostedKeywords($value)
    {
        $this->setBoostWordsByFieldName('lowboostw', $value);
    }

    /**
     * Get low boosted keywords for enrichment.
     *
     * @return string
     */
    public function getLowBoostedKeywords()
    {
        return $this->getBoostWordsByFieldName('lowboostw');
    }

    /**
     * Keywords seperated by whitespace.
     *
     * @param string
     */
    public function setNormalBoostedKeywords($value)
    {
        $this->setBoostWordsByFieldName('normalboostw', $value);
    }

    /**
     * Get normal boosted keywords for enrichment.
     *
     * @return string
     */
    public function getNormalBoostedKeywords()
    {
        return $this->getBoostWordsByFieldName('normalboostw');
    }

    /**
     * Keywords seperated by whitespace.
     *
     * @param string
     */
    public function setHighBoostedKeywords($value)
    {
        $this->setBoostWordsByFieldName('highboostw', $value);
    }

    /**
     * Get high boosted keywords for enrichment.
     *
     * @return string
     */
    public function getHighBoostedKeywords()
    {
        return $this->getBoostWordsByFieldName('highboostw');
    }

    /**
     * Get field enrichment by field name.
     *
     * @param string $fieldName
     * @return string
     */
    protected function getBoostWordsByFieldName($fieldName)
    {
        $boostWords = '';
        $fieldEnrichmentCollection = $this->getFieldEnrichments();

        foreach ($fieldEnrichmentCollection as $fieldEnrichment) {
            /** @var $fieldEnrichment FieldEnrichment */
            if ($fieldEnrichment->getFieldName() === $fieldName) {
                $boostWords = $fieldEnrichment->getContent();
                break;
            }
        }

        return $boostWords;
    }

    /**
     * Set field enrichment by field name
     *
     * @param string $fieldName
     * @param $value
     */
    protected function setBoostWordsByFieldName($fieldName, $value)
    {
        //if we have allready enrichments for this fieldName we remove them
        //and create a new fieldEnrichment right away
        if ($this->hasFieldEnrichmentForFieldName($fieldName)) {
            $this->removeFieldEnrichmentsForFieldName($fieldName);
        }

        //when we set the boostword fieldValue to an empty value, we semantically remove the field enrichment
        $wasFieldReset = trim($value) == '';
        if ($wasFieldReset) {
            return;
        }

        $fieldEnrichment = new FieldEnrichment();
        $fieldEnrichment->setFieldName($fieldName);
        $fieldEnrichment->setContent($value);

        $this->addFieldEnrichment($fieldEnrichment);
    }

    /**
     * @return ContextsBoostingCollection
     */
    public function getContextsBoosting()
    {
        return $this->contextsBoosting;
    }

    /**
     * @param ContextsBoosting $contextsBoosting
     * @return Enrichment
     */
    public function addContextsBoosting(ContextsBoosting $contextsBoosting)
    {
        $this->contextsBoosting[] = $contextsBoosting;

        return $this;
    }

}