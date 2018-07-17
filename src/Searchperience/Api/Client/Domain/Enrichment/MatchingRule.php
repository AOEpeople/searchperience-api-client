<?php

namespace Searchperience\Api\Client\Domain\Enrichment;

use Symfony\Component\Validator\Constraints as Assert;
use Searchperience\Api\Client\Domain\AbstractEntity;

/**
 * @author Timo Schmidt <timo.schmidt@aoe.com>
 */
class MatchingRule extends AbstractEntity {

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
	protected $operandValue = '';

	const OPERATOR_MATCHES = 'matches';
	const OPERATOR_CONTAINS = 'contains';
	const OPERATOR_CONTAINSNOT = 'contains_not';
	const OPERATOR_DOESNOTMATCH = 'does_not_match';
	const OPERATOR_GREATER = 'greater_than';
	const OPERATOR_LOWER = 'lower_than';

	/**
	 * @var array
	 */
	protected static $allowedOperators = array(
		self::OPERATOR_MATCHES, self::OPERATOR_CONTAINS,
		self::OPERATOR_CONTAINSNOT, self::OPERATOR_DOESNOTMATCH,
		self::OPERATOR_GREATER, self::OPERATOR_LOWER
	);

	/**
	 * @param array $operator
	 * @return bool
	 */
	public static function isOperatorAllowed($operator) {
		return in_array($operator, self::$allowedOperators);
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
	 * @throws \Searchperience\Common\Exception\InvalidArgumentException
	 */
	public function setOperator($operator) {
		if(!$this->isOperatorAllowed($operator)) {
			throw new \Searchperience\Common\Exception\InvalidArgumentException("Invalid operator: ".htmlspecialchars($operator));
		}
		$this->operator = $operator;
	}

	/**
	 * @return string
	 */
	public function getOperator() {

		return $this->operator;
	}

	/**
	 * @param string $operandValue
	 */
	public function setOperandValue($operandValue) {
		$this->operandValue = $operandValue;
	}

	/**
	 * @return string
	 */
	public function getOperandValue() {
		return $this->operandValue;
	}
}