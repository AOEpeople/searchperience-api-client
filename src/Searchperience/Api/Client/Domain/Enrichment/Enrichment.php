<?php

namespace Searchperience\Api\Client\Domain\Enrichment;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Timo Schmidt <timo.schmidt@aoe.com>
 */
class Enrichment {

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
}