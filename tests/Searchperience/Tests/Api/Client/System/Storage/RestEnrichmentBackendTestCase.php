<?php

namespace Searchperience\Tests\Api\Client\Document\System\Storage;

use Ddeboer\Imap\Exception\Exception;
use Searchperience\Api\Client\Domain\Enrichment\ContextsBoosting;
use Searchperience\Api\Client\Domain\Enrichment\ContextsBoostingCollection;
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
class RestEnrichmentBackendTestCase extends \Searchperience\Tests\BaseTestCase
{

    /**
     * @var RestEnrichmentBackend
     */
    protected $enrichmentBackend;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->enrichmentBackend = new RestEnrichmentBackend();
    }

    /**
     * @test
     */
    public function testCanReconstituteEnrichmentCollection()
    {
        $restClient = new \Guzzle\Http\Client('http://api.searchperience.com/');
        $mock = new \Guzzle\Plugin\Mock\MockPlugin();
        $mock->addResponse(new \Guzzle\Http\Message\Response(201, NULL, $this->getFixtureContent('Api/Client/System/Storage/Fixture/Enrichment1.xml')));
        $restClient->addSubscriber($mock);

        $this->enrichmentBackend->injectRestClient($restClient);
        $enrichment = $this->enrichmentBackend->getById(1);
        $this->assertSame($enrichment->getFieldEnrichments()->getCount(), 2, 'Could not reconstitude field enrichments');
        $this->assertSame($enrichment->getMatchingRules()->getCount(), 1, 'Could not reconstitude matching rules');
        $this->assertSame($enrichment->getAddBoost(), '2315.22', 'Could not reconstitude add boost');
        $this->assertSame($enrichment->getTitle(), 'my enrichment', 'Could not reconstitude title');
        $this->assertSame($enrichment->getDescription(), 'some text');

        $matchingRulesCollection = $enrichment->getMatchingRules();
        $this->assertCount(1, $matchingRulesCollection);
        /** @var $matchingRule MatchingRule */
        $matchingRule = $matchingRulesCollection->getIterator()->offsetGet(0);
        $this->assertSame($matchingRule->getFieldName(), 'content');
        $this->assertSame($matchingRule->getOperator(), 'matches');
        $this->assertSame($matchingRule->getOperandValue(), "10");

        /** @var ContextsBoostingCollection $contextsBoostingCollection */
        $contextsBoostingCollection = $enrichment->getContextsBoosting();

        $this->assertSame($contextsBoostingCollection->offsetGet(0)->getBoostFieldName(), "categories");
        $this->assertSame($contextsBoostingCollection->offsetGet(0)->getBoostFieldValue(), "naiset_asusteet");
        $this->assertSame($contextsBoostingCollection->offsetGet(0)->getBoostOptionName(), "is_loyalty");
        $this->assertSame($contextsBoostingCollection->offsetGet(0)->getBoostOptionValue(), true);
        $this->assertSame($contextsBoostingCollection->offsetGet(0)->getBoostingValue(), (double)5.0);
    }

    /**
     * @test
     */
    public function testCanGetCollectionWithTotalCount()
    {
        $restClient = new \Guzzle\Http\Client('http://api.searchperience.com/');
        $mock = new \Guzzle\Plugin\Mock\MockPlugin();
        $mock->addResponse(new \Guzzle\Http\Message\Response(201, NULL, $this->getFixtureContent('Api/Client/System/Storage/Fixture/Enrichment2.xml')));
        $restClient->addSubscriber($mock);

        $this->enrichmentBackend->injectRestClient($restClient);
        $enrichments = $this->enrichmentBackend->getAllByFilterCollection(1, 10);
        $this->assertEquals($enrichments->getTotalCount(), 99, 'Could not reconstitude enrichment collection');
    }

    /**
     * @test
     */
    public function canPostEnrichment()
    {
        $responsetMock = $this->getMockBuilder('\Guzzle\Http\Message\Response')->setMethods(array())->setConstructorArgs(array())->setMockClassName('')->disableOriginalConstructor(false)->getMock();
		$resquestMock = $this->getMockBuilder('\Guzzle\Http\Message\Request')->setMethods(array('setAuth','send'))->setConstructorArgs(array())->setMockClassName('')->disableOriginalConstructor(false)->getMock();
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
                    'operandValue' => 'nasa'
                )
            ),
            'fieldEnrichments' => array(
                array(
                    'content' => 'dsad adas das das das dsarewr lkilklök lklöklö ',
                    'fieldName' => 'testfield'
                )
            ),
            'contextsBoosting' => array(
                array(
                    'boostFieldName' => 'brands',
                    'boostFieldValue' => 'women',
                    'boostOptionName' => 'in_stock',
                    'boostOptionValue' => true,
                    'boostingValue' => 5.6,
                )
            ),
        );

        $restClient = $this->getMockBuilder('\Guzzle\Http\Client')->setMethods(array('post','setAuth','send'))->setConstructorArgs(array('http://api.searcperience.com/'))->setMockClassName('')->disableOriginalConstructor(false)->getMock();
        $restClient->expects($this->once())->method('post')->with('/{customerKey}/enrichments', null, $expectedArguments)->will($this->returnCallback(function ($url, $foo, $arguments) use ($resquestMock) {
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
        $matchingRule->setOperandValue('nasa');
        $enrichment->addMatchingRule($matchingRule);

        $fieldEnrichment = new FieldEnrichment();
        $fieldEnrichment->setContent('dsad adas das das das dsarewr lkilklök lklöklö ');
        $fieldEnrichment->setFieldName('testfield');
        $enrichment->addFieldEnrichment($fieldEnrichment);

        $contextsBoosting = new ContextsBoosting();
        $contextsBoosting->setBoostFieldName('brands');
        $contextsBoosting->setBoostFieldValue('women');
        $contextsBoosting->setBoostOptionName('in_stock');
        $contextsBoosting->setBoostOptionValue(true);
        $contextsBoosting->setBoostingValue(5.6);
        $enrichment->addContextsBoosting($contextsBoosting);

        $this->enrichmentBackend->injectRestClient($restClient);
        $this->enrichmentBackend->post($enrichment);
    }

    /**
     * @test
     * @expectedException \Searchperience\Common\Exception\InvalidArgumentException
     */
    public function invalidSortingThrowsException()
    {
        $this->enrichmentBackend->getAllByFilterCollection(0, 10, new FilterCollection(), 'foo', 'Foo');
    }

    /**
     * @test
     */
    public function restBackendReturnsEmptyCollectionForEmptyListResponse()
    {
        /** @var  $restBackend RestEnrichmentBackend */
        $restBackend = $this->getMockBuilder('Searchperience\Api\Client\System\Storage\RestEnrichmentBackend')->setMethods(array('getListResponseFromEndpoint'))->getMock();
        $restBackend->expects($this->once())->method('getListResponseFromEndpoint')->will($this->returnCallback(
            function () {
                throw new EntityNotFoundException();
            }
        ));

        $result = $restBackend->getAllByFilterCollection(0, 10);
        $this->assertInstanceOf('Searchperience\Api\Client\Domain\Enrichment\EnrichmentCollection', $result);
    }

    /**
     * @test
     */
    public function getByDocumentIdNothingForEmptyResponse()
    {
        $restClient = $this->getMockedRestClientWith404Response();
        $this->enrichmentBackend->injectRestClient($restClient);
        $enrichment = $this->enrichmentBackend->getById(1234);
        $this->assertNull($enrichment, 'Get by documentId did not return null for unexisting entity');
    }
}
