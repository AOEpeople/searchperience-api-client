<?php

namespace Searchperience\Api\Client\Domain\Enrichment;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Timo Schmidt <timo.schmidt@aoe.com>
 */
class Enrichment {

	/**
	 * @var integer
	 */
	protected $id;

	/**
	 * @var string
	 */
	protected $title;

	/**
	 * @var float
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
	 * Constructor
	 */
	public function __construct() {
		$this->matchingRules 		= new MatchingRuleCollection();
		$this->fieldEnrichments		= new FieldEnrichmentCollection();
	}

	/**
	 * @param float $addBoost
	 */
	public function setAddBoost($addBoost) {
		$this->addBoost = $addBoost;
	}

	/**
	 * @return float
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
}