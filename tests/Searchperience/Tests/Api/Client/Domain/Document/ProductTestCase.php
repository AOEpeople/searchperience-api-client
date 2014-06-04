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
		$attribute->setValue("foo");
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
}