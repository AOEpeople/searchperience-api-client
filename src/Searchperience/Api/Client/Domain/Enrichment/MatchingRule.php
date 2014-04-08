<?php

namespace Searchperience\Api\Client\Domain\Enrichment;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Timo Schmidt <timo.schmidt@aoe.com>
 */
class MatchingRule {

	/**
	 * @var string
	 */
	protected $fieldName = '';

	/**
	 * @var string
	 */
	protected $operator = '';

	/**
	 * @var string
	 */
	protected $operatorValue = '';

	const OPERATOR_EQUALS = 'equals';
	const OPERATOR_CONTAINS = 'contains';
	const OPERATOR_CONTAINSNOT = 'contains_not';
	const OPERATOR_EQUALSNOT = 'equals_not';
	const OPERATOR_GREATER = 'greater_then';
	const OPERATOR_LOWER = 'lower_then';

	/**
	 * @var array
	 */
	protected $allowedOperators = array(
		self::OPERATOR_EQUALS, self::OPERATOR_CONTAINS,
		self::OPERATOR_CONTAINSNOT, self::OPERATOR_EQUALSNOT,
		self::OPERATOR_GREATER, self::OPERATOR_LOWER
	);

	/**
	 * @param array $operator
	 * @return bool
	 */
	public static function isOperatorAllowed($operator) {
		return in_array($operator, self::allowedOperators);
	}

	/**
	 * @param string $fieldName
	 */
	public function setFieldName($fieldName) {
		$this->fieldName = $fieldName;
	}

	/**
	 * @return string
	 */
	public function getFieldName() {
		return $this->fieldName;
	}

	/**
	 * @param string $operator
	 */
	public function setOperator($operator) {
		$this->operator = $operator;
	}

	/**
	 * @return string
	 */
	public function getOperator() {
		return $this->operator;
	}

	/**
	 * @param string $operatorValue
	 */
	public function setOperatorValue($operatorValue) {
		$this->operatorValue = $operatorValue;
	}

	/**
	 * @return string
	 */
	public function getOperatorValue() {
		return $this->operatorValue;
	}
}