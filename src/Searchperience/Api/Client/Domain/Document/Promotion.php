<?php

namespace Searchperience\Api\Client\Domain\Document;

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
	protected $contentDOM = null;

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
	 * @return \DOMDocument
	 */
	public function getContentDOM() {
		if(!$this->contentDOM instanceof \DOMDocument) {
			$this->contentDOM = new \DOMDocument('1.0','UTF-8');
			$this->contentDOM->loadXML($this->content);
		}

		return $this->contentDOM;
	}

	/**
	 * @return string|void
	 */
	public function getContent() {
		$dom        = new \DOMDocument('1.0','UTF-8');
		$promotion  = $dom->createElement('promotion');

		$title = $dom->createElement('title',$this->getPromotionTitle());
		$promotion->appendChild($title);

		$type = $dom->createElement('type',$this->getPromotionType());
		$promotion->appendChild($type);

		$image = $dom->createElement('image',$this->getImageUrl());
		$promotion->appendChild($image);

		$searchTerms = $dom->createElement('searchterms');
		foreach($this->getKeywords() as $keyWord) {
			$keyWordNode = $dom->createElement('searchterm',$keyWord);
			$searchTerms->appendChild($keyWordNode);
		}
		$promotion->appendChild($searchTerms);

		$solrFieldValues = $dom->createElement('solrfieldvalues');
		foreach($this->getFieldValues() as $fieldName => $fieldValue) {
			$solrFieldValue = $dom->createElement('solrfieldvalue', $fieldValue);
			$fieldNameAttribute = $dom->createAttribute('fieldname');
			$attributeValue = $dom->createTextNode($fieldName);
			$fieldNameAttribute->appendChild($attributeValue);

			$solrFieldValue->appendChild($fieldNameAttribute);
			$solrFieldValues->appendChild($solrFieldValue);
		}

		$promotion->appendChild($solrFieldValues);
		$content = $dom->createElement('content');
		$cdata = $dom->createCDATASection($this->getPromotionContent());
		$content->appendChild($cdata);
		$promotion->appendChild($content);
		$dom->appendChild($promotion);
		return $dom->saveXML();
	}

	/**
	 * This method is used to set the properties back from the xml body to the domain object
	 *
	 * @return void
	 */
	public function afterReconstitution() {
		$xpath = new \DOMXPath($this->getContentDOM());
		$this->promotionTitle = $this->getFirstNodeContent($xpath,'//title');
		$this->promotionType = $this->getFirstNodeContent($xpath,'//type');
		$this->imageUrl = $this->getFirstNodeContent($xpath,'//image');

		$searchTerms = $xpath->query("//searchterm");
		foreach($searchTerms as $searchTerm) {
			$this->keywords[] = $searchTerm->textContent;
		}

		$solrFieldValues = $xpath->query("//solrfieldvalue");
		foreach($solrFieldValues as $solrFieldValue) {
				/** @var $solrFieldValue \DOMElement */
			$fieldName = $solrFieldValue->getAttribute("fieldname");
			$this->fieldValues[$fieldName] = $solrFieldValue->textContent;
		}

		$this->promotionContent = $this->getFirstNodeContent($xpath,'//content');
	}

	/**
	 * @param $xpath
	 * @param $query
	 * @return string
	 */
	protected function getFirstNodeContent($xpath, $query) {
		$titleNodes = $xpath->query($query);
		if (!$titleNodes->length == 1) {
			return "";
		}

		return (string)$titleNodes->item(0)->textContent;
	}
}