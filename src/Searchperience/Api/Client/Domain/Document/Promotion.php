<?php

namespace Searchperience\Api\Client\Domain\Document;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Timo Schmidt <timo.schmidt@aoe.com>
 */
class Promotion extends Document {

	/**
	 * @var string
	 */
	protected $mimeType = 'text/searchperiencepromotion+xml';

	/**
	 * @var string
	 */
	protected $promotionContent;

	/**
	 * @var string
	 */
	protected $promotionType;

	/**
	 * @var
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
	 * @param string $fieldName
	 * @param mixed $fieldValue
	 */
	public function addFieldValue($fieldName, $fieldValue) {

	}

	/**
	 * @return string|void
	 */
	public function getContent() {
		//build the xml
	}
}