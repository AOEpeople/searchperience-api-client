<?php

namespace Searchperience\Api\Client\System\Storage;

use Guzzle\Http\Client;
use Searchperience\Api\Client\Domain\CommandLog\CommandLogCollection;
use Searchperience\Common\Http\Exception\EntityNotFoundException;

/**
 * Class RestCommandLogBackend
 * @package Searchperience\Api\Client\System\Storage
 */
class RestCommandLogBackend extends \Searchperience\Api\Client\System\Storage\AbstractRestBackend implements \Searchperience\Api\Client\System\Storage\CommandLogBackendInterface {

	/**
	 * @var string
	 */
	protected $endpoint = 'commandlogs';


    /**
     * {@inheritdoc}
     * @param int $processId
     * @return \Searchperience\Api\Client\Domain\Document\Document|null
     * @throws \Searchperience\Common\Exception\RuntimeException
     */
    public function getByProcessId($processId) {
        try {
            $response = $this->getGetResponseFromEndpoint('/' . $processId);
            return $this->buildCommandLogFromXml($response->xml());
        } catch (EntityNotFoundException $e) {
            return null;
        }
    }

	/**
	 * {@inheritdoc}
	 * @param int $start
	 * @param int $limit
	 * @param \Searchperience\Api\Client\Domain\Filters\FilterCollection $filtersCollection
	 * @param string $sortingField = ''
	 * @param string $sortingType = desc
	 * @return \Searchperience\Api\Client\Domain\CommandLog\CommandLogCollection
	 * @throws \Searchperience\Common\Exception\RuntimeException
	 */
	public function getAllByFilterCollection($start, $limit, \Searchperience\Api\Client\Domain\Filters\FilterCollection $filtersCollection = null, $sortingField = '', $sortingType = self::SORTING_DESC ) {
		try {
			$response   = $this->getListResponseFromEndpoint($start, $limit, $filtersCollection, $sortingField, $sortingType);
			$xmlElement = $response->xml();
		} catch (EntityNotFoundException $e) {
			return new CommandLogCollection();
		}
		return $this->buildCommandLogsFromXml($xmlElement);
	}

    /**
     * @param \SimpleXMLElement $xml
     *
     * @return \Searchperience\Api\Client\Domain\Document\Document
     */
    protected function buildCommandLogFromXml(\SimpleXMLElement $xml) {
        $commandLogs = $this->buildCommandLogsFromXml($xml);
        return reset($commandLogs);
    }

	/**
	 * @param \SimpleXMLElement $xml
	 * @return \Searchperience\Api\Client\Domain\Document\Document[]
	 */
	protected function buildCommandLogsFromXml(\SimpleXMLElement $xml) {
        $commandLogArray = new CommandLogCollection();
		if ($xml->totalCount instanceof \SimpleXMLElement) {
            $commandLogArray->setTotalCount((integer) $xml->totalCount->__toString());
		}

		$commandLogs=$xml->xpath('commandlog');

		foreach($commandLogs as $commandLog) {
			$commandLogObject = new \Searchperience\Api\Client\Domain\CommandLog\CommandLog();
            $commandLogObject->__setProperty('processId', (integer)$commandLog->processid);
            $commandLogObject->__setProperty('status', (string)$commandLog->status);
            $commandLogObject->__setProperty('logMessage', (string)$commandLog->log);
            $commandLogObject->__setProperty('commandName', (string)$commandLog->command);
            $commandLogObject->__setProperty('instanceName', (string)$commandLog->instancename);

			if (trim($commandLog->starttime) != '') {
				//we assume that the restapi always return y-m-d H:i:s in the utc format
				$startTime = $this->dateTimeService->getDateTimeFromApiDateString($commandLog->starttime);
				if($startTime instanceof  \DateTime) {
                    $commandLogObject->__setProperty('startTime', $startTime);
				}
			}

            if (trim($commandLog->endtime) != '') {
                //we assume that the restapi always return y-m-d H:i:s in the utc format
                $endTime = $this->dateTimeService->getDateTimeFromApiDateString($commandLog->endtime);
                if ($endTime instanceof \DateTime) {
                    $commandLogObject->__setProperty('endTime', $endTime);
                }
            }

            $commandLogObject->__setProperty('duration', (integer)$commandLog->duration);
            $commandLogObject->__setProperty('binary', (string)$commandLog->binary);
            $commandLogObject->afterReconstitution();

            $commandLogArray->append($commandLogObject);
		}
		return $commandLogArray;
	}

	/**
	 * Create an array containing only the available indexer command log property values.
	 *
	 * @param \Searchperience\Api\Client\Domain\AbstractEntity $commandLog
	 * @return array
	 */
	protected function buildRequestArray(\Searchperience\Api\Client\Domain\AbstractEntity $commandLog) {
		$valueArray = array();

		if (!is_null($commandLog->getCommandName())) {
			$valueArray['commandName'] = $commandLog->getCommandName();
		}

		if (!is_null($commandLog->getProcessId())) {
			$valueArray['processId'] = $commandLog->getProcessId();
		}

		if (!is_null($commandLog->getStatus())) {
			$valueArray['status'] = $commandLog->getStatus();
		}

		if ($commandLog->getStartTime() instanceof \DateTime) {
			$valueArray['startTime'] = $this->dateTimeService->getDateStringFromDateTime($commandLog->getStartTime());
		}

		if (!is_null($commandLog->getDuration())) {
			$valueArray['duration'] = $commandLog->getDuration();
		}

		if (!is_null($commandLog->getLogMessage())) {
			$valueArray['logMessage'] = $commandLog->getLogMessage();
		}

		if (!is_null($commandLog->getInstanceName())) {
			$valueArray['instanceName'] = $commandLog->getInstanceName();
		}

		return $valueArray;
	}
}