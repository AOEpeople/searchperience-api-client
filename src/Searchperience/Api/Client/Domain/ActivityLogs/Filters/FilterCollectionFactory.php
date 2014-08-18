<?php

namespace Searchperience\Api\Client\Domain\ActivityLogs\Filters;

use Searchperience\Api\Client\Domain\ActivityLogs\Filters;
use Searchperience\Api\Client\Domain\Filters\FilterCollection;
use Searchperience\Api\Client\Domain\Filters\AbstractFilterCollectionFactory;

use Symfony\Component\Validator\Validation;

/**
 * Class FilterRepository
 * @package Searchperience\Api\Client\Domain\ActivityLogs\Filters
 * @author: Nikolay Diaur <nikolay.diaur@aoe.com>
 */
class FilterCollectionFactory extends AbstractFilterCollectionFactory {

	/**
	 * @var array
	 */
	protected $allowedFilters = array(
		'severity', 'logTime', 'query'
	);

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
		foreach ($filterNames as $filterName) {
			if (!in_array($filterName, $this->allowedFilters)) {
				throw new \Searchperience\Common\Exception\UnexpectedValueException('Could not handle filter ' . $filterName . ' for IndexerActivityLog entity. Is this filter allowed?');
			}
		}
		return true;
	}
}