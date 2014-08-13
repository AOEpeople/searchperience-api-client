<?php

namespace Searchperience\Api\Client\Domain\Document;

use Symfony\Component\Validator\Constraints as Assert;
use Searchperience\Api\Client\System\XMLContentMapper\ProductMapper;
use Searchperience\Common\Exception\InvalidArgumentException;


/**
 * @author Timo Schmidt <timo.schmidt@aoe.com>
 */
class Product extends AbstractDocument {

	/**
	 * @var string
	 */
	protected $mimeType = 'application/searchperience+xml';

	/**
	 * @var int
	 */
	protected $storeId = 0;

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
	 * @var string
	 */
	protected $language = '';

	/**
	 * @var string
	 */
	protected $imageLink = '';

	/**
	 * @var array
	 */
	protected $categoryPaths = array();

	/**
	 * @var ProductMapper
	 */
	protected $xmlMapper = null;

	/**
	 *
	 */
	public function __construct() {
		$this->xmlMapper = new ProductMapper();
	}

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
	public function getAvailability() {
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
	public function getLanguage() {
		return $this->language;
	}

	/**
	 * @param string $language
	 */
	public function setLanguage($language) {
		$this->language = $language;
	}

	/**
	 * @return int
	 */
	public function getStoreId() {
		return $this->storeId;
	}

	/**
	 * @param int $storeId
	 */
	public function setStoreId($storeId) {
		$this->storeId = $storeId;
	}

	/**
    * @return string
    */
	public function getImageLink() {
		return $this->imageLink;
	}

	/**
	 * @param string $imageLink
	 */
	public function setImageLink($imageLink) {
		$this->imageLink = $imageLink;
	}

	/**
	 * @return string
	 */
	public function getContent() {
		return $this->xmlMapper->toXML($this);
	}

	/**
	 * @return boolean
	 */
	public function afterReconstitution() {
		try {
			$this->xmlMapper->fromXML($this, $this->content);
			return true;
		} catch(InvalidArgumentException $e) {
			return false;
		}
	}

	/**
	 * @return array
	 */
	public function getAttributes() {
		return $this->attributes;
	}

	/**
	 * @param array<ProductAttribute> $attributes
	 */
	public function setAttributes($attributes) {
		$this->attributes = $attributes;
	}

	/**
	 * @param ProductAttribute $attribute
	 */
	public function addAttribute(ProductAttribute $attribute) {
		$this->attributes[$attribute->getName()] = $attribute;
	}

	/**
	 * @param $name
	 * @return ProductAttribute
	 */
	public function getAttributeByName($name) {
		return $this->attributes[$name];
	}

	/**
	 * @param ProductAttribute $attribute
	 */
	public function removeAttribute(ProductAttribute $attribute) {
		return $this->removeAttributeByName($attribute->getName());
	}

	/**
	 * @param $attributeName
	 */
	public function removeAttributeByName($attributeName) {
		if(array_key_exists($attributeName,$this->attributes)) {
			unset($this->attributes[$attributeName]);
		}
	}

	/**
	 * @return int
	 */
	public function getAttributeCount() {
		return count($this->attributes);
	}

	/**
	 * @return array
	 */
	public function getCategoryPaths() {
		return $this->categoryPaths;
	}

	/**
	 * @param ProductCategoryPath $categoryPath
	 */
	public function addCategoryPath(ProductCategoryPath $categoryPath) {
		$this->categoryPaths[$categoryPath->getCategoryId()] = $categoryPath;
	}

	/**
	 * @param ProductCategoryPath $categoryPath
	 */
	public function removeCategoryPath(ProductCategoryPath $categoryPath) {
		return $this->removeCategoryPathById($categoryPath->getCategoryId());
	}

	/**
	 * @param $id
	 */
	public function removeCategoryPathById($id) {
		if(array_key_exists($id,$this->categoryPaths)) {
			unset($this->categoryPaths[$id]);
		}
	}
}