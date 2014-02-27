<?php
/**
 * @Author: Nikolay Diaur <nikolay.diaur@aoe.com>
 * @Date: 2/24/14
 * @Time: 6:19 PM
 */

namespace Searchperience\Api\Client\Domain\Filters;


use Symfony\Component\Validator\Constraints as Assert;


/**
 * Class BoostFactorFilter
 * @package Searchperience\Api\Client\Domain\Filters
 */
class BoostFactorFilter {

	/**
	 * @var string
	 */
	protected $filterString;

	/**
	 * @var string $bfStart
	 * @Assert\Type(type="double", message="The value {{ value }} is not a valid {{ type }}.")
	 */
	protected $bfStart;

	/**
	 * @var string $bfEnd
	 * @Assert\Type(type="double", message="The value {{ value }} is not a valid {{ type }}.")
	 */
	protected $bfEnd;

	/**
	 * @param string $bfEnd
	 */
	public function setBfEnd($bfEnd) {
		$this->bfEnd = $bfEnd;
	}

	/**
	 * @return string
	 */
	public function getBfEnd() {
		return $this->bfEnd;
	}

	/**
	 * @param string $bfStart
	 */
	public function setBfStart($bfStart) {
		$this->bfStart = $bfStart;
	}

	/**
	 * @return string
	 */
	public function getBfStart() {
		return $this->bfStart;
	}

	/**
	 * @return string
	 */
	public function getFilterString() {
		if (isset($this->bfStart)) {
			$this->filterString = sprintf("&bfStart=%s", rawurlencode($this->getBfStart()));
		}
		if (isset($this->bfEnd)) {
			$this->filterString .= sprintf("&bfEnd=%s", rawurlencode($this->getBfEnd()));
		}
		return $this->filterString;
	}
}