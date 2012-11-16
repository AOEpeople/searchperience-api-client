<?php

namespace Searchperience\Api\Client\System\Storage;

/**
 * User: michael.klapper
 * Date: 14.11.12
 * Time: 15:17
 */
interface StorageDocumentBackendInterface {

	/**
	 * @param string $username
	 * @return void
	 */
	public function setUsername($username);

	/**
	 * @param string $password
	 * @return void
	 */
	public function setPassword($password);

	/**
	 * @param string $baseUrl http://playground.saascluster.local/bosch/
	 * @return mixed
	 */
	public function setBaseUrl($baseUrl);

	/**
	 * @param \Searchperience\Domain\Document $document
	 * @throwsException \Searchperience\System\Exception\UnauthorizedRequestException
	 * @return mixed
	 */
	public function post(\Searchperience\Api\Client\Domain\Document $document);

	/**
	 * @param integer $foreignId
	 * @throwsException \Searchperience\System\Exception\UnauthorizedRequestException
	 * @throwsException \Searchperience\Domain\Exception\DocumentNotFoundException
	 * @return mixed
	 */
	public function get($foreignId);

	/**
	 * @param integer $foreignId
	 * @throwsException \Searchperience\System\Exception\UnauthorizedRequestException
	 * @throwsException \Searchperience\Domain\Exception\DocumentNotFoundException
	 * @return mixed
	 */
	public function delete($foreignId);
}
