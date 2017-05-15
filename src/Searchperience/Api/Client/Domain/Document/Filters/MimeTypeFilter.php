<?php

namespace Searchperience\Api\Client\Domain\Document\Filters;

use Symfony\Component\Validator\Constraints as Assert;
use Searchperience\Api\Client\Domain\Filters\AbstractFilter;

/**
 * Class MimeTypeFilter
 *
 * @package Searchperience\Api\Client\Domain\Document\Filters
 */
class MimeTypeFilter extends AbstractFilter {

	/**
	 * @var string $mimeType
	 * @Assert\Type(type="string", message="The value {{ value }} is not a valid {{ type }}.")
	 */
	protected $mimeType;

	/**
	 * @var string
	 */
	protected $filterString;

	/**
	 * @param string $mimeType
	 */
	public function setMimeType($mimeType) {
		$this->mimeType = $mimeType;
	}

	/**
	 * @return string
	 */
	public function getMimeType() {
		return $this->mimeType;
	}

	/**
	 * @return string
	 */
	public function getFilterString() {
		if (!empty($this->mimeType)) {
			$this->filterString = sprintf("&mimeType=%s", rawurlencode($this->getMimeType()));
		}
		return $this->filterString;
	}
}