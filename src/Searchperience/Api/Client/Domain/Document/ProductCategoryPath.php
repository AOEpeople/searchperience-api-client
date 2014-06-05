<?php


namespace Searchperience\Api\Client\Domain\Document;

use Symfony\Component\Validator\Constraints as Assert;


/**
 * Class ProductCategoryPath
 * @package Searchperience\Api\Client\Domain\Document
 */
class ProductCategoryPath {

	/**
	 * @var integer
	 */
	protected $categoryId = 0;

	/**
	 * @var string
	 */
	protected $categoryPath = '/';

	/**
	 * @return int
	 */
	public function getCategoryId() {
		return $this->categoryId;
	}

	/**
	 * @param int $categoryId
	 */
	public function setCategoryId($categoryId) {
		$this->categoryId = $categoryId;
	}

	/**
	 * @return string
	 */
	public function getCategoryPath() {
		return $this->categoryPath;
	}

	/**
	 * Used to set the Categorypath.
	 *
	 * E.g.: Moda &amp; Accessori/Abbigliamento/Vestiti
	 *
	 * Note: "/" is used to sperate the segments. "/" inside of the segment needs to be
	 * escaped "\/". You can also use "setCategoryPathFromArray"
	 *
	 * @param string $categoryPath
	 */
	public function setCategoryPath($categoryPath) {
		$this->categoryPath = $categoryPath;
	}


	/**
	 * @param array $pathSegments
	 */
	public function setCategoryPathFromArray(array $pathSegments) {
		$escaped = array();
		foreach($pathSegments as $pathSegment) {
				//make sure it is unescaped
			$unescapedSegment = str_replace("\/","/", $pathSegment);
				//and now escape it
			$escapedSegment = str_replace("/","\/", $unescapedSegment);
			$escaped[] = $escapedSegment;
		}

		$this->setCategoryPath(implode("/", $escaped));
	}
}