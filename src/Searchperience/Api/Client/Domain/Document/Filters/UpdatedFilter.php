<?php

namespace Searchperience\Api\Client\Domain\Document\Filters;

use Symfony\Component\Validator\Constraints as Assert;
use Searchperience\Api\Client\Domain\Filters\AbstractDateFilter;

/**
 * Class UpdatedFilter
 * @package Searchperience\Api\Client\Domain\Document\Filters
 * @author: Nikolay Diaur <nikolay.diaur@aoe.com>
 */
class UpdatedFilter extends AbstractDateFilter {

	/**
	 * @var string
	 */
	protected $filterString;

	/**
	 * @var \DateTime $updatedFrom
	 * @Assert\DateTime(message="The value {{ value }} is not a valid datetime.")
	 */
	protected $updatedFrom;

	/**
	 * @var \DateTime $updatedTo
	 * @Assert\DateTime(message="The value {{ value }} is not a valid datetime.")
	 */
	protected $updatedTo;

	/**
	 * @param \DateTime  $updatedTo
	 */
	public function setUpdatedTo($updatedTo) {
		$this->updatedTo = $updatedTo;
	}

	/**
	 * @return \DateTime
	 */
	public function getUpdatedTo() {
		return $this->updatedTo;
	}

	/**
	 * @param \DateTime $updatedFrom
	 */
	public function setUpdatedFrom($updatedFrom) {
		$this->updatedFrom = $updatedFrom;
	}

	/**
	 * @return \DateTime
	 */
	public function getUpdatedFrom() {
		return $this->updatedFrom;
	}

	/**
	 * @return string
	 */
	public function getFilterString() {
		if(!empty($this->updatedFrom)) {
			$this->filterString = sprintf("&updatedFrom=%s", rawurlencode($this->toString($this->getUpdatedFrom())));
		}
		if (!empty($this->updatedTo)) {
			$this->filterString .= sprintf("&updatedTo=%s", rawurlencode($this->toString($this->getUpdatedTo())));
		}
		return $this->filterString;
	}
}
