<?php

namespace Searchperience\Tests\Api\Client\Document;
use Searchperience\Api\Client\Domain\Document\Product;
use Searchperience\Api\Client\Domain\Document\ProductAttribute;

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

		$this->assertContains(
			$this->cleanSpaces($expectedNode),
			$this->cleanSpaces($this->product->getContent()),
			'Did not find attribute snipped in xml'
		);
	}

	/**
	 * @test
	 */
	public function canGetContentAsXmlContainsDescription() {
		$this->product->setDescription("hello");
		$expectedNode = '<description>hello</description>';
		$this->assertContains(
			$this->cleanSpaces($expectedNode),
			$this->cleanSpaces($this->product->getContent()),
			'Did not find description snipped in xml'
		);
	}

	/**
	 * @test
	 */
	public function canGetContentAsXmlContainsAvailability() {
		$this->product->setAvailability(true);
		$expectedNode = '<availability>1</availability>';
		$this->assertContains(
			$this->cleanSpaces($expectedNode),
			$this->cleanSpaces($this->product->getContent()),
			'Did not find availability snipped in xml'
		);
	}

	/**
	 * @test
	 */
	public function canGetContentAsXmlContainsPrice() {
		$this->product->setPrice(1.10);
		$expectedNode = '<price>1.10</price>';
		$this->assertContains(
			$this->cleanSpaces($expectedNode),
			$this->cleanSpaces($this->product->getContent()),
			'Did not find availability snipped in xml'
		);
	}

	/**
	 * @test
	 */
	public function canGetContentAsXmlContainsLanguage() {
		$this->product->setLanguage("de_DE");
		$expectedNode = '<language>de_DE</language>';
		$this->assertContains(
			$this->cleanSpaces($expectedNode),
			$this->cleanSpaces($this->product->getContent()),
			'Did not find language snipped in xml'
		);
	}
}