<?php
/**
 * @Author: Nikolay Diaur <nikolay.diaur@aoe.com>
 * @Date: 8/15/14
 * @Time: 3:02 PM
 */

namespace Searchperience\Api\Client\System\Storage;

use Searchperience\Api\Client\Domain\ActivityLogs\ActivityLogs;
use Searchperience\Api\Client\Domain\ActivityLogs\ActivityLogsCollection;
use Searchperience\Common\Http\Exception\EntityNotFoundException;

/**
 * Class RestActivityLogsBackend
 * @package Searchperience\Api\Client\System\Storage
 */
class RestActivityLogsBackend extends AbstractRestBackend implements ActivityLogsBackendInterface {
	/**
	 * @var string
	 */
	protected $endpoint = 'activitylogs';

	/**
	 * {@inheritdoc}
	 * @param int $id
	 * @return \Searchperience\Api\Client\Domain\Document\Document|null
	 * @throws \Searchperience\Common\Exception\RuntimeException
	 */
	public function getById($id) {
		try {
			$response = $this->getGetResponseFromEndpoint('/' . $id);
			return $this->buildActivityLogFromXml($response->xml());
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
	 * @return \Searchperience\Api\Client\Domain\ActivityLogs\ActivityLogsCollection
	 * @throws \Searchperience\Common\Exception\RuntimeException
	 */
	public function getAllByFilterCollection($start, $limit, \Searchperience\Api\Client\Domain\Filters\FilterCollection $filtersCollection = null, $sortingField = '', $sortingType = self::SORTING_DESC) {
		try {
			$response = $this->getListResponseFromEndpoint($start, $limit, $filtersCollection, $sortingField, $sortingType);
			$xmlElement = $response->xml();
		} catch (EntityNotFoundException $e) {
			return new ActivityLogsCollection();
		}
		return $this->buildActivityLogsFromXml($xmlElement);
	}

	/**
	 * @param \SimpleXMLElement $xml
	 * @return \Searchperience\Api\Client\Domain\Document\Document
	 */
	protected function buildActivityLogFromXml(\SimpleXMLElement $xml) {
		$activityLogs = $this->buildActivityLogsFromXml($xml);
		return reset($activityLogs);
	}

	/**
	 * @param \SimpleXMLElement $xml
	 * @return array|ActivityLogsCollection
	 */
	protected function buildActivityLogsFromXml(\SimpleXMLElement $xml) {
		$activityLogsCollection = new ActivityLogsCollection();

		if ($xml->totalCount instanceof \SimpleXMLElement) {
			$activityLogsCollection->setTotalCount((integer)$xml->totalCount->__toString());
		}

		$activityLogs = $xml->xpath('activitylogs');
		foreach ($activityLogs as $activityLog) {
			$activityLogsAttributeArray = (array)$activityLog->attributes();

			$activityLogsObject = new ActivityLogs();
			$activityLogsObject->__setProperty('id', (integer)$activityLogsAttributeArray['@attributes']['id']);
			if (trim($activityLog->logTime) != '') {
				//we assume that the restAPI always return y-m-d H:i:s in the utc format
				$logTime = $this->dateTimeService->getDateTimeFromApiDateString($activityLog->logTime);
				$activityLogsObject->__setProperty('logTime', $logTime);
			}
			$activityLogsObject->__setProperty('processId', (string)$activityLog->processId);
			$activityLogsObject->__setProperty('severity', (string)$activityLog->severity);
			$activityLogsObject->__setProperty('message', (string)$activityLog->message);
			$activityLogsObject->__setProperty('additionalData', (array)$activityLog->additionalData);
			$activityLogsObject->__setProperty('packageKey', (string)$activityLog->packageKey);
			$activityLogsObject->__setProperty('className', (string)$activityLog->className);
			$activityLogsObject->__setProperty('methodName', (string)$activityLog->methodName);
			$activityLogsObject->__setProperty('tag', (string)$activityLog->tag);

			$activityLogsCollection[] = $activityLogsObject;

			$activityLogsObject->afterReconstitution();
		}
		return $activityLogsCollection;
	}

	/**
	 * Create an array containing only the available activity logs property values.
	 *
	 * @param \Searchperience\Api\Client\Domain\AbstractEntity $activityLogs
	 * @return array
	 */
	protected function buildRequestArray(\Searchperience\Api\Client\Domain\AbstractEntity $activityLogs) {
		$valueArray = array();

		if (!is_null($activityLogs->getId())) {
			$valueArray['id'] = $activityLogs->getId();
		}

		if (!is_null($activityLogs->getLogTime())) {
			$valueArray['logTime'] = $activityLogs->getLogTime();
		}

		if (!is_null($activityLogs->getProcessId())) {
			$valueArray['processId'] = $activityLogs->getProcessId();
		}

		if (!is_null($activityLogs->getSeverity())) {
			$valueArray['severity'] = $activityLogs->getSeverity();
		}

		if (!is_null($activityLogs->getMessage())) {
			$valueArray['message'] = $activityLogs->getMessage();
		}

		if (!is_null($activityLogs->getAdditionalData())) {
			$valueArray['additionalData'] = $activityLogs->getAdditionalData();
		}

		if (!is_null($activityLogs->getPackageKey())) {
			$valueArray['packageKey'] = $activityLogs->getPackageKey();
		}

		if (!is_null($activityLogs->getClassName())) {
			$valueArray['className'] = $activityLogs->getClassName();
		}

		if (!is_null($activityLogs->getMethodName())) {
			$valueArray['methodName'] = $activityLogs->getMethodName();
		}

		if (!is_null($activityLogs->getTag())) {
			$valueArray['tag'] = $activityLogs->getTag();
		}
		return $valueArray;
	}
} 