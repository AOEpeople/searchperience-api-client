<?php

namespace Searchperience\Tests\Api\Client\Document;
use Searchperience\Api\Client\Domain\ActivityLogs\ActivityLogs;

/**
 * Class Urlqueue
 * @package Searchperience\Api\Client\Domain
 * @Author: Nikolay Diaur <nikolay.diaur@aoe.com>
 */
class ActivityLogsTestCase extends \Searchperience\Tests\BaseTestCase {

    /**
     * @var ActivityLogs
     */
    protected $activityLogs;

    /**
     *
     */
    public function setUp() {
        $this->activityLogs = new ActivityLogs();
    }

    /**
     * @test
     */
    public function setGet() {

        $this->activityLogs->__setProperty('id', '132');
        $this->activityLogs->__setProperty('logTime', '2014-01-01 10:10:00');
        $this->activityLogs->__setProperty('severity', 3);
        $this->activityLogs->__setProperty('message', 'No queue with documentId found');
        $this->activityLogs->__setProperty('additionalData', "a:0:{}");
        $this->activityLogs->__setProperty('packageKey', "packageKey");
        $this->activityLogs->__setProperty('className', "Controller_Indexer");
        $this->activityLogs->__setProperty('methodName', 'indexAction');

        $this->assertEquals('132', $this->activityLogs->getId());
        $this->assertEquals('2014-01-01 10:10:00', $this->activityLogs->getLogTime());
        $this->assertEquals(3, $this->activityLogs->getSeverity());
        $this->assertEquals('No queue with documentId found', $this->activityLogs->getMessage());
        $this->assertEquals("a:0:{}", $this->activityLogs->getAdditionalData());
        $this->assertEquals("packageKey", $this->activityLogs->getPackageKey());
        $this->assertEquals("Controller_Indexer", $this->activityLogs->getClassName());
        $this->assertEquals('indexAction', $this->activityLogs->getMethodName());
    }
}
