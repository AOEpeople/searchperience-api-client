<?php

namespace Searchperience\Tests;

/**
 * @author Michael Klapper <michael.klapper@aoemedia.de>
 * @date 14.11.12
 * @time 17:51
 */
class BaseTestCase extends \PHPUnit_Framework_TestCase {

	/**
	 * Returns the content of a given fixture file.
	 *
	 * @param string $fixture Relative path to the fixture file.
	 * @throws \PHPUnit_Framework_Exception
	 * @return string
	 */
	protected function getFixtureContent($fixture) {
		$fixture = '/' . ltrim($fixture, '/');
		$fixture = str_replace('/', DIRECTORY_SEPARATOR, $fixture);
		$fixtureFilePath = dirname(__FILE__) . $fixture;
		if (is_file($fixtureFilePath)) {
			$fixtureContent = file_get_contents($fixtureFilePath);
		} else {
			throw new \PHPUnit_Framework_Exception('Fixture file: "' . $fixtureFilePath . '" not found!');
		}

		return $fixtureContent;
	}
}
