<?php

namespace Searchperience\Api\Client\Domain\UrlQueueItem;

use Symfony\Component\Validator\Validation;
use Searchperience\Api\Client\System\Storage\AbstractRestBackend;
use Searchperience\Common\Exception\InvalidArgumentException;

/**
 * Class UrlQueueStatusRepository
 *
 * @package Searchperience\Api\Client\Domain
 * @author: Timo Schmidt <timo.schmidt@aoe.com>
 */
class UrlQueueStatusRepository {
	/**
	 * @var \Searchperience\Api\Client\System\Storage\UrlQueueStatusBackendInterface
	 */
	protected $storageBackend;

	/**
	 * Injects the storage backend.
	 *
	 * @param \Searchperience\Api\Client\System\Storage\UrlQueueStatusBackendInterface $storageBackend
	 * @return void
	 */
	public function injectStorageBackend(\Searchperience\Api\Client\System\Storage\UrlQueueStatusBackendInterface $storageBackend) {
		$this->storageBackend = $storageBackend;
	}

	/**
	 * Retrieves the status of the url queue.
	 *
	 * @return UrlQueueStatus
	 * @throws \Searchperience\Common\Exception\InvalidArgumentException
	 */
	public function get() {
		$urlQueueStatus = $this->decorateUrlQueueStatus($this->storageBackend->get());
		return $urlQueueStatus;
	}

	/**
	 * Overwrite this method to decorate the UrlQueueStatus repsonse.
	 *
	 * @param $urlQueueStatus
	 * @return mixed
	 */
	protected function decorateUrlQueueStatus($urlQueueStatus) {
		return $urlQueueStatus;
	}
}