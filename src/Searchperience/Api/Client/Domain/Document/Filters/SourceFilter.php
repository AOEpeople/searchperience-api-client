<?php

namespace Searchperience\Api\Client\Domain\Document\Filters;

use Symfony\Component\Validator\Constraints as Assert;
use Searchperience\Api\Client\Domain\Filters\AbstractFilter;


/**
 * Class SourceFilter
 * @package Searchperience\Api\Client\Domain\Document\Filters
 * @author: Nikolay Diaur <nikolay.diaur@aoe.com>
 */
class SourceFilter extends AbstractFilter {

	/**
	 * @var string $source
	 * @Assert\Type(type="string", message="The value {{ value }} is not a valid {{ type }}.")
	 */
	protected $source;

	/**
	 * @var string
	 */
	protected $filterString;

	/**
	 * @param string $source
	 */
	public function setSource($source) {
		$this->source = $source;
	}

	/**
	 * @return string
	 */
	public function getSource() {
		return $this->source;
	}

	/**
	 * @return string
	 */
	public function getFilterString() {
		if (!empty($this->source)) {
			$this->filterString = sprintf("&source=%s", rawurlencode($this->getSource()));
		}
		return $this->filterString;
	}
}