<?php

namespace Searchperience\Api\Client\Domain\Document\Filters;

use Symfony\Component\Validator\Constraints as Assert;
use Searchperience\Api\Client\Domain\Filters\AbstractDateFilter;

/**
 * Class CreatedFilter
 * @package Searchperience\Api\Client\Domain\Document\Filters
 * @author: Nikolay Diaur <nikolay.diaur@aoe.com>
 */
class CreatedFilter extends AbstractDateFilter {

	/**
	 * @var string
	 */
	protected $filterString;

	/**
	 * @var \DateTime $createdFrom
	 * @Assert\DateTime(message="The value {{ value }} is not a valid datetime.")
	 */
	protected $createdFrom;

	/**
	 * @var \DateTime $createdTo
	 * @Assert\DateTime(message="The value {{ value }} is not a valid datetime.")
	 */
	protected $createdTo;

	/**
	 * @param \DateTime  $createdTo
	 */
	public function setCreatedTo($createdTo) {
		$this->createdTo = $createdTo;
	}

	/**
	 * @return \DateTime
	 */
	public function getCreatedTo() {
		return $this->createdTo;
	}

	/**
	 * @param \DateTime $createdFrom
	 */
	public function setCreatedFrom($createdFrom) {
		$this->createdFrom = $createdFrom;
	}

	/**
	 * @return \DateTime
	 */
	public function getCreatedFrom() {
		return $this->createdFrom;
	}

	/**
	 * @return string
	 */
	public function getFilterString() {
		if(!empty($this->createdFrom)) {
			$this->filterString = sprintf("&createdFrom=%s", rawurlencode($this->toString($this->getCreatedFrom())));
		}
		if (!empty($this->createdTo)) {
			$this->filterString .= sprintf("&createdTo=%s", rawurlencode($this->toString($this->getCreatedTo())));
		}
		return $this->filterString;
	}
}
