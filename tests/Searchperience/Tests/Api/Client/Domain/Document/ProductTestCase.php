<?php

namespace Searchperience\Tests\Api\Client\Document;
use Searchperience\Api\Client\Domain\Document\Product;
use Searchperience\Api\Client\Domain\Document\ProductAttribute;
use Searchperience\Api\Client\Domain\Document\ProductCategoryPath;

/**
 * Class ProductTestCase
 * @package Searchperience\Tests\Api\Client\Document
 */
class ProductTestCase extends \Searchperience\Tests\BaseTestCase {

	/**
	 * @var \Searchperience\Api\Client\Domain\Document\Product
	 */
	protected $product;

	/**
	 * Initialize test environment
	 *
	 * @return void
	 */
	public function setUp() {
		$this->product = new \Searchperience\Api\Client\Domain\Document\Product();
	}

	/**
	 * Cleanup test environment
	 *
	 * @return void
	 */
	public function tearDown() {
		$this->product = null;
	}

	/**
	 * @test
	 */
	public function canAddAttribute() {
		$this->assertEquals(0, $this->product->getAttributeCount());
		$attribute = new ProductAttribute();
		$attribute->setType(ProductAttribute::TYPE_STRING);
		$attribute->addValue("foo");
		$this->product->addAttribute($attribute);
		$this->assertEquals(1, $this->product->getAttributeCount());
	}

	/**
	 * @test
	 */
	public function setTitle() {
		$this->product->setTitle("foo");
		$this->assertSame("foo",$this->product->getTitle());
	}

	/**
	 * @test
	 */
	public function canGetContentAsXmlForSimpleAttribute() {
		$this->assertEquals(0, $this->product->getAttributeCount());
		$attribute = new ProductAttribute();
		$attribute->setType(ProductAttribute::TYPE_STRING);
		$attribute->addValue("foo");
		$attribute->setName('test');
		$this->product->addAttribute($attribute);

		$expectedNode = '<attribute name="test" type="string">
		<value>foo</value>
		</attribute>';

		$this->assertContainsXmlSnipped($expectedNode, $this->product->getContent());
	}

	/**
	 * @test
	 */
	public function canGetContentAsXmlContainsDescription() {
		$this->product->setDescription("hello");
		$expectedNode = '<description>hello</description>';
		$this->assertContainsXmlSnipped($expectedNode, $this->product->getContent());
	}

	/**
	 * @test
	 */
	public function canGetContentAsXmlContainsAvailability() {
		$this->product->setAvailability(true);
		$expectedNode = '<availability>1</availability>';
		$this->assertContainsXmlSnipped($expectedNode, $this->product->getContent());
	}

	/**
	 * @test
	 */
	public function canGetContentAsXmlContainsSpecialPrice() {
		$this->product->setSpecialPrice(22.20);
		$expectedNode = '<special_price>22.20</special_price>';
		$this->assertContainsXmlSnipped($expectedNode, $this->product->getContent());
	}

	/**
	 * @test
	 */
	public function canGetContentAsXmlContainsStoredId() {
		$this->product->setStoreId(100);
		$expectedNode = '<storeId>100</storeId>';
		$this->assertContainsXmlSnipped($expectedNode, $this->product->getContent());
	}

	/**
	 * @test
	 */
	public function canGetContentAsXmlContainsTitle() {
		$this->product->setTitle("foobar");
		$expectedNode = '<title>foobar</title>';
		$this->assertContainsXmlSnipped($expectedNode, $this->product->getContent());
	}

	/**
	 * @test
	 */
	public function canGetContentAsXmlContainsImageLink() {
		$this->product->setImageLink('http://www.searchperience.com/test.gif');
		$expectedNode = '<image_link>http://www.searchperience.com/test.gif</image_link>';
		$this->assertContainsXmlSnipped($expectedNode, $this->product->getContent());
	}

	/**
	 * @test‚
	 */
	public function canGetContentContainsCategoryPath() {

		$productCategoryPath = new ProductCategoryPath();
		$productCategoryPath->setCategoryId(620);
		$productCategoryPath->setCategoryPathFromArray(array(
			'Home Page','Gran Gala della Moda','Look Camelia',
		));

		$this->product->addCategoryPath($productCategoryPath);

		$expectedNode = "<category_path>Home Page/Gran Gala della Moda/Look Camelia</category_path>".
						"<category_id>620</category_id>";

		$this->assertContainsXmlSnipped($expectedNode, $this->product->getContent());

	}
}