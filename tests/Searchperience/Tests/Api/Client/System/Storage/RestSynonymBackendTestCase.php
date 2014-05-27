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
		$firstSynonym = $synonyms->offsetGet(0);
		$this->assertSame("en",$firstSynonym->getTagName(),'Could not reconstitude tagName from xml response');
		$this->assertSame(2, count($firstSynonym->getWordsWithSameMeaning()),'Could not reconstitude words with same meaning');

		$firstWordsWithSameMeaning  = array_values($firstSynonym->getWordsWithSameMeaning());
		$this->assertSame("mobilephone", $firstWordsWithSameMeaning[1],'Could not reconstitude words with same meaning');
	}

	/**
	 * @test
	 */
	public function canPostSynonym() {
		//$this->markTestIncomplete('Process error');
		$this->synonymBackend = $this->getMock('\Searchperience\Api\Client\System\Storage\RestSynonymBackend', array('executePostRequest'));
		$this->synonymBackend->injectDateTimeService(new \Searchperience\Api\Client\System\DateTime\DateTimeService());

		$restClient = new \Guzzle\Http\Client('http://api.searchperience.com/');
		$mock = new \Guzzle\Plugin\Mock\MockPlugin();
		$mock->addResponse(new \Guzzle\Http\Message\Response(201));
		$restClient->addSubscriber($mock);
		$this->synonymBackend->injectRestClient($restClient);

		$expectsArgumentsArray = Array(
			'mainWord' => 'foo',
			'wordsWithSameMeaning' => array(
				'bla','bar'
			),
			'tagName' => 'one'
		);

		$this->synonymBackend->expects($this->once())->method('executePostRequest')->with($expectsArgumentsArray,'/one')->will(
			$this->returnValue($this->getMock('\Guzzle\Http\Message\Response',array(),array(),'',false))
		);

		$synonym = new Synonym();
		$synonym->setMainWord('foo');
		$synonym->addWordWithSameMeaning('bla');
		$synonym->addWordWithSameMeaning('bar');
		$synonym->setTagName('one');

		$this->synonymBackend->post('one',$synonym);
	}

	/**
	 * @test
	 */
	public function canDeleteSynonym() {
		//$this->markTestIncomplete('Process error');
		$this->synonymBackend = $this->getMock('\Searchperience\Api\Client\System\Storage\RestSynonymBackend', array('deleteByMainWord'));
		$this->synonymBackend->injectDateTimeService(new \Searchperience\Api\Client\System\DateTime\DateTimeService());

		$restClient = new \Guzzle\Http\Client('http://api.searchperience.com/');
		$mock = new \Guzzle\Plugin\Mock\MockPlugin();
		$mock->addResponse(new \Guzzle\Http\Message\Response(201));
		$restClient->addSubscriber($mock);
		$this->synonymBackend->injectRestClient($restClient);

		$this->synonymBackend->expects($this->once())->method('deleteByMainWord')->with('one', 'foo')->will(
				$this->returnValue($this->getMock('\Guzzle\Http\Message\Response', array(), array(), '', false))
		);

		$synonym = new Synonym();
		$synonym->setMainWord('foo');
		$synonym->setTagName('one');

		$this->synonymBackend->delete('one',$synonym);
	}
}