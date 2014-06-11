<?php

namespace Searchperience\Common\Http\Exception;

/**
 * Exception when a client error is encountered (4xx codes)
 */
class ClientErrorResponseException extends \Searchperience\Common\Exception\RuntimeException implements \Searchperience\Common\Exception\SearchperienceException {

	/**
	 * @var \Guzzle\Http\Message\Response
	 */
	protected $response;

	/**
	 * @param \Guzzle\Http\Message\Response $response
	 */
	public function setResponse($response) {
		$this->response = $response;
	}

	/**
	 * @return \Guzzle\Http\Message\Response
	 */
	public function getResponse() {
		return $this->response;
	}
}
