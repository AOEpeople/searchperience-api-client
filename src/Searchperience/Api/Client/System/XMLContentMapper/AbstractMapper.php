<?php

namespace Searchperience\Api\Client\System\XMLContentMapper;

use Symfony\Component\Validator\Constraints as Assert;
use Searchperience\Api\Client\Domain\Document\Product;
use Searchperience\Api\Client\Domain\Document\ProductCategoryPath;
use Searchperience\Api\Client\Domain\Document\ProductAttribute;



/**
 * @author Timo Schmidt <timo.schmidt@aoe.com>
 */
abstract class AbstractMapper {

	/**
	 * @param $xpath
	 * @param $query
	 * @return string
	 */
	protected function getFirstNodeContent($xpath, $query) {
		$nodes = $xpath->query($query);
		if (!$nodes->length == 1) {
			return "";
		}

		return (string)$nodes->item(0)->textContent;
	}

}