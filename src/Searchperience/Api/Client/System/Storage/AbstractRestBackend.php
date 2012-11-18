<?php

namespace Searchperience\Api\Client\System\Storage;

/**
 * User: Michael Klapper
 * Date: 16.11.12
 * Time: 21:19
 */
abstract class AbstractRestBackend {

	/**
	 * @var string
	 */
	protected $username;

	/**
	 * @var
	 */
	protected $password;

	/**
	 * @string
	 */
	protected $baseUrl;

	/**
	 * Set the username to access the api.
	 *
	 * @param string $username
	 * @throws \Searchperience\Common\Exception\InvalidArgumentException
	 * @return void
	 */
	public function setUsername($username) {
		if (!is_string($username) || $username === '') {
			throw new \Searchperience\Common\Exception\InvalidArgumentException('baseUrl cannot be empty string.');
		}
		$this->username = $username;
	}

	/**
	 * Set the password to access the api.
	 *
	 * @param string $password
	 * @throws \Searchperience\Common\Exception\InvalidArgumentException
	 * @return void
	 */
	public function setPassword($password) {
		if (!is_string($password) || $password === '') {
			throw new \Searchperience\Common\Exception\InvalidArgumentException('baseUrl cannot be empty string.');
		}
		$this->password = $password;
	}

	/**
	 * Set the api base url including the customer path key.
	 *
	 * @param string $baseUrl Example: http://api.searchperience.com/bosch/
	 * @throws \Searchperience\Common\Exception\InvalidArgumentException
	 * @return void
	 */
	public function setBaseUrl($baseUrl) {
		if (!is_string($baseUrl) || $baseUrl === '') {
			throw new \Searchperience\Common\Exception\InvalidArgumentException('baseUrl cannot be empty string.');
		}
		$this->baseUrl = $baseUrl;
	}

	/**
	 * @param integer $statusCode
	 */
	protected function transformStatusCodeToException($statusCode) {

		// HTTP informational
		if ($statusCode >= 100 && $statusCode <= 199) {

		}

		// HTTP redirection
		if ($statusCode >= 300 && $statusCode <= 399) {

		}

		// HTTP client error
		if ($statusCode >= 400 && $statusCode <= 499) {

		}

		// HTTP server error
		if ($statusCode >= 500 && $statusCode <= 599) {

		}
	}
}
