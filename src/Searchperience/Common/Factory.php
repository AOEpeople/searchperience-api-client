<?php

namespace Searchperience\Common;

/**
 * @author Michael Klapper <michael.klapper@aoemedia.de>
 * @date 18.11.12
 */
class Factory {

	/**
	 * Create a new instance of DocumentRepository
	 *
	 * @param string $baseUrl Example: http://api.searchperience.com/
	 * @param string $customerKey Example: qvc
	 * @param string $username
	 * @param string $password
	 *
	 * @internal param string $customerkey
	 * @return \Searchperience\Api\Client\Domain\DocumentRepository
	 */
	public static function getDocumentRepository($baseUrl, $customerKey, $username, $password) {
		$guzzle = new \Guzzle\Http\Client();
		$guzzle->setConfig(array(
			'customerKey' => $customerKey,
		));
		$documentStorage = new \Searchperience\Api\Client\System\Storage\RestDocumentBackend();
		$documentStorage->injectRestClient($guzzle);
		$documentStorage->setBaseUrl($baseUrl);
		$documentStorage->setUsername($username);
		$documentStorage->setPassword($password);

		$documentRepository = new \Searchperience\Api\Client\Domain\DocumentRepository();
		$documentRepository->injectStorageBackend($documentStorage);

		return $documentRepository;
	}
}
