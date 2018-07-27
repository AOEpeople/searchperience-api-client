<?php

namespace Searchperience\Tests\Api\Client\Document\System\Storage;

use Searchperience\Api\Client\Domain\Synonym\Synonym;
use Searchperience\Api\Client\System\Storage\RestSynonymBackend;

/**
 * Class RestSynonymBackendTestCase
 * @package Searchperience\Tests\Api\Client\Document\System\Storage
 * @author Timo Schmidt <timo.schmidt@aoe.com>
 */
class RestSynonymBackendTestCase extends \Searchperience\Tests\BaseTestCase {

	/**
	 * @var RestSynonymBackend
	 */
	protected $synonymBackend;

	/**
	 * @return void
	 */
	public function setUp() {
		$this->synonymBackend = new RestSynonymBackend();
	}

	/**
	 * @test
	 */
	public function canGetSynonyms() {
		$restClient = new \Guzzle\Http\Client('http://api.searchperience.com/');
		$mock = new \Guzzle\Plugin\Mock\MockPlugin();
		$mock->addResponse(new \Guzzle\Http\Message\Response(201, NULL, $this->getFixtureContent('Api/Client/System/Storage/Fixture/Synonyms.xml')));
		$restClient->addSubscriber($mock);

		$this->synonymBackend->injectRestClient($restClient);
		$synonyms = $this->synonymBackend->getAll();

		$this->assertEquals(2, $synonyms->getTotalCount(), 'Could not reconstitute synonym collection');
		$this->assertEquals(2, $synonyms->getCount(), 'Could not get count from synonyms');
		/** @var Synonym $firstSynonym */
        $firstSynonym = $synonyms->offsetGet(0);
		$this->assertSame("en",$firstSynonym->getTagName(),'Could not reconstitude tagName from xml response');
		$this->assertSame(2, count(explode(',', $firstSynonym->getMappedWords())),'Could not reconstitude mapped words');

		$mappedWords  = explode(',', $firstSynonym->getMappedWords());
		$this->assertSame("mobilephone", $mappedWords[1],'Could not reconstitude mapped words');
	}

	/**
	 * @test
	 */
	public function getBySynonyms() {
		$restClient = new \Guzzle\Http\Client('http://api.searchperience.com/');
		$mock = new \Guzzle\Plugin\Mock\MockPlugin();
		$mock->addResponse(new \Guzzle\Http\Message\Response(201, NULL, $this->getFixtureContent('Api/Client/System/Storage/Fixture/SynonymsBySynonyms.xml')));
		$restClient->addSubscriber($mock);

		$this->synonymBackend->injectRestClient($restClient);
		$synonym = $this->synonymBackend->getBySynonyms('en','bike');

		$this->assertSame("en",$synonym->getTagName(),'Could not reconstitude tagName from xml response');
		$this->assertSame(1, count(explode(',', $synonym->getMappedWords())),'Could not reconstitude mapped words');

		$mappedWords  = explode(',', $synonym->getMappedWords());
		$this->assertSame("bicycle", $mappedWords[0],'Could not reconstitude mapped words');
	}

	/**
	 * @test
	 */
	public function getBySynonymsReturnsNothingForEmptyResponse() {
		$restClient = $this->getMockedRestClientWith404Response();
		$this->synonymBackend->injectRestClient($restClient);
		$synonym = $this->synonymBackend->getBySynonyms('en','bike');
		$this->assertNull($synonym,'Get by synonyms did not return null for unexisting entity');
	}

	/**
	 * @test
	 */
	public function canPostSynonym() {
		//$this->markTestIncomplete('Process error');
		$this->synonymBackend = $this->getMockBuilder('\Searchperience\Api\Client\System\Storage\RestSynonymBackend')->setMethods(array('executePostRequest'))->getMock();
		$this->synonymBackend->injectDateTimeService(new \Searchperience\Api\Client\System\DateTime\DateTimeService());

		$restClient = new \Guzzle\Http\Client('http://api.searchperience.com/');
		$mock = new \Guzzle\Plugin\Mock\MockPlugin();
		$mock->addResponse(new \Guzzle\Http\Message\Response(201));
		$restClient->addSubscriber($mock);
		$this->synonymBackend->injectRestClient($restClient);

		$expectsArgumentsArray = Array(
			'synonyms' => 'foo',
			'mappedWords' => 'bla,bar',
			'tagName' => 'one',
			'type' => 'grouping'
		);

		$this->synonymBackend->expects($this->once())->method('executePostRequest')->with($expectsArgumentsArray,'/one')->will(
			$this->returnValue($this->createMock('\Guzzle\Http\Message\Response',array(),array(),'',false))
		);

		$synonym = new Synonym();
		$synonym->setSynonyms('foo');
		$synonym->setMappedWords('bla,bar');
		$synonym->setLanguage('one');

		$this->synonymBackend->post('one',$synonym);
	}

	/**
	 * @test
	 */
	public function canDeleteBySynonyms() {
		//$this->markTestIncomplete('Process error');
		$this->synonymBackend = $this->getMockBuilder('\Searchperience\Api\Client\System\Storage\RestSynonymBackend')->setMethods(array('getDeleteResponseFromEndpoint'))->getMock();
		$this->synonymBackend->injectDateTimeService(new \Searchperience\Api\Client\System\DateTime\DateTimeService());

		$restClient = new \Guzzle\Http\Client('http://api.searchperience.com/');
		$mock = new \Guzzle\Plugin\Mock\MockPlugin();
		$mock->addResponse(new \Guzzle\Http\Message\Response(201));
		$restClient->addSubscriber($mock);
		$this->synonymBackend->injectRestClient($restClient);

		$this->synonymBackend->expects($this->once())->method('getDeleteResponseFromEndpoint')->with('/one/foo')->will(
				$this->returnValue($this->createMock('\Guzzle\Http\Message\Response', array(), array(), '', false))
		);

		$this->synonymBackend->deleteBySynonyms('one', 'foo');
	}

    /**
     * @test
     */
    public function canDeleteBySynonym() {
		//$this->markTestIncomplete('Process error');
		$this->synonymBackend = $this->getMockBuilder('\Searchperience\Api\Client\System\Storage\RestSynonymBackend')->setMethods(array('getDeleteResponseFromEndpoint'))->getMock();
        $this->synonymBackend->injectDateTimeService(new \Searchperience\Api\Client\System\DateTime\DateTimeService());

        $restClient = new \Guzzle\Http\Client('http://api.searchperience.com/');
        $mock = new \Guzzle\Plugin\Mock\MockPlugin();
        $mock->addResponse(new \Guzzle\Http\Message\Response(201));
        $restClient->addSubscriber($mock);
        $this->synonymBackend->injectRestClient($restClient);


        $synonym = new Synonym();
        $synonym->setSynonyms('foo');
        $synonym->setLanguage('one');

        $this->synonymBackend->expects($this->once())->method('getDeleteResponseFromEndpoint')->with('/one', $synonym)->will(
            $this->returnValue($this->createMock('\Guzzle\Http\Message\Response', array(), array(), '', false))
        );

        $this->synonymBackend->delete('one',$synonym);
    }

}