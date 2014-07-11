<?php
/**
 * Created by IntelliJ IDEA.
 * User: pavelbogomolenko
 * Date: 10/07/14
 * Time: 16:50
 * To change this template use File | Settings | File Templates.
 */

namespace Searchperience\Tests\Api\Client\Document\System\Storage;

use Searchperience\Api\Client\System\Storage\RestAdminSearchBackend;
use Searchperience\Api\Client\Domain\AdminSearch\AdminSearch;
use Searchperience\Api\Client\Domain\AdminSearch\AdminSearchCollection;


/**
 * Class RestAdminSearchBackendTestCase
 * @package Searchperience\Tests\Api\Client\Document\System\Storage
 */
class RestAdminSearchBackendTestCase extends \Searchperience\Tests\BaseTestCase {

	/**
	 * @var RestAdminSearchBackend
	 */
	protected $adminSearchBackend;

	/**
	 * Initialize test environment
	 *
	 * @return void
	 */
	public function setUp() {
		$this->adminSearchBackend = new RestAdminSearchBackend();
	}

	/**
	 * Cleanup test environment
	 *
	 * @return void
	 */
	public function tearDown() {
		$this->adminSearchBackend = NULL;
	}

	/**
	 * @test
	 */
	public function canGetAllAdminSearchCollection() {
		$restClient = new \Guzzle\Http\Client('http://api.searchperience.me/qvc/');
		$mock = new \Guzzle\Plugin\Mock\MockPlugin();
		$mock->addResponse(new \Guzzle\Http\Message\Response(201, NULL, $this->getFixtureContent('Api/Client/System/Storage/Fixture/adminsearch_collection.xml')));
		$restClient->addSubscriber($mock);

		$this->adminSearchBackend->injectRestClient($restClient);

		$expectedAdminSearchCollection = new AdminSearchCollection();

		$expectedAdminSearch1 = new AdminSearch();
		$expectedAdminSearch1->__setProperty("id","1");
		$expectedAdminSearch1->__setProperty("title","some endpoint");
		$expectedAdminSearch1->__setProperty("description","some endpoint");
		$expectedAdminSearch1->__setProperty("url","http://www.dummy.url/api/");

		$expectedAdminSearchCollection->append($expectedAdminSearch1);

		$expectedAdminSearch2 = new AdminSearch();
		$expectedAdminSearch2->__setProperty("id","2");
		$expectedAdminSearch2->__setProperty("title","some endpoint 2");
		$expectedAdminSearch2->__setProperty("description","some endpoint 2");
		$expectedAdminSearch2->__setProperty("url","http://www.dummy.url/api/v2");

		$expectedAdminSearchCollection->append($expectedAdminSearch2);

		$actualAdminSearchCollection = $this->adminSearchBackend->getAll();

		$this->assertEquals($expectedAdminSearchCollection, $actualAdminSearchCollection);
	}
}