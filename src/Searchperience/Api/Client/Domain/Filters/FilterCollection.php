<?php

namespace Searchperience\Api\Client\Domain\Filters;

/**
 * Class FiltersCollection
 * @package Searchperience\Api\Client\Domain\Filters
 * @author: Nikolay Diaur <nikolay.diaur@aoe.com>
 */
class FilterCollection extends \ArrayObject {

	/**
	 * @var ArrayObject
	 */
	protected $filters;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->filters = new \ArrayObject();
	}

	/**
	 * @object $filter
	 * @throws \Searchperience\Common\Exception\UnexpectedValueException
	 */
	public function addFilter($filter){
		$this->filters->append($filter);
	}

	/**
	 * @return string
	 */
	public function getFilterStringFromAll() {
		$filtersString = '';

		foreach ($this->filters as $key => $filterObject) {
			$filtersString .= $filterObject->getFilterString();
		}

		return $filtersString;
	}

	/**
	 * @return int
	 */
	public function getCount() {
		return $this->filters->count();
	}

}