<?php

namespace Searchperience\Common\Exception;

/**
 * @author: michael.klapper
 * @date: 18.11.12
 * @time: 09:58
 */
class RuntimeException extends \RuntimeException implements SearchperienceException {
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
