<?php

namespace Searchperience\Tests\Api\Client\Document;
use Searchperience\Api\Client\Domain\Document\Promotion;

/**
 * @author Timo Schmidt <timo.schmidt@aoe.com>
 * @date 05.05.2014
 * @time 17:50
 */
class PromotionTestCase extends \Searchperience\Tests\BaseTestCase {

	/**
	 * @var \Searchperience\Api\Client\Domain\Document\Promotion
	 */
	protected $promotion;

	/**
	 * Initialize test environment
	 *
	 * @return void
	 */
	public function setUp() {
		$this->promotion = new \Searchperience\Api\Client\Domain\Document\Promotion();
	}

	/**
	 * Cleanup test environment
	 *
	 * @return void
	 */
	public function tearDown() {
		$this->promotion = null;
	}

	/**
	 * @test
	 */
	public function canGetXmlContent() {

		$expectedContent = $this->cleanSpaces(
		'<?xml version="1.0" encoding="UTF-8"?><promotion>
			<title>test</title>
			<type>organic</type>
			<image>http://www.foobar.de/test.gif</image>
			<searchterms>
				<searchterm>one</searchterm>
				<searchterm>two</searchterm>
			</searchterms>
			<solrfieldvalues>
				<solrfieldvalue fieldname="test">Fooobar</solrfieldvalue>
			</solrfieldvalues>
			<content><![CDATA[
				<html>
					<head></head>
					<body><![CDATA[test]]]]><![CDATA[></body>
				</html>
			]]></content>
		</promotion>');


		$this->promotion->setPromotionType(Promotion::TYPE_ORGANIC);
		$this->promotion->setImageUrl('http://www.foobar.de/test.gif');
		$this->promotion->setPromotionTitle('test');
		$this->promotion->addKeyword('one');
		$this->promotion->addKeyword('two');
		$this->promotion->addFieldValue('test','Fooobar');
		$this->promotion->setPromotionContent('<html><head></head><body><![CDATA[test]]></body></html>');

		$content = $this->cleanSpaces( $this->promotion->getContent() );
		$this->assertEquals($content,$expectedContent,'Could not build xml from promotion as expected');
	}

	/**
	 * @test
	 */
	public function canGetContentDOM() {
		$content = '<?xml version="1.0" encoding="UTF-8"?><promotion>
			<title>test</title>
			<type>organic</type>
			<image>http://www.foobar.de/test.gif</image>
			<searchterms>
				<searchterm>one</searchterm>
				<searchterm>two</searchterm>
			</searchterms>
			<solrfieldvalues>
				<solrfieldvalue fieldname="test">Fooobar</solrfieldvalue>
			</solrfieldvalues>
			<content><![CDATA[
				<html>
					<head></head>
					<body><![CDATA[test]]]]><![CDATA[></body>
				</html>
			]]></content>
		</promotion>';

		$this->promotion->__setProperty('content',$content);
		$this->assertInstanceOf('\DOMDocument',$this->promotion->getContentDOM(),'Could not dom object from content');
		$xpath = new \DOMXPath($this->promotion->getContentDOM());
		$typePath = $xpath->query("//type");
		$this->assertEquals((string) $typePath->item(0)->textContent,"organic");
	}

	/**
	 * @test
	 */
	public function canGetPromotionContentDOM() {
		$content = '<?xml version="1.0" encoding="UTF-8"?><promotion>
			<title>test</title>
			<type>organic</type>
			<image>http://www.foobar.de/test.gif</image>
			<searchterms>
				<searchterm>one</searchterm>
				<searchterm>two</searchterm>
			</searchterms>
			<solrfieldvalues>
				<solrfieldvalue fieldname="test">Fooobar</solrfieldvalue>
			</solrfieldvalues>
			<content><![CDATA[
				<html>
					<head></head>
					<body><![CDATA[test]]]]><![CDATA[></body>
				</html>
			]]></content>
		</promotion>';

		$this->promotion->__setProperty('content',$content);
		$this->assertInstanceOf('\DOMDocument',$this->promotion->getPromotionContentDOM(),'Could not dom object from content');
		$xpath = new \DOMXPath($this->promotion->getPromotionContentDOM());
		$typePath = $xpath->query("//body");
		$this->assertEquals(trim((string) $typePath->item(0)->textContent),"test","Could not get body content from promotion content");
	}
}