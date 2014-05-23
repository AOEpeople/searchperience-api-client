<?php

namespace Searchperience\Common;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;

/**
 * @author Michael Klapper <michael.klapper@aoemedia.de>
 * @date 18.11.12
 */
class Factory {

	/**
	 * @var bool
	 */
	public static $HTTP_DEBUG = FALSE;

	/**
	 * Create a new instance of DocumentRepository
	 *
	 * @param string $baseUrl Example: http://api.searchperience.com/
	 * @param string $customerKey Example: qvc
	 * @param string $username
	 * @param string $password
	 *
	 * @throws \Searchperience\Common\Exception\RuntimeException
	 * @internal param string $customerkey
	 * @return \Searchperience\Api\Client\Domain\Document\DocumentRepository
	 */
	public static function getDocumentRepository($baseUrl, $customerKey, $username, $password) {
		self::getDepedenciesAutoloaded();
		$guzzle 			= self::getPreparedGuzzleClient($customerKey);
		$dateTimeService 	= new \Searchperience\Api\Client\System\DateTime\DateTimeService();

		$documentStorage 	= new \Searchperience\Api\Client\System\Storage\RestDocumentBackend();
		$documentStorage->injectRestClient($guzzle);
		$documentStorage->injectDateTimeService($dateTimeService);
		$documentStorage->setBaseUrl($baseUrl);
		$documentStorage->setUsername($username);
		$documentStorage->setPassword($password);

		$documentRepository = new \Searchperience\Api\Client\Domain\Document\DocumentRepository();
		$documentRepository->injectStorageBackend($documentStorage);
		$documentRepository->injectFilterCollectionFactory(new \Searchperience\Api\Client\Domain\Document\Filters\FilterCollectionFactory());
		$documentRepository->injectValidator(\Symfony\Component\Validator\Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator());

		return $documentRepository;
	}

	/**
	 * Create a new instance of DocumentStatusRepository
	 *
	 * @param string $baseUrl Example: http://api.searchperience.com/
	 * @param string $customerKey Example: qvc
	 * @param string $username
	 * @param string $password
	 *
	 * @throws \Searchperience\Common\Exception\RuntimeException
	 * @internal param string $customerkey
	 * @return \Searchperience\Api\Client\Domain\Document\DocumentStatusRepository
	 */
	public static function getDocumentStatusRepository($baseUrl, $customerKey, $username, $password) {
		self::getDepedenciesAutoloaded();
		$guzzle 			= self::getPreparedGuzzleClient($customerKey);
		$dateTimeService 	= new \Searchperience\Api\Client\System\DateTime\DateTimeService();

		$documentStorage 	= new \Searchperience\Api\Client\System\Storage\RestDocumentStatusBackend();
		$documentStorage->injectRestClient($guzzle);
		$documentStorage->injectDateTimeService($dateTimeService);
		$documentStorage->setBaseUrl($baseUrl);
		$documentStorage->setUsername($username);
		$documentStorage->setPassword($password);

		$documentStatusRepository = new \Searchperience\Api\Client\Domain\Document\DocumentStatusRepository();
		$documentStatusRepository->injectStorageBackend($documentStorage);

		return $documentStatusRepository;
	}

	/**
	 * Create the document service that encapsulates a few document operations.
	 *
	 * @param $baseUrl
	 * @param $customerKey
	 * @param $username
	 * @param $password
	 * @return \Searchperience\Api\Client\Domain\Document\DocumentService
	 */
	public static function getDocumentService($baseUrl, $customerKey, $username, $password) {
		$documentRepository = self::getDocumentRepository($baseUrl, $customerKey, $username, $password);
		$documentService 	= new \Searchperience\Api\Client\Domain\Document\DocumentService();
		$documentService->injectDocumentRepository($documentRepository);

		return $documentService;
	}

	/**
	 * @param string $baseUrl
	 * @param string $customerKey
	 * @param string $username
	 * @param string $password
	 * @return \Searchperience\Api\Client\Domain\Document\UrlQueueItemRepository
	 */
	public static function getUrlQueueItemRepository($baseUrl, $customerKey, $username, $password) {
		self::getDepedenciesAutoloaded();

		$guzzle 			= self::getPreparedGuzzleClient($customerKey);
		$dateTimeService 	= new \Searchperience\Api\Client\System\DateTime\DateTimeService();

		$urlQueueItemStorage 	= new \Searchperience\Api\Client\System\Storage\RestUrlQueueItemBackend();
		$urlQueueItemStorage->injectRestClient($guzzle);
		$urlQueueItemStorage->injectDateTimeService($dateTimeService);
		$urlQueueItemStorage->setBaseUrl($baseUrl);
		$urlQueueItemStorage->setUsername($username);
		$urlQueueItemStorage->setPassword($password);

		$urlQueueItemRepository = new \Searchperience\Api\Client\Domain\UrlQueueItem\UrlQueueItemRepository();
		$urlQueueItemRepository->injectStorageBackend($urlQueueItemStorage);
		$urlQueueItemRepository->injectFilterCollectionFactory(new \Searchperience\Api\Client\Domain\UrlQueueItem\Filters\FilterCollectionFactory());
		$urlQueueItemRepository->injectValidator(\Symfony\Component\Validator\Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator());

		return $urlQueueItemRepository;
	}

	/**
	 * @param string $baseUrl
	 * @param string $customerKey
	 * @param string $username
	 * @param string $password
	 * @return \Searchperience\Api\Client\Domain\Document\UrlQueueItemRepository
	 */
	public static function getUrlQueueStatusRepository($baseUrl, $customerKey, $username, $password) {
		self::getDepedenciesAutoloaded();

		$guzzle 			= self::getPreparedGuzzleClient($customerKey);

		$urlQueueStatusStorage 	= new \Searchperience\Api\Client\System\Storage\RestUrlQueueStatusBackend();
		$urlQueueStatusStorage->injectRestClient($guzzle);
		$urlQueueStatusStorage->setBaseUrl($baseUrl);
		$urlQueueStatusStorage->setUsername($username);
		$urlQueueStatusStorage->setPassword($password);

		$urlQueueStatusRepository = new \Searchperience\Api\Client\Domain\UrlQueueItem\UrlQueueStatusRepository();
		$urlQueueStatusRepository->injectStorageBackend($urlQueueStatusStorage);

		return $urlQueueStatusRepository;
	}


	/**
	 * @param string $baseUrl
	 * @param string $customerKey
	 * @param string $username
	 * @param string $password
	 * @return \Searchperience\Api\Client\Domain\Enrichment\EnrichmentRepository
	 */
	public static function getEnrichmentRepository($baseUrl, $customerKey, $username, $password) {
		self::getDepedenciesAutoloaded();
		$guzzle 			= self::getPreparedGuzzleClient($customerKey);

		$dateTimeService 	= new \Searchperience\Api\Client\System\DateTime\DateTimeService();

		$enrichmentStorage 	= new \Searchperience\Api\Client\System\Storage\RestEnrichmentBackend();
		$enrichmentStorage->injectRestClient($guzzle);
		$enrichmentStorage->injectDateTimeService($dateTimeService);
		$enrichmentStorage->setBaseUrl($baseUrl);
		$enrichmentStorage->setUsername($username);
		$enrichmentStorage->setPassword($password);

		$enrichmentRepository = new \Searchperience\Api\Client\Domain\Enrichment\EnrichmentRepository();
		$enrichmentRepository->injectStorageBackend($enrichmentStorage);
		$enrichmentRepository->injectFilterCollectionFactory(new \Searchperience\Api\Client\Domain\Enrichment\Filters\FilterCollectionFactory());
		$enrichmentRepository->injectValidator(\Symfony\Component\Validator\Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator());

		return $enrichmentRepository;
	}

	/**
	 * @param string $baseUrl
	 * @param string $customerKey
	 * @param string $username
	 * @param string $password
	 * @return \Searchperience\Api\Client\Domain\Synonym\SynonymRepository
	 */
	public static function getSynonymRepository($baseUrl, $customerKey, $username, $password) {
		self::getDepedenciesAutoloaded();
		$guzzle 			= self::getPreparedGuzzleClient($customerKey);
		$dateTimeService 	= new \Searchperience\Api\Client\System\DateTime\DateTimeService();
		$synonymStorage 	= new \Searchperience\Api\Client\System\Storage\RestSynonymBackend();
		$synonymStorage->injectRestClient($guzzle);
		$synonymStorage->injectDateTimeService($dateTimeService);
		$synonymStorage->setBaseUrl($baseUrl);
		$synonymStorage->setUsername($username);
		$synonymStorage->setPassword($password);

		$synonymRepository = new \Searchperience\Api\Client\Domain\Synonym\SynonymRepository();
		$synonymRepository->injectStorageBackend($synonymStorage);
		$synonymRepository->injectValidator(\Symfony\Component\Validator\Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator());

		return $synonymRepository;
	}


	/**
	 * @param $customerKey
	 * @return \Guzzle\Http\Client
	 * @throws Exception\RuntimeException
	 */
	protected static function getPreparedGuzzleClient($customerKey) {
		$guzzle = new \Guzzle\Http\Client();
		$guzzle->setConfig(array(
			'customerKey' => $customerKey,
			'redirect.disable' => true
		));

		if (self::$HTTP_DEBUG === TRUE) {
			if (class_exists('\Guzzle\Plugin\Log\LogPlugin')) {
				$guzzle->addSubscriber(\Guzzle\Plugin\Log\LogPlugin::getDebugPlugin());
				return $guzzle;
			} else {
				throw new \Searchperience\Common\Exception\RuntimeException('Please run "composer install --dev" to install "guzzle/plugin-log"');
			}
		}
		return $guzzle;
	}

	protected static function getDepedenciesAutoloaded() {
		// TODO resolve this "autoloading" in a right way
		class_exists('\Symfony\Component\Validator\Constraints\Url');
		class_exists('\Symfony\Component\Validator\Constraints\NotBlank');
		class_exists('\Symfony\Component\Validator\Constraints\Length');
	}
}
