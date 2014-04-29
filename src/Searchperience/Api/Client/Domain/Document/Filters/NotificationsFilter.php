<?php

namespace Searchperience\Api\Client\Domain\Document\Filters;

use Symfony\Component\Validator\Constraints as Assert;

use Searchperience\Api\Client\Domain\Filters\AbstractFilter;
use Searchperience\Api\Client\Domain\Filters\FilterCollection;
use Searchperience\Api\Client\Domain\Document\Filters\IsDuplicateFilter;
use Searchperience\Api\Client\Domain\Document\Filters\ProcessingThreadIdFilter;
use Searchperience\Api\Client\Domain\Document\Filters\IsDeletedFilter;
use Searchperience\Api\Client\Domain\Document\Filters\HasErrorFilter;
use Searchperience\Api\Client\Domain\Document\Filters\IsWaitingFilter;


use Searchperience\Api\Client\Domain\Document\Document;

/**
 * Class NotificationsFilter
 * @package Searchperience\Api\Client\Domain\Document\Filters
 * @author: Nikolay Diaur <nikolay.diaur@aoe.com>
 */
class NotificationsFilter extends AbstractFilter {

	/**
	 * @var array $state
	 * @Assert\Type(type="array", message="The value {{ value }} is not a valid {{ type }}.")
	 */
	protected $notifications;

	/**
	 * @param array $states
	 */
	public function setNotifications($states) {
		$this->notifications = $states;
	}

	/**
	 * @return array
	 */
	public function getNotifications() {
		return $this->notifications;
	}

	/**
	 * @return string
	 */
	public function getFilterString() {
		if(!is_array($this->notifications)) {
			return '';
		}

		$result = new FilterCollection();
		foreach($this->notifications as $state) {
			switch($state) {
				case Document::IS_PROCESSING:
					$filter = new ProcessingThreadIdFilter();
					$filter->setProcessingThreadIdStart(1);
					$filter->setProcessingThreadIdEnd(65536);
					$result->addFilter($filter);

					$filter = new IsDeletedFilter();
					$filter->setDeleted(false);
					$result->addFilter($filter);
					break;
				case Document::IS_DELETING:
					$filter = new IsDeletedFilter();
					$filter->setDeleted(true);
					$result->addFilter($filter);
					break;

				case Document::IS_ERROR:
					$filter = new HasErrorFilter();
					$filter->setError(true);
					$result->addFilter($filter);
					break;
				case Document::IS_DUPLICATE:
					$filter = new IsDuplicateFilter();
					$filter->setIsDuplicate(true);
					$result->addFilter($filter);
					break;
				case Document::IS_WAITING:
					$filter = new IsWaitingFilter();
					$filter->setIsWaiting(true);
					$result->addFilter($filter);
					break;
				case Document::IS_REDIRECT:
					$filter = new IsRedirectFilter();
					$filter->setIsRedirect(true);
					$result->addFilter($filter);
					break;
			}
		}

		return $result->getFilterStringFromAll();
	}
}