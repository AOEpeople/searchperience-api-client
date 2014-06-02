<?php

namespace Searchperience\Tests\Api\Client\Document\System\Storage;

use Searchperience\Api\Client\Domain\Stopword\Stopword;
use Searchperience\Api\Client\System\Storage\RestStopwordBackend;

/**
 * Class RestStopwordBackendTestCase
 * @package Searchperience\Tests\Api\Client\Document\System\Storage
 * @author Timo Schmidt <timo.schmidt@aoe.com>
 */
class RestStopwordBackendTestCase extends \Searchperience\Tests\BaseTestCase {

	/**
	 * @var RestStopwordBackend
	 */
	protected $stopwordBackend;

	/**
	 * @return void
	 */
	public function setUp() {
		$this->stopwordBackend = new RestStopwordBackend();
	}

	/**
	 * @test
	 */
	public function canGetStopwords() {
		$restClient = new \Guzzle\Http\Client('http://api.searchperience.com/');
		$mock = new \Guzzle\Plugin\Mock\MockPlugin();
		$mock->addResponse(new \Guzzle\Http\Message\Response(201, NULL, $this->getFixtureContent('Api/Client/System/Storage/Fixture/Stopwords.xml')));
		$restClient->addSubscriber($mock);

		$this->stopwordBackend->injectRestClient($restClient);
		$stopwords = $this->stopwordBackend->getAll();

		$this->assertEquals(2, $stopwords->getTotalCount(), 'Could not reconstitute stopword collection');
		$this->assertEquals(2, $stopwords->getCount(), 'Could not get count from stopwords');
		$firstStopword = $stopwords->offsetGet(0);
		$this->assertSame("en",$firstStopword->getTagName(),'Could not reconstitude tagName from xml response');
		$this->assertSame("blue", $firstStopword->getWord(),'Could not reconstitude word');
	}

	/**
	 * @test
	 */
	public function canPostStopword() {
		//$this->markTestIncomplete('Process error');
		$this->stopwordBackend = $this->getMock('\Searchperience\Api\Client\System\Storage\RestStopwordBackend', array('executePostRequest'));
		$this->stopwordBackend->injectDateTimeService(new \Searchperience\Api\Client\System\DateTime\DateTimeService());

		$restClient = new \Guzzle\Http\Client('http://api.searchperience.com/');
		$mock = new \Guzzle\Plugin\Mock\MockPlugin();
		$mock->addResponse(new \Guzzle\Http\Message\Response(201));
		$restClient->addSubscriber($mock);
		$this->stopwordBackend->injectRestClient($restClient);

		$expectsArgumentsArray = array('word' => 'foo', 'tagName' => 'one');

		$this->stopwordBackend->expects($this->once())->method('executePostRequest')->with($expectsArgumentsArray,'/one')->will(
			$this->returnValue($this->getMock('\Guzzle\Http\Message\Response',array(),array(),'',false))
		);

		$stopword = new Stopword();
		$stopword->setWord('foo');
		$stopword->setTagName('one');

		$this->stopwordBackend->post('one',$stopword);
	}

	/**
	 * @test
	 */
	public function canDeleteStopword() {
		//$this->markTestIncomplete('Process error');
		$this->stopwordBackend = $this->getMock('\Searchperience\Api\Client\System\Storage\RestStopwordBackend', array('deleteByWord'));
		$this->stopwordBackend->injectDateTimeService(new \Searchperience\Api\Client\System\DateTime\DateTimeService());

		$restClient = new \Guzzle\Http\Client('http://api.searchperience.com/');
		$mock = new \Guzzle\Plugin\Mock\MockPlugin();
		$mock->addResponse(new \Guzzle\Http\Message\Response(201));
		$restClient->addSubscriber($mock);
		$this->stopwordBackend->injectRestClient($restClient);

		$this->stopwordBackend->expects($this->once())->method('deleteByWord')->with('one', 'foo')->will(
				$this->returnValue($this->getMock('\Guzzle\Http\Message\Response', array(), array(), '', false))
		);

		$stopword = new Stopword();
		$stopword->setWord('foo');
		$stopword->setTagName('one');

		$this->stopwordBackend->delete('one',$stopword);
	}
}