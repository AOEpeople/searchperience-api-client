<?php

namespace Searchperience\Tests\Api\Client\Document;

use Searchperience\Api\Client\Domain\UrlQueueItem\Filters\FilterCollectionFactory;
use Searchperience\Api\Client\System\Storage;
use Searchperience\Api\Client\Domain\UrlQueueItem\UrlQueueItemRepository;

/**
 * @author Timo Schmidt <timo.schmidt@aoe.com>
 */
class PriorityFilterTestCase extends \Searchperience\Tests\BaseTestCase {

	/**
	 * @var \Searchperience\Api\Client\Domain\UrlQueueItem\Filters\PriorityFilter
	 */
	protected $filter;

	/**
	 * @return void
	 */
	public function setUp() {
		$this->filter = new PriorityFilter();
	}

	/**
	 * @test
	 */
	public function testFilter() {
		$this->filter->setPriorityStart(1);
		$this->filter->setPriorityEnd(2);
		$this->assertEquals("&priorityStart=1&priorityEnd=2",$this->filter->getFilterString());
	}
}