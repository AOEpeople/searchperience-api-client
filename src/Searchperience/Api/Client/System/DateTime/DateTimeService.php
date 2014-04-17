<?php

namespace Searchperience\Api\Client\System\DateTime;


/**
 * Class AbstractDateFilter
 * @package Searchperience\Api\Client\System\DateTime
 */
class DateTimeService {
	/**
	 * @var string
	 */
	protected $targetSystemTimeZone = 'UTC';

	/**
	 * @var string
	 */
	protected $targetSystemDateFormat = 'Y-m-d H:i:s';

	/**
	 * @param string $targetSystemDateFormat
	 */
	public function setTargetSystemDateFormat($targetSystemDateFormat) {
		$this->targetSystemDateFormat = $targetSystemDateFormat;
	}

	/**
	 * @return string
	 */
	public function getTargetSystemDateFormat() {
		return $this->targetSystemDateFormat;
	}

	/**
	 * @param string $targetSystemTimeZone
	 */
	public function setTargetSystemTimeZone($targetSystemTimeZone) {
		$this->targetSystemTimeZone = $targetSystemTimeZone;
	}

	/**
	 * @return string
	 */
	public function getTargetSystemTimeZone() {
		return $this->targetSystemTimeZone;
	}

	/**
	 * @param string $dateString
	 * @return \DateTime
	 */
	public function getDateTimeFromApiDateString($dateString) {
		return $this->getDateTimeFromFormat($this->targetSystemDateFormat, $dateString, $this->targetSystemTimeZone);
	}

	/**
	 * @param \DateTime $dateTime
	 * @return string
	 */
	public function getDateStringFromDateTime(\DateTime $dateTime) {
		$savedTimeZone = $dateTime->getTimezone();
		$dateTime->setTimezone(new \DateTimeZone($this->targetSystemTimeZone));
		$restApiDateString = $dateTime->format($this->targetSystemDateFormat);
		$dateTime->setTimezone($savedTimeZone);

		return $restApiDateString;
	}

	/**
	 * @param string $timeFormat
	 * @param string $dateString
	 * @param int $timeZoneConstant
	 * @return \DateTime
	 */
	protected function getDateTimeFromFormat($timeFormat, $dateString, $timeZoneConstant) {
		$timeZone = new \DateTimeZone($timeZoneConstant);
		return \DateTime::createFromFormat($timeFormat, $dateString, $timeZone);
	}
}
