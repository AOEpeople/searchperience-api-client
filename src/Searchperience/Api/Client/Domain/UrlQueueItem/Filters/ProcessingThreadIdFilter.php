<?php
/**
 * @Author: Nikolay Diaur <nikolay.diaur@aoe.com>
 * @Date: 2/24/14
 * @Time: 6:19 PM
 */

namespace Searchperience\Api\Client\Domain\UrlQueueItem\Filters;


use Symfony\Component\Validator\Constraints as Assert;
use Searchperience\Api\Client\Domain\Filters\AbstractFilter;


/**
 * Class ProcessingThreadId filter
 * @package Searchperience\Api\Client\Domain\Filters
 */
class ProcessingThreadIdFilter extends AbstractFilter {

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