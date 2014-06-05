<?php

namespace Searchperience\Api\Client\System\XMLContentMapper;

use Symfony\Component\Validator\Constraints as Assert;
use Searchperience\Api\Client\Domain\Document\Product;
use Searchperience\Api\Client\Domain\Document\ProductCategoryPath;
use Searchperience\Api\Client\Domain\Document\ProductAttribute;



/**
 * @author Timo Schmidt <timo.schmidt@aoe.com>
 */
class ProductMapper extends AbstractMapper {

	/**
	 * @param Product $product
	 */
	public function toXML(Product $product) {
		$dom      = new \DOMDocument('1.0','UTF-8');
		$productNode  = $dom->createElement('product');

		$this->addCommonNodesToContentDom($dom, $productNode, $product);
		$this->addCategoryPathNodesToContentDom($dom, $productNode, $product);
		$this->addAttributeNodesToContentDom($dom, $productNode , $product);

		$dom->appendChild($productNode);
		return $dom->saveXML();
	}

	/**
	 * @param $dom
	 * @param $productNode
	 * @param Product $product
	 */
	protected function addAttributeNodesToContentDom($dom, \DOMElement $productNode, Product $product) {
		foreach ($product->getAttributes() as $productAttribute) {
			/** @var $productAttribute ProductAttribute */
			$attributeNode = $dom->createElement('attribute');
			$attributeNode->setAttribute("name", $productAttribute->getName());
			$attributeNode->setAttribute("type", $productAttribute->getType());

			if ($productAttribute->getForSearching()) {
				$attributeNode->setAttribute("forsearching", 1);
			}

			if ($productAttribute->getForSorting()) {
				$attributeNode->setAttribute("forsorting", 1);
			}

			if ($productAttribute->getForFaceting()) {
				$attributeNode->setAttribute("forfaceting", 1);
			}

			$values = $productAttribute->getValues();

			foreach ($values as $value) {
				$valueNode = $dom->createElement('value');
				$valueTextNode = $dom->createTextNode($value);
				$valueNode->appendChild($valueTextNode);
				$attributeNode->appendChild($valueNode);
			}

			$productNode->appendChild($attributeNode);
		}
	}

	/**
	 * @param $dom
	 * @param $productNode
	 * @param Product $product
	 */
	protected function addCommonNodesToContentDom($dom, \DOMElement $productNode, Product  $product) {
		$productIdNode = $dom->createElement("id", $product->getProductId());
		$productNode->appendChild($productIdNode);

		$storeIdNode = $dom->createElement("storeid", $product->getStoreId());
		$productNode->appendChild($storeIdNode);

		$languageNode = $dom->createElement("language", $product->getLanguage());
		$productNode->appendChild($languageNode);

		$availabilityNode = $dom->createElement("availability", $product->getAvailability() ? 1 : 0);
		$productNode->appendChild($availabilityNode);

		$skuNode = $dom->createElement("sku", $product->getSku());
		$productNode->appendChild($skuNode);

		$titleNode = $dom->createElement("title", $product->getTitle());
		$productNode->appendChild($titleNode);

		$descriptionNode = $dom->createElement("description", $product->getDescription());
		$productNode->appendChild($descriptionNode);

		$descriptionNode = $dom->createElement("short_description", $product->getShortDescription());
		$productNode->appendChild($descriptionNode);

		$priceNode = $dom->createElement("price", number_format($product->getPrice(), 2));
		$productNode->appendChild($priceNode);

		$specialPriceNode = $dom->createElement("special_price", number_format($product->getSpecialPrice(), 2));
		$productNode->appendChild($specialPriceNode);

		$groupPriceNode = $dom->createElement("group_price", number_format($product->getGroupPrice(), 2));
		$productNode->appendChild($groupPriceNode);

		$imageLink = $dom->createElement("image_link", $product->getImageLink());
		$productNode->appendChild($imageLink);
	}

	/**
	 * @param $dom
	 * @param $productNode
	 * @param Product $product
	 */
	protected function addCategoryPathNodesToContentDom($dom, \DOMElement $productNode,  Product $product) {
		foreach($product->getCategoryPaths() as $categoryPath) {
			/** @var $categoryPath \Searchperience\Api\Client\Domain\Document\ProductCategoryPath */

			$categoryPathNode = $dom->createElement("category_path", $categoryPath->getCategoryPath());
			$productNode->appendChild($categoryPathNode);

			$categoryIdNode = $dom->createElement("category_id", $categoryPath->getCategoryId());
			$productNode->appendChild($categoryIdNode);
		}
	}

	/**
	 * @param Product $product
	 * @param $contentXML
	 */
	public function fromXML(Product $product, $contentXML) {
		$contentDOM = new \DOMDocument('1.0','UTF-8');
		$contentDOM->loadXML($contentXML);
		$xpath = new \DOMXPath($contentDOM);

		$this->restoreCommonPropertiesFromDOMContent($xpath, $product);
		$this->restoreCategoryPathsFromDOMContent($xpath, $product);
		$this->restoreAttributesFromDOMContent($xpath, $product);
	}

	/**
	 * @param $xpath
	 * @param Product $product
	 */
	protected function restoreCommonPropertiesFromDOMContent($xpath, Product $product) {
		$product->__setProperty('productId', (int)$this->getFirstNodeContent($xpath, '//id'));

		$storeNode = $this->getFirstNodeContent($xpath, '//storeid');
		if (trim($storeNode) != "") {
			$product->__setProperty('storeId', (int) $storeNode);
		}

		$languageNode = $this->getFirstNodeContent($xpath, '//language');
		if (trim($languageNode) != "") {
			$product->__setProperty('language', (string) $languageNode);
		}

		$availabilityNode = $this->getFirstNodeContent($xpath, '//availability');
		if (trim($availabilityNode) != "") {
			$product->__setProperty('availability', (boolean) $availabilityNode);
		}

		$skuNode = $this->getFirstNodeContent($xpath, '//sku');
		if (trim($skuNode) != "") {
			$product->__setProperty('sku',(string) $skuNode);
		}

		$titleNode = $this->getFirstNodeContent($xpath, '//title');
		if (trim($titleNode) != "") {
			$product->__setProperty('title',(string) $titleNode);
		}

		$descriptionNode = $this->getFirstNodeContent($xpath, '//description');
		if (trim($descriptionNode) != "") {
			$product->__setProperty('description',(string) $descriptionNode );
		}

		$shortDescriptionNode = $this->getFirstNodeContent($xpath, '//short_description');
		if (trim($shortDescriptionNode) != "") {
			$product->__setProperty('shortDescription',(string) $shortDescriptionNode);
		}

		$priceNode = $this->getFirstNodeContent($xpath, '//price');
		if (trim($priceNode) != "") {
			$product->__setProperty('price',(float) $priceNode);
		}

		$specialPriceNode = $this->getFirstNodeContent($xpath, '//special_price');
		if (trim($specialPriceNode) != "") {
			$product->__setProperty('specialPrice',(float)$specialPriceNode);
		}

		$groupPriceNode = $this->getFirstNodeContent($xpath, '//group_price');
		if (trim($groupPriceNode) != "") {
			$product->__setProperty('groupPrice',(float)$groupPriceNode);
		}

		$imageLinkNode = $this->getFirstNodeContent($xpath, '//image_link');
		if (trim($imageLinkNode) != "") {
			$product->__setProperty('imageLink', $imageLinkNode);
		}
	}

	/**
	 * @param $xpath
	 * @param Product $product
	 */
	protected function restoreCategoryPathsFromDOMContent($xpath, Product $product) {
		$categoryPaths      = array();
		$categoryPathNodes  = $xpath->query("//category_path");

		foreach ($categoryPathNodes as $categoryPathNode) {
			/** @var $node \DOMElement */
			$productCategoryPath = new ProductCategoryPath();
			$productCategoryPath->setCategoryPath((string)$categoryPathNode->textContent);

			for ($i = 0; $i < 5; $i++) {
				if (!isset($categoryPathNode->nextSibling)) {
					break;
				}
				$categoryPathNode = $categoryPathNode->nextSibling;
				if ($categoryPathNode->nodeName == 'category_id') {
					$productCategoryPath->setCategoryId((int)$categoryPathNode->textContent);
					break;
				}
			}

			$categoryPaths[$productCategoryPath->getCategoryId()] = $productCategoryPath;
		}

		$product->__setProperty('categoryPaths',$categoryPaths);
	}

	/**
	 * @param $xpath
	 * @param Product $product
	 */
	protected function restoreAttributesFromDOMContent($xpath, Product $product) {
		$attributeNodes = $xpath->query("//attribute");
		$attributes = array();

		foreach ($attributeNodes as $attributeNode) {
			/** @var $attributeNode \DOMElement */
			$name = $attributeNode->getAttribute("name");
			$type = $attributeNode->getAttribute("type");
			$forsearching = $attributeNode->getAttribute("forsearching") == "1";
			$forfaceting = $attributeNode->getAttribute("forfaceting") == "1";
			$forsorting = $attributeNode->getAttribute("forsorting") == "1";

			$attribute = new ProductAttribute();
			$attribute->setName($name);
			$attribute->setType($type);
			$attribute->setForSearching($forsearching);
			$attribute->setForFaceting($forfaceting);
			$attribute->setForSorting($forsorting);

			$values = $xpath->query("//value", $attributeNode);
			foreach ($values as $value) {
				/** @var $value \DOMElement */
				$attribute->addValue($value->textContent);
			}

			$attributes[$attribute->getName()] = $attribute;
		}

		$product->__setProperty('attributes', $attributes);
	}
}