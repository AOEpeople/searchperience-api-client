<?php

namespace Searchperience\Api\Client\Domain\Enrichment;

use Symfony\Component\Validator\Constraints as Assert;
use Searchperience\Api\Client\Domain\AbstractEntity;

/**
 * @author Timo Schmidt <timo.schmidt@aoe.com>
 */
class Enrichment extends AbstractEntity{

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
	 * @var array
	 */
	protected $matchingRules = null;

	/**
	 * @var array
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
	 * @var array
	 */
	protected static $allowedMatchingRulesCombinationTypes = array(
		Enrichment::MATCH_ALL, Enrichment::MATCH_ATLEASTONE, Enrichment::MATCH_NONE
	);

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->matchingRules 		= new MatchingRuleCollection();
		$this->fieldEnrichments		= new FieldEnrichmentCollection();
	}

	/**
	 * Should be a float with %
	 *
	 * eg: 90.00%
	 *
	 * @param string $addBoost
	 */
	public function setAddBoost($addBoost) {
		$this->addBoost = $addBoost;
	}

	/**
	 * @return string
	 */
	public function getAddBoost() {
		return $this->addBoost;
	}

	/**
	 * @param int $id
	 */
	public function setId($id) {
		$this->id = $id;
	}

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param string $title
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @param string $description
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @param boolean $enabled
	 */
	public function setEnabled($enabled) {
		$this->enabled = $enabled;
	}

	/**
	 * @return boolean
	 */
	public function getEnabled() {
		return $this->enabled;
	}

	/**
	 * @param FieldEnrichment $fieldEnrichment
	 * @return Enrichment
	 */
	public function addFieldEnrichment(FieldEnrichment $fieldEnrichment) {
		$this->fieldEnrichments[] = $fieldEnrichment;

		return $this;
	}

	/**
	 * @return FieldEnrichmentCollection
	 */
	public function getFieldEnrichments() {
		return $this->fieldEnrichments;
	}

	/**
	 * @param MatchingRule $matchingRule
	 * @return Enrichment
	 */
	public function addMatchingRule(MatchingRule $matchingRule) {
		$this->matchingRules[] = $matchingRule;

		return $this;
	}

	/**
	 * @return MatchingRuleCollection
	 */
	public function getMatchingRules() {
		return $this->matchingRules;
	}

	/**
	 * @param string $matchingRulesCombinationType
	 * @throws \Searchperience\Common\Exception\InvalidArgumentException
	 */
	public function setMatchingRulesCombinationType($matchingRulesCombinationType) {
		if(!self::isAllowedMatchingRuleCombinationType($matchingRulesCombinationType)) {
			throw new \Searchperience\Common\Exception\InvalidArgumentException("Invalid matchingRulesCombinationType: ".htmlspecialchars($matchingRulesCombinationType));
		}

		$this->matchingRulesCombinationType = $matchingRulesCombinationType;
	}

	/**
	 * @return string
	 */
	public function getMatchingRulesCombinationType() {
		return $this->matchingRulesCombinationType;
	}

	/**
	 * @param string $matchingRulesCombinationType
	 * @return bool
	 */
	public static function isAllowedMatchingRuleCombinationType($matchingRulesCombinationType) {
		return in_array($matchingRulesCombinationType, self::$allowedMatchingRulesCombinationTypes);
	}

	/**
	 * @param boolean $matchingRulesExpectedResult
	 */
	public function setMatchingRulesExpectedResult($matchingRulesExpectedResult) {
		$this->matchingRulesExpectedResult = $matchingRulesExpectedResult;
	}

	/**
	 * @return boolean
	 */
	public function getMatchingRulesExpectedResult() {
		return $this->matchingRulesExpectedResult;
	}

	/**
	 * Keywords seperated by whitespace.
	 *
	 * @param string
	 */
	public function setLowBoostedKeywords($value) {
		$this->setBoostWordsByFieldName('lowboostw', $value);
	}

	/**
	 * Get low boosted keywords for enrichment.
	 *
	 * @return string
	 */
	public function getLowBoostedKeywords() {
		return $this->getBoostWordsByFieldName('lowboostw');
	}

	/**
	 * Keywords seperated by whitespace.
	 *
	 * @param string
	 */
	public function setNormalBoostedKeywords($value) {
		$this->setBoostWordsByFieldName('normalboostw', $value);
	}

	/**
	 * Get normal boosted keywords for enrichment.
	 *
	 * @return string
	 */
	public function getNormalBoostedKeywords() {
		return $this->getBoostWordsByFieldName('normalboostw');
	}

	/**
	 * Keywords seperated by whitespace.
	 *
	 * @param string
	 */
	public function setHighBoostedKeywords($value) {
		$this->setBoostWordsByFieldName('highboostw', $value);
	}

	/**
	 * Get high boosted keywords for enrichment.
	 *
	 * @return string
	 */
	public function getHighBoostedKeywords() {
		return $this->getBoostWordsByFieldName('highboostw');
	}

	/**
	 * Get field enrichment by field name.
	 *
	 * @param string $fieldName
	 * @return string
	 */
	protected function getBoostWordsByFieldName($fieldName) {
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
	protected function setBoostWordsByFieldName($fieldName, $value) {
		$fieldEnrichment = new FieldEnrichment();
		$fieldEnrichment->setFieldName($fieldName);
		$fieldEnrichment->setContent($value);

		$this->addFieldEnrichment($fieldEnrichment);
	}
}