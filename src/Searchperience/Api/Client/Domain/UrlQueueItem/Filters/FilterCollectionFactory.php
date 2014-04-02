<?php
/**
 * @Author: Nikolay Diaur <nikolay.diaur@aoe.com>
 * @Date: 2/24/14
 * @Time: 6:19 PM
 */

namespace Searchperience\Api\Client\Domain\UrlQueueItem\Filters;

use Searchperience\Api\Client\Domain\Document\Filters;
use Searchperience\Api\Client\Domain\Filters\FilterCollection;
use Searchperience\Api\Client\Domain\UrlQueueItem\UrlQueueItem;
use Searchperience\Api\Client\Domain\Filters\AbstractFilterCollectionFactory;

use Symfony\Component\Validator\Validation;

/**
 * Class FilterRepository
 * @package Searchperience\Api\Client\Domain\Document\Filters
 */
class FilterCollectionFactory extends AbstractFilterCollectionFactory{

	/**
	 * Build
	 *
	 * @param array $states
	 * @return FilterCollection
	 */
	public function createFromUrlQueueItemStates(array $states) {
		$result = new FilterCollection();
		foreach($states as $state) {
			switch($state) {
				case UrlQueueItem::IS_WAITING:
					$filter = new ProcessingThreadIdFilter();
					$filter->setProcessingThreadIdStart(0);
					$filter->setProcessingThreadIdEnd(0);
					$result->addFilter($filter);

					$filter = new DeletedFilter();
					$filter->setDeleted(false);
					$result->addFilter($filter);
					break;
				case UrlQueueItem::IS_PROCESSING:
					$filter = new ProcessingThreadIdFilter();
					$filter->setProcessingThreadIdStart(1);
					$filter->setProcessingThreadIdEnd(65536);
					$result->addFilter($filter);

					$filter = new DeletedFilter();
					$filter->setDeleted(false);
					$result->addFilter($filter);
					break;
				case UrlQueueItem::IS_DOCUMENT_DELETED:
					$filter = new DeletedFilter();
					$filter->setDeleted(true);
					$result->addFilter($filter);
					break;

				case UrlQueueItem::IS_ERROR:
					$filter = new ErrorFilter();
					$filter->setError(true);
					$result->addFilter($filter);
					break;
			}
		}

		return $result;
	}

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
	 * @return booleans
	 */
	protected function validateFilterArguments($filters) {
		// TODO: Implement validateFilterArguments() method.

		return true;
	}
}