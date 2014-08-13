<?php

namespace Searchperience\Tests\Api\Client\Document\System\Storage;
use Searchperience\Api\Client\Domain\Document\Product;
use Searchperience\Api\Client\System\XMLContentMapper\ProductMapper;

/**
 * @author Timo Schmidt <timo.schmidt@aoe.com>
‚ */
class ProductMapperTest extends \Searchperience\Tests\BaseTestCase {

	/**
	 * @var \Searchperience\Api\Client\System\XMLContentMapper\ProductMapper
	 */
	protected $mapper;

	/**
	 * @return void
	 */
	public function setUp() {
		$this->mapper = new ProductMapper();
	}

	/**
	 * @test
	 */
	public function canRoundTripConvert() {
		$product = new Product();
		$fixture = $this->getFixtureContent('Api/Client/System/XMLContentMapper/Fixture/product.xml');
		$this->mapper->fromXML($product,$fixture);
		$color = $product->getAttributeByName('color');
		$this->assertEquals(1,count($color->getValues()),'Reconstituted product with unexpected amount of attributes');
		$xmlOut = $this->mapper->toXML($product);
		$cleanFixture = $this->cleanSpaces($fixture);
		$cleanXml = $this->cleanSpaces($xmlOut);
		$this->assertEquals($cleanFixture, $cleanXml);
	}
}