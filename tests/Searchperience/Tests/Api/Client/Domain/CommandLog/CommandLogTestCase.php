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

        $this->commandLog->__setProperty('commandName', 'test');
        $this->commandLog->__setProperty('binary', 'test.php');
        $this->commandLog->__setProperty('duration', 0);
        $this->commandLog->__setProperty('logMessage', 'error test message');
        $this->commandLog->__setProperty('startTime', "2014-01-01 10:10:00");
        $this->commandLog->__setProperty('endTime', "2014-01-01 10:10:10");
        $this->commandLog->__setProperty('processId', 999);
        $this->commandLog->__setProperty('status', 'finished');

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
