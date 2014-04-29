<?php

namespace Searchperience\Api\Client\Domain\Filters;

use Symfony\Component\Validator\Constraints as Assert;
use Searchperience\Api\Client\Domain\Filters\AbstractFilter;

/**
 * Class ProcessingThreadId filter
 * @package Searchperience\Api\Client\Domain\Filters
 * @author: Nikolay Diaur <nikolay.diaur@aoe.com>
 */
abstract class AbstractProcessingThreadIdFilter extends AbstractFilter {

	/**
	 * @var string
	 */
	protected $filterString;

	/**
	 * @var int $processingThreadIdStart
	 * @Assert\Type(type="integer", message="The value {{ value }} is not a valid {{ type }}.")
	 */
	protected $processingThreadIdStart;

	/**
	 * @var string $boostFactorEnd
	 * @Assert\Type(type="integer", message="The value {{ value }} is not a valid {{ type }}.")
	 */
	protected $processingThreadIdEnd;

	/**
	 * @param string $processingThreadIdEnd
	 */
	public function setProcessingThreadIdEnd($processingThreadIdEnd) {
		$this->processingThreadIdEnd = $processingThreadIdEnd;
	}

	/**
	 * @return string
	 */
	public function getProcessingThreadIdEnd() {
		return $this->processingThreadIdEnd;
	}

	/**
	 * @param int $processingThreadIdStart
	 */
	public function setProcessingThreadIdStart($processingThreadIdStart) {
		$this->processingThreadIdStart = $processingThreadIdStart;
	}

	/**
	 * @return int
	 */
	public function getProcessingThreadIdStart() {
		return $this->processingThreadIdStart;
	}

	/**
	 * @return string
	 */
	public function getFilterString() {
		if (isset($this->processingThreadIdStart)) {
			$this->filterString = sprintf("&processingThreadIdStart=%s", rawurlencode((string)$this->getProcessingThreadIdStart()));
		}
		if (isset($this->processingThreadIdEnd)) {
			$this->filterString .= sprintf("&processingThreadIdEnd=%s", rawurlencode((string)$this->getProcessingThreadIdEnd()));
		}
		return $this->filterString;
	}
}