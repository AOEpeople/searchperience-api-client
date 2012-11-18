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
	 * @param string $baseUrl Example: http://api.searchperience.com/bosch/
	 * @param string $username
	 * @param string $password
	 *
	 * @return \Searchperience\Api\Client\Domain\DocumentRepository
	 */
	public function getDocumentRepository($baseUrl, $username, $password) {
		$documentStorage = new \Searchperience\Api\Client\System\Storage\RestDocumentBackend();
		$documentStorage->injectRestClient(new \Guzzle\Http\Client());
		$documentStorage->setBaseUrl($baseUrl);
		$documentStorage->setUsername($username);
		$documentStorage->setPassword($password);

		$documentRepository = new \Searchperience\Api\Client\Domain\DocumentRepository();
		$documentRepository->injectStorageBackend($documentStorage);

		return $documentRepository;
	}
}
