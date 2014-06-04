<?php

namespace Searchperience\Api\Client\Domain\Document;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Timo Schmidt <timo.schmidt@aoe.com>
 */
class Product extends AbstractDocument {

	/**
	 * @var string
	 */
	protected $mimeType = 'application/searchperience+xml';

	/**
	 * @var string
	 */
	protected $description = '';


	/**
	 * @var string
	 */
	protected $shortDescription = '';

	/**
	 * @var int
	 */
	protected $productId = 0;

	/**
	 * @var string
	 */
	protected $sku = '';

	/**
	 * @var string
	 */
	protected $title = '';

	/**
	 * @var string
	 */
	protected $price = 0.0;

	/**
	 * @var float
	 */
	protected $specialPrice = 0.0;

	/**
	 * @var float
	 */
	protected $groupPrice = 0.0;

	/**
	 * @var
	 */
	protected $attributes = array();

	/**
	 * @var bool
	 */
	protected $availability = true;

	/**
	 * @return float
	 */
	public function getGroupPrice() {
		return $this->groupPrice;
	}

	/**
	 * @param float $groupPrice
	 */
	public function setGroupPrice($groupPrice) {
		$this->groupPrice = $groupPrice;
	}

	/**
	 * @return int
	 */
	public function getProductId() {
		return $this->productId;
	}

	/**
	 * @param int $productId
	 */
	public function setProductId($productId) {
		$this->productId = $productId;
	}

	/**
	 * @return string
	 */
	public function getPrice() {
		return $this->price;
	}

	/**
	 * @param string $price
	 */
	public function setPrice($price) {
		$this->price = $price;
	}

	/**
	 * @return string
	 */
	public function getShortDescription() {
		return $this->shortDescription;
	}

	/**
	 * @param string $shortDescription
	 */
	public function setShortDescription($shortDescription) {
		$this->shortDescription = $shortDescription;
	}

	/**
	 * @return string
	 */
	public function getSku() {
		return $this->sku;
	}

	/**
	 * @param string $sku
	 */
	public function setSku($sku) {
		$this->sku = $sku;
	}

	/**
	 * @return float
	 */
	public function getSpecialPrice() {
		return $this->specialPrice;
	}

	/**
	 * @param float $specialPrice
	 */
	public function setSpecialPrice($specialPrice) {
		$this->specialPrice = $specialPrice;
	}

	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @param string $title
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @param string $description
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * @return boolean
	 */
	public function isAvailability() {
		return $this->availability;
	}

	/**
	 * @param boolean $availability
	 */
	public function setAvailability($availability) {
		$this->availability = $availability;
	}

	/**
	 * @return string
	 */
	public function getContent() {
		$dom      = new \DOMDocument('1.0','UTF-8');
		$product  = $dom->createElement('product');

		foreach($this->attributes as $productAttribute) {
				/** @var $productAttribute ProductAttribute */
			$attributeNode = $dom->createElement('attribute');
			$attributeNode->setAttribute("name",$productAttribute->getName());
			$attributeNode->setAttribute("type",$productAttribute->getType());

			if($productAttribute->getForFaceting()) {
				$attributeNode->setAttribute("forfaceting",1);
			}

			if($productAttribute->getForSearching()) {
				$attributeNode->setAttribute("forearching",1);
			}

			if($productAttribute->getForSorting()) {
				$attributeNode->setAttribute("forsotring",1);
			}

			$values = $productAttribute->getValues();

			foreach($values as $value) {
				$valueNode      = $dom->createElement('value');
				$valueTextNode  = $dom->createTextNode($value);
				$valueNode->appendChild($valueTextNode);
			}

			$attributeNode->appendChild($valueNode);
			$product->appendChild($attributeNode);
		}

		$dom->appendChild($product);
		return $dom->saveXML();
	}

	/**
	 * @param ProductAttribute $attribute
	 */
	public function addAttribute(ProductAttribute $attribute) {
		$this->attributes[$attribute->getName()] = $attribute;
	}

	/**
	 * @return int
	 */
	public function getAttributeCount() {
		return count($this->attributes);
	}
}