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
		$expectedNode = '<storeid>100</storeid>';
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
	 * @test
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

	/**
	 * @test
	 */
	public function canUseSpecialCharactersInTitle() {
		$testTitle = "Foobar™ is registered in the U.S. Patent and Trademark Office.";
		$this->product->setTitle($testTitle);
		$expectedNode = "<title>Foobar™ is registered in the U.S. Patent and Trademark Office.</title>";

		$this->assertContainsXmlSnipped($expectedNode, $this->product->getContent());
	}

	/**
	 * @test
	 */
	public function canGetSameDocumentAsFromFixture() {
		$expectedXml  = $this->getFixtureContent('Api/Client/Domain/Document/Fixture/testproduct.xml');

		$this->product->setProductId(118948);
		$this->product->setStoreId(1);
		$this->product->setLanguage("de_DE");
		$this->product->setAvailability(true);
		$this->product->setSku(103115);
		$this->product->setTitle("Foo");
		$this->product->setDescription("long description");
		$this->product->setShortDescription("short description");
		$this->product->setPrice(42.36);
		$this->product->setSpecialPrice(100);
		$this->product->setGroupPrice(101);
		$this->product->setImageLink('http://www.searchperience.de/test.gif');

		$categoryPath1 = new ProductCategoryPath();
		$categoryPath1->setCategoryId(7);
		$categoryPath1->setCategoryPath("Moda &amp; Accessori");
		$this->product->addCategoryPath($categoryPath1);

		$categoryPath2 = new ProductCategoryPath();
		$categoryPath2->setCategoryId(36);
		$categoryPath2->setCategoryPath("Moda &amp; Accessori/Abbigliamento");
		$this->product->addCategoryPath($categoryPath2);

		$attribute1 = new ProductAttribute();
		$attribute1->setName('color');
		$attribute1->setType(ProductAttribute::TYPE_TEXT);
		$attribute1->setForFaceting(true);
		$attribute1->setForSorting(true);
		$attribute1->setForSearching(true);
		$attribute1->addValue("red");
		$this->product->addAttribute($attribute1);

		$attribute2 = new ProductAttribute();
		$attribute2->setName('defaults');
		$attribute2->setType(ProductAttribute::TYPE_STRING);
		$attribute2->addValue("i like searchperience");
		$attribute2->addValue("foobar");
		$this->product->addAttribute($attribute2);

		$generatedXml = $this->product->getContent();
		$this->assertXmlStringEqualsXmlString($expectedXml,$generatedXml,'Method getContent on product did not produce expected result');
	}

	/**
	 * @test
	 */
	public function canGetAttributeByName() {
		$attribute1 = new ProductAttribute();
		$attribute1->setName('color');
		$attribute1->setType(ProductAttribute::TYPE_TEXT);
		$attribute1->setForFaceting(true);
		$attribute1->setForSorting(true);
		$attribute1->setForSearching(true);
		$attribute1->addValue("red");
		$this->product->addAttribute($attribute1);

		$attribute2 = new ProductAttribute();
		$attribute2->setName('defaults');
		$attribute2->setType(ProductAttribute::TYPE_STRING);
		$attribute2->addValue("i like searchperience");
		$attribute2->addValue("foobar");
		$this->product->addAttribute($attribute2);

		$this->assertEquals($attribute2, $this->product->getAttributeByName("defaults"));
	}

	/**
	 * @test
	 */
	public function canInitializeProductFromProductXml() {
		$expectedXml  = $this->getFixtureContent('Api/Client/Domain/Document/Fixture/testproduct.xml');
		$this->product->__setProperty('content',$expectedXml);
		$this->product->afterReconstitution();

		$this->assertSame(118948, $this->product->getProductId(),'Could not get product with expected productId');
		$this->assertSame(1, $this->product->getStoreId(),'Could not restore storeId');
		$this->assertSame("de_DE", $this->product->getLanguage(),'Could not restore language');
		$this->assertSame("103115", $this->product->getSku(),'Could not restore sku');
		$this->assertSame("Foo", $this->product->getTitle(),'Could not restore title');
		$this->assertSame("long description", $this->product->getDescription(),'Could not restore description');
		$this->assertSame("short description", $this->product->getShortDescription(),'Could not restore short description');
		$this->assertSame(42.36, $this->product->getPrice(),'Could not restore price');
		$this->assertSame(100.00, $this->product->getSpecialPrice(), 'Could not get special price');
		$this->assertSame(101.00, $this->product->getGroupPrice(), 'Could not get group price');
		$this->assertSame('http://www.searchperience.de/test.gif', $this->product->getImageLink(),'Could not restore image link');
		$this->assertSame(2, count($this->product->getCategoryPaths()),'Could not restore category pathes');
		$this->assertSame(2, count($this->product->getAttributes()),'Could not restore attributes');

	}

	/**
	 * @test
	 */
	public function afterReconstitutionIsNotThrowingErrorWithEmptyContent() {
		$this->product->afterReconstitution();
	}
}