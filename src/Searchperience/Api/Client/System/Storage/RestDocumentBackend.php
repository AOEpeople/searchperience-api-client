<?php

namespace Searchperience\Api\Client\System\Storage;

use Guzzle\Http\Client;

/**
 * @author Michael Klapper <michael.klapper@aoemedia.de>
 * @date 14.11.12
 * @time 15:17
 */
class RestDocumentBackend implements \Searchperience\Api\Client\System\Storage\StorageDocumentBackendInterface {

	/**
	 * @var string
	 */
	protected $username;

	/**
	 * @var
	 */
	protected $password;

	/**
	 * @var \Guzzle\Http\Client
	 */
	protected $restClient;

	/**
	 * @param \Guzzle\Http\Client $restClient
	 * @return void
	 */
	public function injectRestClient(\Guzzle\Http\Client $restClient) {
		$this->restClient = $restClient;
	}

	public function __construct($baseUrl) {
		$this->restClient = new Client($baseUrl);
	}

	/**
	 * {@inheritdoc}
	 */
	public function post(\Searchperience\Api\Client\Domain\Document $document) {
		$url = 'documents?';
		$url .= 'foreignId='.$document->


		$this->restClient->post('', array(
			'X-Header' => 'My Header'
		), 'body of the request')->send();
	}

	/**
	 * @param integer $statusCode
	 */
	protected function transformStatusCode($statusCode) {

		switch ($statusCode) {
			case 200:
				// OK
			case 201:
				// created OK
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function get($foreignId) {
		$response = $this->restClient->get('/documents?foreignId=' . $foreignId)
			->setAuth($this->username, $this->password)
			->send();

		return $this->buildDocumentFromRequest($response->getBody());
	}

	/**
	 * {@inheritdoc}
	 */
	public function delete($foreignId) {
		// TODO: Implement delete() method.
	}

	/**
	 * {@inheritdoc}
	 */
	public function setUsername($username) {
		// TODO: Implement setUsername() method.
	}

	/**
	 * {@inheritdoc}
	 */
	public function setPassword($password) {
		// TODO: Implement setPassword() method.
	}

	/**
	 * @param string $baseUrl
	 * @return mixed
	 */
	public function setBaseUrl($baseUrl) {
		// TODO: Implement setBaseUrl() method.
	}

	/**
	 * @param string $response
	 * @return \Searchperience\Api\Client\Domain\Document
	 */
	protected function buildDocumentFromRequest($response) {
		$xml = simplexml_load_string($response); /** @var xml SimpleXMLElement */
		$documentAttributeArray = (array)$xml->document->attributes();

		$document = new \Searchperience\Api\Client\Domain\Document();
		$document->setId($documentAttributeArray['@attributes']['id']);

		return $document;
	}
}
