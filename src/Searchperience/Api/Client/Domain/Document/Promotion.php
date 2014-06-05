<?php

namespace Searchperience\Api\Client\Domain\Document;

use Searchperience\Api\Client\System\XMLContentMapper\PromotionMapper;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Timo Schmidt <timo.schmidt@aoe.com>
 */
class Promotion extends AbstractDocument {

	/**
	 * Used for organic promotions
	 */
	const TYPE_ORGANIC = 'organic';

	/**
	 * Used for seperated promotions
	 */
	const TYPE_PROMOTION = 'promotion';

	/**
	 * @var string
	 */
	protected $mimeType = 'text/searchperiencepromotion+xml';

	/**
	 * @var string
	 */
	protected $promotionTitle = '';

	/**
	 * @var string
	 */
	protected $promotionContent = '';

	/**
	 * @var \DOMDocument
	 */
	protected $promotionContentDOM = null;

	/**
	 * @var string
	 */
	protected $promotionType = self::TYPE_PROMOTION;

	/**
	 * @var string
	 */
	protected $imageUrl;

	/**
	 * @var array
	 */
	protected $keywords = array();

	/**
	 * @var array
	 */
	protected $fieldValues = array();

	/**
	 * @var PromotionMapper
	 */
	protected $xmlMapper = null;

	/**
	 *
	 */
	public function __construct() {
		$this->xmlMapper = new PromotionMapper();
	}

	/**
	 * @return string
	 */
	public function getPromotionTitle() {
		return $this->promotionTitle;
	}

	/**
	 * @param string $promotionTitle
	 */
	public function setPromotionTitle($promotionTitle) {
		$this->promotionTitle = $promotionTitle;
	}

	/**
	 * @param string $fieldName
	 * @param mixed $fieldValue
	 */
	public function addFieldValue($fieldName, $fieldValue) {
		$this->fieldValues[$fieldName] = $fieldValue;
	}

	/**
	 * @return array
	 */
	public function getFieldValues() {
		return $this->fieldValues;
	}

	/**
	 * @param array $fieldValues
	 */
	public function setFieldValues($fieldValues) {
		$this->fieldValues = $fieldValues;
	}

	/**
	 * @return string
	 */
	public function getPromotionType(){
		return $this->promotionType;
	}

	/**
	 * @param string $promotionType
	 */
	public function setPromotionType($promotionType) {
		$this->promotionType = $promotionType;
	}

	/**
	 * @return string
	 */
	public function getPromotionContent() {
		return $this->promotionContent;
	}

	/**
	 * @param string $promotionContent
	 */
	public function setPromotionContent($promotionContent) {
		$this->promotionContent = $promotionContent;
	}

	/**
	 * @return array
	 */
	public function getKeywords() {
		return $this->keywords;
	}

	/**
	 * @param array $keywords
	 */
	public function setKeywords($keywords) {
		$this->keywords = $keywords;
	}

	/**
	 * @param string $keyword
	 */
	public function addKeyword($keyword) {
		$this->keywords[$keyword] = $keyword;
	}

	/**
	 * @return string
	 */
	public function getImageUrl() {
		return $this->imageUrl;
	}

	/**
	 * @param string $imageUrl
	 */
	public function setImageUrl($imageUrl) {
		$this->imageUrl = $imageUrl;
	}

	/**
	 * This method is used to ret
	 *
	 * @return \DOMDocument
	 */
	public function getPromotionContentDOM() {
		$contentDOM = $this->getContentDOM();
		$xpath = new \DOMXPath($contentDOM);

		$list = $xpath->query("//promotion/content");

		$this->promotionContentDOM = new \DOMDocument('1.0','UTF-8');
		$this->promotionContentDOM->loadXML($list->item(0)->textContent);

		return $this->promotionContentDOM;
	}

	/**
	 * @return string
	 */
	public function getContent() {
		return $this->xmlMapper->toXML($this);
	}

	/**
	 * @return void
	 */
	public function afterReconstitution() {
		return $this->xmlMapper->fromXML($this, $this->content);
	}
}