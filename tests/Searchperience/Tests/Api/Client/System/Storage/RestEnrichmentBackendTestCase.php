<?php

namespace Searchperience\Tests\Api\Client\Document\System\Storage;

use Searchperience\Api\Client\Domain\Enrichment\Enrichment;
use Searchperience\Api\Client\Domain\Enrichment\FieldEnrichment;
use Searchperience\Api\Client\Domain\Enrichment\MatchingRule;
use Searchperience\Api\Client\Domain\Filters\FilterCollection;

use Searchperience\Api\Client\System\Storage\RestEnrichmentBackend;
use Searchperience\Common\Http\Exception\EntityNotFoundException;

/**
 * @author Timo Schmidt
 * @date 14.11.12
 * @time 15:17
 */
class RestEnrichmentBackendTestCase extends \Searchperience\Tests\BaseTestCase {

	/**
	 * @var RestEnrichmentBackend
	 */
	protected $enrichmentBackend;

	/**
	 * @return void
	 */
	public function setUp() {
		$this->enrichmentBackend = new RestEnrichmentBackend();
	}

	/**
	 * @test
	 */
	public function testCanReconstituteEnrichmentCollection() {
		$restClient = new \Guzzle\Http\Client('http://api.searchperience.com/');
		$mock = new \Guzzle\Plugin\Mock\MockPlugin();
		$mock->addResponse(new \Guzzle\Http\Message\Response(201, NULL, $this->getFixtureContent('Api/Client/System/Storage/Fixture/Enrichment1.xml')));
		$restClient->addSubscriber($mock);

		$this->enrichmentBackend->injectRestClient($restClient);
		$enrichment = $this->enrichmentBackend->getById(1);
		$this->assertSame($enrichment->getFieldEnrichments()->getCount(),2,'Could not reconstitude field enrichments');
		$this->assertSame($enrichment->getMatchingRules()->getCount(),1,'Could not reconstitude matching rules');
		$this->assertSame($enrichment->getAddBoost(),'2315.22','Could not reconstitude add boost');
		$this->assertSame($enrichment->getTitle(),'my enrichment','Could not reconstitude title');
		$this->assertSame($enrichment->getDescription(), 'some text');

		$matchingRulesCollection = $enrichment->getMatchingRules();
		$this->assertCount(1, $matchingRulesCollection);
		$matchingRule = $matchingRulesCollection->getIterator()->offsetGet(0);
		$this->assertSame($matchingRule->getFieldName(), 'content');
		$this->assertSame($matchingRule->getOperator(), 'equals');
		$this->assertSame($matchingRule->getOperatorValue(), "10");
	}

	/**
	 * @test
	 */
	public function testCanGetCollectionWithTotalCount() {
		$restClient = new \Guzzle\Http\Client('http://api.searchperience.com/');
		$mock = new \Guzzle\Plugin\Mock\MockPlugin();
		$mock->addResponse(new \Guzzle\Http\Message\Response(201, NULL, $this->getFixtureContent('Api/Client/System/Storage/Fixture/Enrichment2.xml')));
		$restClient->addSubscriber($mock);

		$this->enrichmentBackend->injectRestClient($restClient);
		$enrichments = $this->enrichmentBackend->getAllByFilterCollection(1,10);
		$this->assertEquals($enrichments->getTotalCount(), 99, 'Could not reconstitude enrichment collection');
	}

	/**
	 * @test
	 */
	public function canPostEnrichment() {
		$responsetMock = $this->getMock('\Guzzle\Http\Message\Response', array(), array(), '', false);
		$resquestMock = $this->getMock('\Guzzle\Http\Message\Request',array('setAuth','send'),array(),'',false);
		$resquestMock->expects($this->once())->method('setAuth')->will($this->returnCallback(function () use ($resquestMock) {
			return $resquestMock;
		}));
		$resquestMock->expects($this->once())->method('send')->will($this->returnCallback(function () use ($responsetMock) {
			return $responsetMock;
		}));

		$expectedArguments = array(
			'description' => 'one two three',
			'title' => 'foo',
			'addBoost' => '99.99%',
			'matchingRuleCombinationType' => 'all',
			'matchingRuleExpectedResult' => 1,
			'matchingRules' => array(
				array(
					'fieldName' => 'title',
					'operator' => 'contains_not',
					'operatorValue' => 'nasa'
				)
			),
			'fieldEnrichments' => array(
				array(
					'content' => 'dsad adas das das das dsarewr lkilklök lklöklö ',
					'fieldName' => 'testfield'
				)
			)
		);

		$restClient = $this->getMock('\Guzzle\Http\Client',array('post','setAuth','send'),array('http://api.searcperience.com/'));
		$restClient->expects($this->once())->method('post')->with('/{customerKey}/enrichments',null,$expectedArguments)->will($this->returnCallback(function($url,$foo,$arguments) use ($resquestMock) {
			return $resquestMock;
		}));

		$enrichment = new Enrichment();
		$enrichment->setTitle('foo');
		$enrichment->setAddBoost("99.99%");
		$enrichment->setDescription('one two three');
		$enrichment->setMatchingRulesCombinationType($enrichment::MATCH_ALL);
		$enrichment->setMatchingRulesExpectedResult(true);

		$matchingRule = new MatchingRule();
		$matchingRule->setFieldName('title');
		$matchingRule->setOperator(MatchingRule::OPERATOR_CONTAINSNOT);
		$matchingRule->setOperatorValue('nasa');
		$enrichment->addMatchingRule($matchingRule);

		$fieldEnrichment = new FieldEnrichment();
		$fieldEnrichment->setContent('dsad adas das das das dsarewr lkilklök lklöklö ');
		$fieldEnrichment->setFieldName('testfield');
		$enrichment->addFieldEnrichment($fieldEnrichment);

		$this->enrichmentBackend->injectRestClient($restClient);
		$this->enrichmentBackend->post($enrichment);
	}

	/**
	 * @test
	 * @expectedException \Searchperience\Common\Exception\InvalidArgumentException
	 */
	public function invalidSortingThrowsException() {
		$this->enrichmentBackend->getAllByFilterCollection(0, 10, new FilterCollection(),'foo','Foo');
	}

	/**
	 * @test
	 */
	public function restBackendReturnsEmptyCollectionForEmptyListResponse() {
			/** @var  $restBackend RestEnrichmentBackend */
		$restBackend = $this->getMock('Searchperience\Api\Client\System\Storage\RestEnrichmentBackend',array('getListResponseFromEndpoint'));
		$restBackend->expects($this->once())->method('getListResponseFromEndpoint')->will($this->returnCallback(
			function(){
				throw new EntityNotFoundException();
			}
		));

		$result = $restBackend->getAllByFilterCollection(0,10);
		$this->assertInstanceOf('Searchperience\Api\Client\Domain\Enrichment\EnrichmentCollection',$result);
	}
}
