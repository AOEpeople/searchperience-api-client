<?php

namespace Searchperience\Api\Client\Domain\CommandLog;

use Searchperience\Common\Exception\InvalidArgumentException;
use Searchperience\Api\Client\Domain\AbstractRepository;

/**
 * Class IndexerCommandLogRepository
 *
 * @package Searchperience\Api\Client\Domain
 * @author: Nikolay Diaur <nikolay.diaur@aoe.com>
 */
class CommandLogRepository extends AbstractRepository {

    /**
     * Get Commands log by process id
     *
     * @param integer $processId
     * @return CommandLog
     * @throws \Searchperience\Common\Exception\InvalidArgumentException
     */
    public function getByProcessId($processId) {
        if (!is_numeric($processId)) {
            throw new InvalidArgumentException('Method "' . __METHOD__ . '" accepts only integer values as $processId. Input was: ' . serialize($processId));
        }

        $commandLog = $this->checkTypeAndDecorate($this->storageBackend->getByProcessId($processId));
        return $commandLog;
    }
}