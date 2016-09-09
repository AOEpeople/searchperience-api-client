<?php

namespace Searchperience\Api\Client\System\XMLContentMapper;

use Searchperience\Common\Exception\InvalidArgumentException;
use Symfony\Component\Validator\Constraints as Assert;
use Searchperience\Api\Client\Domain\Document\Promotion;

/**
 * @author Timo Schmidt <timo.schmidt@aoe.com>
 */
class PromotionMapper extends AbstractMapper {

	/**
	 * @param Promotion $promotion
	 * @return string
	 */
	public function toXML(Promotion $promotion) {
		$dom        = new \DOMDocument('1.0','UTF-8');
		$promotionNode  = $dom->createElement('promotion');

		$title = $dom->createElement('title',$promotion->getPromotionTitle());
		$promotionNode->appendChild($title);

		$type = $dom->createElement('type',$promotion->getPromotionType());
		$promotionNode->appendChild($type);

		$image = $dom->createElement('image',$promotion->getImageUrl());
		$promotionNode->appendChild($image);

		if(trim($promotion->getLanguage()) !== '') {
			$language = $dom->createElement('language',$promotion->getLanguage());
			$promotionNode->appendChild($language);
		}

		$searchTerms = $dom->createElement('searchterms');
		foreach($promotion->getKeywords() as $keyWord) {
			$keyWordNode = $dom->createElement('searchterm',$keyWord);
			$searchTerms->appendChild($keyWordNode);
		}
		$promotionNode->appendChild($searchTerms);

		$solrFieldValues = $dom->createElement('solrfieldvalues');

		foreach($promotion->getFieldValues() as $fieldName => $fieldValue) {
			$solrFieldValue = $dom->createElement('solrfieldvalue', $fieldValue);
			$fieldNameAttribute = $dom->createAttribute('fieldname');
			$attributeValue = $dom->createTextNode($fieldName);
			$fieldNameAttribute->appendChild($attributeValue);

			$solrFieldValue->appendChild($fieldNameAttribute);
			$solrFieldValues->appendChild($solrFieldValue);
		}

		$limitedTimeFrom = $dom->createElement('limitedTimeFrom', $promotion->getLimitedTimeFrom());
		$promotionNode->appendChild($limitedTimeFrom);

		$limitedTimeTo = $dom->createElement('limitedTimeTo', $promotion->getLimitedTimeTo());
		$promotionNode->appendChild($limitedTimeTo);

		$promotionNode->appendChild($solrFieldValues);
		$content = $dom->createElement('content');
		$cdata = $dom->createCDATASection($promotion->getPromotionContent());
		$content->appendChild($cdata);
		$promotionNode->appendChild($content);
		$dom->appendChild($promotionNode);

		return $dom->saveXML();
	}

	/**
	 * @param Promotion $promotion
	 * @param string $contentXML
	 * @throws \Searchperience\Common\Exception\InvalidArgumentException;
	 */
	public function fromXML(Promotion $promotion, $contentXML) {
		$contentDOM = new \DOMDocument('1.0', 'UTF-8');
		$result 	= @$contentDOM->loadXML($contentXML);

		if($result === false) {
			throw new InvalidArgumentException("No xml content: ".$contentXML);
		}

		$xpath = new \DOMXPath($contentDOM);
		$promotion->__setProperty('promotionTitle', $this->getFirstNodeContent($xpath,'//title'));
		$promotion->__setProperty('promotionType', $this->getFirstNodeContent($xpath,'//type'));
		$promotion->__setProperty('imageUrl', $this->getFirstNodeContent($xpath,'//image'));
		$promotion->__setProperty('language', $this->getFirstNodeContent($xpath,'//language'));
		$promotion->__setProperty('limitedTimeFrom', $this->getFirstNodeContent($xpath,'//limitedTimeFrom'));
		$promotion->__setProperty('limitedTimeTo', $this->getFirstNodeContent($xpath,'//limitedTimeTo'));

		$searchTerms = $xpath->query("//searchterm");
		$keywords = array();

		foreach($searchTerms as $searchTerm) {
			$keywords[] = $searchTerm->textContent;
		}

		$promotion->__setProperty('keywords', $keywords);

		$solrFieldValues = $xpath->query("//solrfieldvalue");
		$fieldValues = array();

		foreach($solrFieldValues as $solrFieldValue) {
			/** @var $solrFieldValue \DOMElement */
			$fieldName = $solrFieldValue->getAttribute("fieldname");
			$fieldValues[$fieldName] = $solrFieldValue->textContent;
		}

		$promotion->__setProperty('fieldValues',$fieldValues);
		$promotion->__setProperty('promotionContent', $this->getFirstNodeContent($xpath,'//content') );
	}
}
