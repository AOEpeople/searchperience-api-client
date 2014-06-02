<?php

namespace Searchperience\Tests\Api\Client\Document\System\Storage;
use Searchperience\Api\Client\Domain\Command\ReCrawlCommand;
use Searchperience\Api\Client\Domain\UrlQueueItem\UrlQueueItem;

/**
 * @author Timo Schmidt <timo.schmidt@aoe.com>
‚ */
class RestCommandBackendTestCase extends \Searchperience\Tests\BaseTestCase {

	/**
	 * @var \Searchperience\Api\Client\System\Storage\RestCommandBackend
	 */
	protected $commandBackend = null;

	/**
	 * Initialize test environment
	 *
	 * @return void
	 */
	public function setUp() {
		$this->commandBackend = new \Searchperience\Api\Client\System\Storage\RestCommandBackend();
	}

	/**
	 * @test
	 */
	public function canPostCommand() {
		$responsetMock = $this->getMock('\Guzzle\Http\Message\Response', array(), array(), '', false);
		$resquestMock = $this->getMock('\Guzzle\Http\Message\Request',array('setAuth','send'),array(),'',false);
		$resquestMock->expects($this->once())->method('setAuth')->will($this->returnCallback(function () use ($resquestMock) {
			return $resquestMock;
		}));
		$resquestMock->expects($this->once())->method('send')->will($this->returnCallback(function () use ($responsetMock) {
			return $responsetMock;
		}));

		$expectedArguments = array(
			'name' => 'ReCrawl',
			'arguments' => array(
				'documentIds' => array(
					100,
					101
				)
			)

		);

		$restClient = $this->getMock('\Guzzle\Http\Client',array('post','setAuth','send'),array('http://api.searcperience.com/'));
		$restClient->expects($this->once())->method('post')->with('/{customerKey}/commands',null,$expectedArguments)->will($this->returnCallback(function($url,$foo,$arguments) use ($resquestMock) {
			return $resquestMock;
		}));

		$command = new ReCrawlCommand();
		$command->addDocumentId(100);
		$command->addDocumentId(101);

		$this->commandBackend->injectRestClient($restClient);
		$this->commandBackend->post($command);
	}
}