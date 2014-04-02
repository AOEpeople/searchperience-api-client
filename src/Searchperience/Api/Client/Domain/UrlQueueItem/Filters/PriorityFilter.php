<?php

namespace Searchperience\Api\Client\Domain\Document\Filters;

use Symfony\Component\Validator\Constraints as Assert;
use Searchperience\Api\Client\Domain\Filters\AbstractFilter;

/**
 * Class PriorityFilter
 * @package Searchperience\Api\Client\Domain\Document\Filters
 * @author: Nikolay Diaur <nikolay.diaur@aoe.com>
 */
class PageRankFilter extends AbstractFilter {

	/**
	 * @var string
	 */
	protected $filterString;

	/**
	 * @var string $pageRankStart
	 * @Assert\Type(type="double", message="The value {{ value }} is not a valid {{ type }}.")
	 */
	protected $priorityStart;

	/**
	 * @var string $pageRankEnd
	 * @Assert\Type(type="double", message="The value {{ value }} is not a valid {{ type }}.")
	 */
	protected $priorityEnd;

	/**
	 * @param string $pageRankEnd
	 */
	public function setPriorityEnd($pageRankEnd) {
		$this->priorityEnd = $pageRankEnd;
	}

	/**
	 * @return string
	 */
	public function getPriorityEnd() {
		return $this->priorityEnd;
	}

	/**
	 * @param string $pageRankStart
	 */
	public function setPriorityStart($pageRankStart) {
		$this->priorityStart = $pageRankStart;
	}

	/**
	 * @return string
	 */
	public function getPriorityStart() {
		return $this->priorityStart;
	}

	/**
	 * @return string
	 */
	public function getFilterString() {
		if (!empty($this->priorityStart)) {
			$this->filterString = sprintf("&priorityStart=%s", rawurlencode($this->getPriorityStart()));
		}
		if (!empty($this->priorityEnd)) {
			$this->filterString .= sprintf("&priorityEnd=%s", rawurlencode($this->getPriorityEnd()));
		}
		return $this->filterString;
	}
}