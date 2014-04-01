<?php
/**
 * @Author: Nikolay Diaur <nikolay.diaur@aoe.com>
 * @Date: 2/24/14
 * @Time: 6:19 PM
 */

namespace Searchperience\Api\Client\Domain\Filters;

use Searchperience\Api\Client\Domain\UrlQueueItem;
use Symfony\Component\Validator\Validation;

/**
 * Class FilterRepository
 * @package Searchperience\Api\Client\Domain\Filters
 */
class FilterCollectionFactory {

	/**
	 * @var \Symfony\Component\Validator\ValidatorInterface
	 */
	protected $filterValidator;

	/**
	 * Injects the validation service
	 *
	 * @param \Symfony\Component\Validator\ValidatorInterface $filterValidator
	 * @return void
	 */
	public function injectValidator(\Symfony\Component\Validator\ValidatorInterface $filterValidator) {
		$this->filterValidator = $filterValidator;
	}

	/**
	 * @param array $filters
	 * @throws \Searchperience\Common\Exception\UnexpectedValueException
	 * @return FilterCollection
	 */
	public function createFromFilterArguments($filters){
		$result = new FilterCollection();
		foreach ($filters as $filterName => $filterValue) {
			$filterName = ucfirst($filterName);
			$filterClassName = $this->getFilterClassName($filterName);
			if (class_exists($filterClassName)) {
				$filter = $this->initFilter($filterClassName, $filterValue);
				$result->addFilter($filter);
			} else {
				throw new \Searchperience\Common\Exception\UnexpectedValueException('Filter not exists: ' . $filterName);
			}
		}

		return $result;
	}

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
	 * @return string
	 * @TODO make load more beautiful
	 */
	protected function loadConstraints() {
		class_exists('\Symfony\Component\Validator\Constraints\Url');
		class_exists('\Symfony\Component\Validator\Constraints\NotBlank');
		class_exists('\Symfony\Component\Validator\Constraints\Length');
		class_exists('\Symfony\Component\Validator\Constraints\DateTime');
		class_exists('\Symfony\Component\Validator\Constraints\Type');
		class_exists('\Symfony\Component\Validator\Constraints\Regex');
	}

	/**
	 * @param object $filter
	 * @throws \Searchperience\Common\Exception\InvalidArgumentException
	 */
	protected function validateFilter($filter) {
		$this->loadConstraints();
		$this->injectValidator(\Symfony\Component\Validator\Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator());
		$violations = $this->filterValidator->validate($filter);

		if ($violations->count() > 0) {
			throw new \Searchperience\Common\Exception\InvalidArgumentException('Given object of type "' . get_class($violations) . '" is not valid: ' . $violations);
		}
	}

	/**
	 * @param string $filterClassName
	 * @param array $filterValue
	 * @throws \Searchperience\Common\Exception\UnexpectedValueException
	 * @throws \Searchperience\Common\Exception\InvalidArgumentException
	 */
	protected function initFilter($filterClassName, $filterValue) {
		$filter = new $filterClassName();

		if($filter instanceof \Searchperience\Api\Client\Domain\Filters\AbstractDateFilter) {
			$filter->injectDateTimeService(new \Searchperience\Api\Client\System\DateTime\DateTimeService());
		}

		if (!is_array($filterValue)) {
			throw new \Searchperience\Common\Exception\UnexpectedValueException($filterClassName.' values "' . __METHOD__ . '" should be an array: ' . $filterValue);
		} else {
			foreach ($filterValue as $key => $value) {
				if ( is_object($value) || trim($value) != '') {
					if (method_exists($filter, $method = ('set' . ucfirst($key)))) {
						$filter->$method($value);
						$this->validateFilter($filter);
					} else {
						throw new \Searchperience\Common\Exception\InvalidArgumentException('Invalid '. $filterClassName.' value ' . $key);
					}
				}
			}
		}

		return $filter;
	}

	/**
	 * @param $filterName
	 * @return string
	 */
	protected function getFilterClassName($filterName) {
		$filterClassName = __NAMESPACE__ . '\\' . $filterName . 'Filter';
		return $filterClassName;
	}
}