<?php
/**
 * @Author: Nikolay Diaur <nikolay.diaur@aoe.com>
 * @Date: 2/24/14
 * @Time: 6:19 PM
 */

namespace Searchperience\Api\Client\Domain\Filters;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class PageRankFilter
 * @package Searchperience\Api\Client\Domain\Filters
 */
class PageRankFilter {

	/**
	 * @var string
	 */
	protected $filterString;

	/**
	 * @var string $prStart
	 * @Assert\Type(type="double", message="The value {{ value }} is not a valid {{ type }}.")
	 */
	protected $prStart;

	/**
	 * @var string $prEnd
	 * @Assert\Type(type="double", message="The value {{ value }} is not a valid {{ type }}.")
	 */
	protected $prEnd;

	/**
	 * @param string $prEnd
	 */
	public function setPrEnd($prEnd) {
		$this->prEnd = $prEnd;
	}

	/**
	 * @return string
	 */
	public function getPrEnd() {
		return $this->prEnd;
	}

	/**
	 * @param string $prStart
	 */
	public function setPrStart($prStart) {
		$this->prStart = $prStart;
	}

	/**
	 * @return string
	 */
	public function getPrStart() {
		return $this->prStart;
	}

	/**
	 * @return string
	 */
	public function getFilterString() {
		if (!empty($this->prStart)) {
			$this->filterString = sprintf("&prStart=%s", rawurlencode($this->getPrStart()));
		}
		if (!empty($this->prEnd)) {
			$this->filterString .= sprintf("&prEnd=%s", rawurlencode($this->getPrEnd()));
		}
		return $this->filterString;
	}
} 