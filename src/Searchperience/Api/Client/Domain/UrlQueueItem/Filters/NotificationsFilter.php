<?php

namespace Searchperience\Api\Client\Domain\UrlQueueItem\Filters;

use Symfony\Component\Validator\Constraints as Assert;
use Searchperience\Api\Client\Domain\Filters\AbstractFilter;
use Searchperience\Api\Client\Domain\Filters\FilterCollection;
use Searchperience\Api\Client\Domain\UrlQueueItem\UrlQueueItem;

/**
 * Class PriorityFilter
 * @package Searchperience\Api\Client\Domain\Document\Filters
 * @author: Timo Schmidt <timo.schmidt@aoe.com>
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
				case UrlQueueItem::IS_WAITING:
					$filter = new ProcessingThreadIdFilter();
					$filter->setProcessingThreadIdStart(0);
					$filter->setProcessingThreadIdEnd(0);
					$result->addFilter($filter);

					$filter = new IsDeletedFilter();
					$filter->setDeleted(false);
					$result->addFilter($filter);
					break;
				case UrlQueueItem::IS_PROCESSING:
					$filter = new ProcessingThreadIdFilter();
					$filter->setProcessingThreadIdStart(1);
					$filter->setProcessingThreadIdEnd(65536);
					$result->addFilter($filter);

					$filter = new IsDeletedFilter();
					$filter->setDeleted(false);
					$result->addFilter($filter);
					break;
				case UrlQueueItem::IS_DOCUMENT_DELETED:
					$filter = new IsDeletedFilter();
					$filter->setDeleted(true);
					$result->addFilter($filter);
					break;

				case UrlQueueItem::IS_ERROR:
					$filter = new HasErrorFilter();
					$filter->setError(true);
					$result->addFilter($filter);
					break;
			}
		}

		return $result->getFilterStringFromAll();
	}
}