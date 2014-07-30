<?php

namespace Searchperience\Tests\Api\Client\Document;
use Searchperience\Api\Client\Domain\CommandLog\CommandLog;

/**
 * Class Urlqueue
 * @package Searchperience\Api\Client\Domain
 * @Author: Nikolay Diaur <nikolay.diaur@aoe.com>
 */
class CommandLogTestCase extends \Searchperience\Tests\BaseTestCase {

    /**
     * @var CommandLog
     */
    protected $commandLog;

    /**
     *
     */
    public function setUp() {
        $this->commandLog = new CommandLog();
    }

    /**
     * @test
     */
    public function setGet() {
        $this->commandLog->setCommandName('test');
        $this->commandLog->setBinary('test.php');
        $this->commandLog->setDuration(0);
        $this->commandLog->setLogMessage('error test message');
        $this->commandLog->setStartTime("2014-01-01 10:10:00");
        $this->commandLog->setEndTime("2014-01-01 10:10:10");
        $this->commandLog->setProcessId(999);
        $this->commandLog->setStatus('finished');


        $this->assertEquals('test', $this->commandLog->getCommandName());
        $this->assertEquals('test.php', $this->commandLog->getBinary());
        $this->assertEquals(0, $this->commandLog->getDuration());
        $this->assertEquals('error test message', $this->commandLog->getLogMessage());
        $this->assertEquals("2014-01-01 10:10:00", $this->commandLog->getStartTime());
        $this->assertEquals("2014-01-01 10:10:10", $this->commandLog->getEndTime());
        $this->assertEquals(999, $this->commandLog->getProcessId());
        $this->assertEquals('finished', $this->commandLog->getStatus());
    }
}
