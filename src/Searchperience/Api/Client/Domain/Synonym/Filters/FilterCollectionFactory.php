<?php

namespace Searchperience\Api\Client\Domain\Synonym\Filters;

use Searchperience\Api\Client\Domain\Filters\AbstractFilterCollectionFactory;
use Symfony\Component\Validator\Validation;

class FilterCollectionFactory extends AbstractFilterCollectionFactory {

	/**
	 * @var array
	 */
	protected $allowedFilters = array('query');

	/**
	 * @param $filterName
	 * @return string
	 */
	protected function getFilterClassName($filterName) {
		$filterClassName = __NAMESPACE__ . '\\' . $filterName . 'Filter';
		return $filterClassName;
	}

	/**
	 * The implementation should check the passed filter arguments to allow only valid filters.
	 *
	 * @param $filters
	 * @throws \Searchperience\Common\Exception\UnexpectedValueException
	 * @return boolean
	 */
	protected function validateFilterArguments($filters) {
		$filterNames = array_keys($filters);
		foreach($filterNames as $filterName)  {
			if(!in_array($filterName,$this->allowedFilters)) {
				throw new \Searchperience\Common\Exception\UnexpectedValueException('Could not handle filter '.$filterName. ' for Synonym entity. Is this filter allowed?');
			}
		}
		return true;
	}
}