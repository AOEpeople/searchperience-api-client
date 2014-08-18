<?php

namespace Searchperience\Api\Client\Domain\Filters;

abstract class AbstractFilterCollectionFactory {

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
				if ( is_object($value) || is_array($value) || trim($value) != '') {
					if (method_exists($filter, $method = ('set' . ucfirst($key)))) {
						$filter->$method($value);
						$this->validateFilter($filter);
					} else {
						throw new \Searchperience\Common\Exception\InvalidArgumentException('Invalid '. $filterClassName.' value ' . $key . 'value' . serialize($value));
					}
				}
			}
		}

		return $filter;
	}

	/**
	 * @param array $filters
	 * @throws \Searchperience\Common\Exception\UnexpectedValueException
	 * @return FilterCollection
	 */
	public function createFromFilterArguments($filters){
		if(!$this->validateFilterArguments($filters)) {
			throw new \Searchperience\Common\Exception\UnexpectedValueException('');
		}
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
	 * The implementation should check the passed filter arguments to allow only valid filters.
	 *
	 * @param $filters
	 * @return booleans
	 */
	abstract protected function validateFilterArguments($filters);

	/**
	 * The implementation of this method should return the class name by filter name.
	 *
	 * @param $string
	 * @return string
	 */
	abstract protected function getFilterClassName($string);
}