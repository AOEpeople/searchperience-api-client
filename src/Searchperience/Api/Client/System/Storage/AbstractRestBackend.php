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
	 * {@inheritdoc}
	 */
	public function setUsername($username) {
		$this->username = $username;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setPassword($password) {
		$this->password = $password;
	}

	/**
	 * @param string $baseUrl
	 * @return mixed
	 */
	public function setBaseUrl($baseUrl) {
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
