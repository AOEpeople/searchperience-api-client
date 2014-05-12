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
		$matchingRuleA->setOperandValue('aoe');

		$matchingRuleB = new MatchingRule();
		$matchingRuleB->setFieldName('brand');
		$matchingRuleB->setOperator(MatchingRule::OPERATOR_CONTAINSNOT);
		$matchingRuleB->setOperandValue('nasa');

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

	/**
	 * @test
	 */
	public function verifyKeywordsGetsSetWithFieldEnrichments() {
		$fixtureEnrichment = new Enrichment();
		$fieldEnrichmentLow = new FieldEnrichment();
		$fieldEnrichmentLow->setFieldName('lowboostw');
		$fieldEnrichmentLow->setContent('low low low');
		$fixtureEnrichment->addFieldEnrichment($fieldEnrichmentLow);
		$fieldEnrichmentNormal = new FieldEnrichment();
		$fieldEnrichmentNormal->setFieldName('normalboostw');
		$fieldEnrichmentNormal->setContent('normal normal normal');
		$fixtureEnrichment->addFieldEnrichment($fieldEnrichmentNormal);
		$fieldEnrichmentHigh = new FieldEnrichment();
		$fieldEnrichmentHigh->setFieldName('highboostw');
		$fieldEnrichmentHigh->setContent('high high high');
		$fixtureEnrichment->addFieldEnrichment($fieldEnrichmentHigh);

		$this->enrichment->setLowBoostedKeywords('low low low');
		$this->enrichment->setNormalBoostedKeywords('normal normal normal');
		$this->enrichment->setHighBoostedKeywords('high high high');

		$this->assertEquals($this->enrichment, $fixtureEnrichment);
	}

	/**
	 * @test
	 */
	public function setNormalBoostedKeywordsIsCreatingOnlyOneFieldEnrichment() {
		$this->assertSame(0, $this->enrichment->getFieldEnrichments()->count(),'Unexpected amount of field enrichments');

		$this->enrichment->setNormalBoostedKeywords('foo, bar');
		$this->enrichment->setNormalBoostedKeywords('one, two');

		$this->assertSame(1, $this->enrichment->getFieldEnrichments()->getCount(),'setNormalBoostedKeywords creates unexpected amoount of fieldEnrichments');
		$this->assertSame('one, two', $this->enrichment->getNormalBoostedKeywords());
	}

	/**
	 * @test
	 */
	public function canResetKeyWordFieldsWithEmptyContent() {
		$this->assertSame(0, $this->enrichment->getFieldEnrichments()->count(),'Unexpected amount of field enrichments');

		$this->enrichment->setLowBoostedKeywords('bla, bla');
		$this->enrichment->setNormalBoostedKeywords('foo, bar');
		$this->enrichment->setHighBoostedKeywords('blub');

		$this->assertSame(3, $this->enrichment->getFieldEnrichments()->count(),'Unexpected amount of field enrichments');

		$this->assertEquals('bla, bla', $this->enrichment->getLowBoostedKeywords());
		$this->assertEquals('foo, bar', $this->enrichment->getNormalBoostedKeywords());
		$this->assertEquals('blub', $this->enrichment->getHighBoostedKeywords());

		$this->assertSame(3, $this->enrichment->getFieldEnrichments()->count(),'Unexpected amount of field enrichments');

		$this->enrichment->setLowBoostedKeywords('');
		$this->enrichment->setNormalBoostedKeywords('');
		$this->enrichment->setHighBoostedKeywords('');

			//now we should have no field enrichments left because we resetted them with an empty string
		$this->assertSame(0, $this->enrichment->getFieldEnrichments()->count(),'Unexpected amount of field enrichments');

		$this->assertEquals('', $this->enrichment->getLowBoostedKeywords());
		$this->assertEquals('', $this->enrichment->getNormalBoostedKeywords());
		$this->assertEquals('', $this->enrichment->getHighBoostedKeywords());
	}

	/**
	 * @test
	 */
	public function verifyKeywordsCanBeRetrievedFromEnrichment() {
		$this->enrichment->setLowBoostedKeywords("low");
		$this->enrichment->setNormalBoostedKeywords("normal");
		$this->enrichment->setHighBoostedKeywords("high");

		$this->assertEquals("low", $this->enrichment->getLowBoostedKeywords());
		$this->assertEquals("normal", $this->enrichment->getNormalBoostedKeywords());
		$this->assertEquals("high", $this->enrichment->getHighBoostedKeywords());
	}
}