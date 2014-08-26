<?php
/**
 * @Author: Nikolay Diaur <nikolay.diaur@aoe.com>
 * @Date: 8/15/14
 * @Time: 3:01 PM
 */

namespace Searchperience\Api\Client\Domain\ActivityLogs;

use Searchperience\Api\Client\Domain\AbstractRepository;
use Searchperience\Api\Client\System\Storage\AbstractRestBackend;
use Searchperience\Common\Exception\InvalidArgumentException;

/**
 * Class ActivityLogsRepository
 * @package Searchperience\Api\Client\Domain\ActivityLogs
 */
class ActivityLogsRepository  extends AbstractRepository {

	/**
	 * Get activity log by id
	 *
	 * @param integer $id
	 * @return ActivityLogs
	 * @throws \Searchperience\Common\Exception\InvalidArgumentException
	 */
	public function getById($id) {
		if (!is_numeric($id)) {
			throw new InvalidArgumentException('Method "' . __METHOD__ . '" accepts only integer values as $id. Input was: ' . serialize($id));
		}
		$activityLog = $this->checkTypeAndDecorate($this->storageBackend->getById($id));
		return $activityLog;
	}
} 