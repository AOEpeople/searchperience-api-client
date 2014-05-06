<?php

namespace Searchperience\Api\Client\Domain;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Timo Schmidt <timo.schmidt@aoe.com>
 */
abstract class AbstractEntity {

	/**
	 * This method should only be called from the framework in the reconstitution
	 * to be able to have readonly properties.
	 *
	 * @param $propertyName
	 * @param $propertyValue
	 */
	public function __setProperty($propertyName, $propertyValue) {
		$callers=debug_backtrace();
		$callerClassName = $callers[1]['class'];


		if(strpos($callerClassName,'Test') === false && strpos($callerClassName,'Backend') === false) {
			throw new \Searchperience\Common\Exception\RuntimeException('Only backend classes and tests are allowed to call __setProperty', 1386845453);
		}

		if(!property_exists($this,$propertyName)) {
			throw new \Searchperience\Common\Exception\InvalidArgumentException('Try to set unexisting property '.htmlspecialchars($propertyName), 1386845432);
		}

		$this->$propertyName = $propertyValue;
	}

	/**
	 * @return void
	 */
	public function afterReconstitution() {}

}