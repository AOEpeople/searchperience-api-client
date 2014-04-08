<?php
/**
 * @author: Timo Schmidt <timo.schmidt@aoe.com>
 * @Date: 2/25/14
 * @Time: 3:59 PM
 */

namespace Searchperience\Api\Client\Domain\Filters;
use Searchperience\Api\Client\Domain\Enrichment\Enrichment;
use Searchperience\Api\Client\Domain\Enrichment\FieldEnrichment;
use Searchperience\Api\Client\Domain\Enrichment\MatchingRule;

/**
 * Class EnrichmentTestCase
 * @package Searchperience\Api\Client\Domain\Filters
 */
class EnrichmentTestCase extends \Searchperience\Tests\BaseTestCase {

	/**
	 * @var Enrichment
	 */
	protected $enrichment;

	/**
	 * @return void
	 */
	public function setUp() {
		$this->enrichment = new Enrichment();
	}

	/**
	 * @test
	 */
	public function canBuildAggregate() {
		$matchingRuleA = new MatchingRule();
		$matchingRuleA->setFieldName('brand');
		$matchingRuleA->setOperator(MatchingRule::OPERATOR_EQUALS);
		$matchingRuleA->setOperatorValue('aoe');

		$matchingRuleB = new MatchingRule();
		$matchingRuleB->setFieldName('brand');
		$matchingRuleB->setOperator(MatchingRule::OPERATOR_CONTAINSNOT);
		$matchingRuleB->setOperatorValue('nasa');

		$fieldEnrichment = new FieldEnrichment();
		$fieldEnrichment->setFieldName('boostwords');
		$fieldEnrichment->setContent('very important');

		$this->enrichment->addMatchingRule($matchingRuleA)->addMatchingRule($matchingRuleB);
		$this->enrichment->addFieldEnrichment($fieldEnrichment);

		$this->assertEquals(2, $this->enrichment->getMatchingRules()->getCount());
	}

	/**
	 * @test
	 * @expectedException InvalidArgumentException
	 */
	public function canNotSetInvalidMatchingRuleCombination() {
		$this->enrichment->setMatchingRulesCombinationType('foo');
	}
}