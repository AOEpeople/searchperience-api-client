<?php

namespace Searchperience\Api\Client\Domain\Document\Filters;

use Symfony\Component\Validator\Constraints as Assert;
use Searchperience\Api\Client\Domain\Filters\AbstractFilter;

/**
 * Class BoostFactorFilter
 *
 * @package Searchperience\Api\Client\Domain\Document\Filters
 * @author: Nikolay Diaur <nikolay.diaur@aoe.com>
 */
class BoostFactorFilter extends AbstractFilter {

	/**
	 * @var string
	 */
	protected $filterString;

	/**
	 * @var string $boostFactorStart
	 * @Assert\Type(type="double", message="The value {{ value }} is not a valid {{ type }}.")
	 */
	protected $boostFactorStart;

	/**
	 * @var string $boostFactorEnd
	 * @Assert\Type(type="double", message="The value {{ value }} is not a valid {{ type }}.")
	 */
	protected $boostFactorEnd;

	/**
	 * @param string $boostFactorEnd
	 */
	public function setBoostFactorEnd($boostFactorEnd) {
		$this->boostFactorEnd = $boostFactorEnd;
	}

	/**
	 * @return string
	 */
	public function getBoostFactorEnd() {
		return $this->boostFactorEnd;
	}

	/**
	 * @param string $boostFactorStart
	 */
	public function setBoostFactorStart($boostFactorStart) {
		$this->boostFactorStart = $boostFactorStart;
	}

	/**
	 * @return string
	 */
	public function getBoostFactorStart() {
		return $this->boostFactorStart;
	}

	/**
	 * @return string
	 */
	public function getFilterString() {
		if (isset($this->boostFactorStart)) {
			$this->filterString = sprintf("&boostFactorStart=%s", rawurlencode($this->getBoostFactorStart()));
		}
		if (isset($this->boostFactorEnd)) {
			$this->filterString .= sprintf("&boostFactorEnd=%s", rawurlencode($this->getBoostFactorEnd()));
		}
		return $this->filterString;
	}
}