<?php

namespace Searchperience\Api\Client\System\Storage;

/**
 * @author Michael Klapper <michael.klapper@aoemedia.de>
 * @date 14.11.12
 * @time 15:17
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
	 * @param \Searchperience\Api\Client\Domain\Document $document
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
	public function getByForeignId($foreignId);

	/**
	 * @param integer $foreignId
	 * @throwsException \Searchperience\System\Exception\UnauthorizedRequestException
	 * @throwsException \Searchperience\Domain\Exception\DocumentNotFoundException
	 * @return mixed
	 */
	public function deleteByForeignId($foreignId);
}
