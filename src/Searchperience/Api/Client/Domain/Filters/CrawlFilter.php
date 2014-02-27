<?php
/**
 * @Author: Nikolay Diaur <nikolay.diaur@aoe.com>
 * @Date: 2/24/14
 * @Time: 6:19 PM
 */

namespace Searchperience\Api\Client\Domain\Filters;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class CrawlFilter
 * @package Searchperience\Api\Client\Domain\Filters
 */
class CrawlFilter {

	/**
	 * @var string
	 */
	protected $filterString;

	/**
	 * @var string $crawlStart
	 * @Assert\DateTime(message="The value {{ value }} is not a valid datetime.")
	 */
	protected $crawlStart;

	/**
	 * @var string $crawlEnd
	 * @Assert\DateTime(message="The value {{ value }} is not a valid datetime.")
	 */
	protected $crawlEnd;

	/**
	 * @param string $crawlEnd
	 */
	public function setCrawlEnd($crawlEnd) {
		$this->crawlEnd = $crawlEnd;
	}

	/**
	 * @return string
	 */
	public function getCrawlEnd() {
		return $this->crawlEnd;
	}

	/**
	 * @param string $crawlStart
	 */
	public function setCrawlStart($crawlStart) {
		$this->crawlStart = $crawlStart;
	}

	/**
	 * @return string
	 */
	public function getCrawlStart() {
		return $this->crawlStart;
	}

	/**
	 * @return string
	 */
	public function getFilterString() {
		if(!empty($this->crawlStart)) {
			$this->filterString = sprintf("&crawlStart=%s", rawurlencode($this->getCrawlStart()));
		}
		if (!empty($this->crawlEnd)) {
			$this->filterString .= sprintf("&crawlEnd=%s", rawurlencode($this->getCrawlEnd()));
		}
		return $this->filterString;
	}
} 