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
	 * @param string $baseUrl
	 * @param string $customerKey
	 * @param string $username
	 * @param string $password
	 * @return \Searchperience\Api\Client\Domain\Synonym\SynonymTagRepository
	 */
	public static function getSynonymTagRepository($baseUrl, $customerKey, $username, $password) {
		self::getDepedenciesAutoloaded();
		$guzzle 			= self::getPreparedGuzzleClient($customerKey);
		$dateTimeService 	= new \Searchperience\Api\Client\System\DateTime\DateTimeService();
		$synonymStorage 	= new \Searchperience\Api\Client\System\Storage\RestSynonymTagBackend();
		$synonymStorage->injectRestClient($guzzle);
		$synonymStorage->injectDateTimeService($dateTimeService);
		$synonymStorage->setBaseUrl($baseUrl);
		$synonymStorage->setUsername($username);
		$synonymStorage->setPassword($password);

		$synonymRepository = new \Searchperience\Api\Client\Domain\Synonym\SynonymTagRepository();
		$synonymRepository->injectStorageBackend($synonymStorage);

		return $synonymRepository;
	}

	/**
	 * @param $baseUrl
	 * @param $customerKey
	 * @param $username
	 * @param $password
	 * @return \Searchperience\Api\Client\Domain\Stopword\StopwordRepository
	 */
	public static function getStopwordRepository($baseUrl, $customerKey, $username, $password) {
		self::getDepedenciesAutoloaded();
		$guzzle = self::getPreparedGuzzleClient($customerKey);
		$dateTimeService = new \Searchperience\Api\Client\System\DateTime\DateTimeService();
		$stopwordStorage = new \Searchperience\Api\Client\System\Storage\RestStopwordBackend();
		$stopwordStorage->injectRestClient($guzzle);
		$stopwordStorage->injectDateTimeService($dateTimeService);
		$stopwordStorage->setBaseUrl($baseUrl);
		$stopwordStorage->setUsername($username);
		$stopwordStorage->setPassword($password);

		$stopwordRepository = new \Searchperience\Api\Client\Domain\Stopword\StopwordRepository();
		$stopwordRepository->injectStorageBackend($stopwordStorage);
		$stopwordRepository->injectValidator(\Symfony\Component\Validator\Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator());

		return $stopwordRepository;
	}

	/**
	 * @param string $baseUrl
	 * @param string $customerKey
	 * @param string $username
	 * @param string $password
	 * @return \Searchperience\Api\Client\Domain\Stopword\StopwordTagRepository
	 */
	public static function getStopwordTagRepository($baseUrl, $customerKey, $username, $password) {
		self::getDepedenciesAutoloaded();
		$guzzle = self::getPreparedGuzzleClient($customerKey);
		$dateTimeService = new \Searchperience\Api\Client\System\DateTime\DateTimeService();
		$stopwordStorage = new \Searchperience\Api\Client\System\Storage\RestStopwordTagBackend();
		$stopwordStorage->injectRestClient($guzzle);
		$stopwordStorage->injectDateTimeService($dateTimeService);
		$stopwordStorage->setBaseUrl($baseUrl);
		$stopwordStorage->setUsername($username);
		$stopwordStorage->setPassword($password);

		$stopwordRepository = new \Searchperience\Api\Client\Domain\Stopword\StopwordTagRepository();
		$stopwordRepository->injectStorageBackend($stopwordStorage);

		return $stopwordRepository;
	}

	/**
	 * @param string $baseUrl
	 * @param string $customerKey
	 * @param string $username
	 * @param string $password
	 * @return \Searchperience\Api\Client\Domain\Command\CommandExecutionService
	 */
	public static function getCommandExecutionService($baseUrl, $customerKey, $username, $password) {
		self::getDepedenciesAutoloaded();
		$guzzle 			= self::getPreparedGuzzleClient($customerKey);
		$commandBackend 	= new \Searchperience\Api\Client\System\Storage\RestCommandBackend();
		$commandBackend->injectRestClient($guzzle);
		$commandBackend->setBaseUrl($baseUrl);
		$commandBackend->setUsername($username);
		$commandBackend->setPassword($password);

		$commandExecutionService = new \Searchperience\Api\Client\Domain\Command\CommandExecutionService();
		$commandExecutionService->injectExecutionBackend($commandBackend);

		return $commandExecutionService;
	}

	/**
	 * @param string $baseUrl
	 * @param string $customerKey
	 * @param string $username
	 * @param string $password
	 * @return \Searchperience\Api\Client\Domain\Insight\ArtifactTypeRepository
	 */
	public static function getArtifactTypeRepository($baseUrl, $customerKey, $username, $password) {
		self::getDepedenciesAutoloaded();
		$guzzle 			= self::getPreparedGuzzleClient($customerKey);
		$dateTimeService 	= new \Searchperience\Api\Client\System\DateTime\DateTimeService();
		$storageBackend 	= new \Searchperience\Api\Client\System\Storage\RestArtifactTypeBackend();
		$storageBackend->injectRestClient($guzzle);
		$storageBackend->injectDateTimeService($dateTimeService);
		$storageBackend->setBaseUrl($baseUrl);
		$storageBackend->setUsername($username);
		$storageBackend->setPassword($password);

		$artifactTypeRepository = new \Searchperience\Api\Client\Domain\Insight\ArtifactTypeRepository();
		$artifactTypeRepository->injectStorageBackend($storageBackend);
		$artifactTypeRepository->injectValidator(\Symfony\Component\Validator\Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator());

		return $artifactTypeRepository;
	}

	/**
	 * @param string $baseUrl
	 * @param string $customerKey
	 * @param string $username
	 * @param string $password
	 * @return \Searchperience\Api\Client\Domain\Insight\ArtifactTypeRepository
	 */
	public static function getArtifactRepository($baseUrl, $customerKey, $username, $password) {
		self::getDepedenciesAutoloaded();
		$guzzle 			= self::getPreparedGuzzleClient($customerKey);
		$dateTimeService 	= new \Searchperience\Api\Client\System\DateTime\DateTimeService();
		$storageBackend 	= new \Searchperience\Api\Client\System\Storage\RestArtifactBackend();
		$storageBackend->injectRestClient($guzzle);
		$storageBackend->injectDateTimeService($dateTimeService);
		$storageBackend->setBaseUrl($baseUrl);
		$storageBackend->setUsername($username);
		$storageBackend->setPassword($password);

		$artifactRepository = new \Searchperience\Api\Client\Domain\Insight\ArtifactRepository();
		$artifactRepository->injectStorageBackend($storageBackend);
		$artifactRepository->injectValidator(\Symfony\Component\Validator\Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator());

		return $artifactRepository;
	}

	/**
	 * Create a new instance of AdminSearchRepository
	 *
	 * @param string $baseUrl Example: http://api.searchperience.com/
	 * @param string $customerKey Example: qvc
	 * @param string $username
	 * @param string $password
	 *
	 * @throws \Searchperience\Common\Exception\RuntimeException
	 * @internal param string $customerkey
	 * @return \Searchperience\Api\Client\Domain\AdminSearch\AdminSearchRepository
	 */
	public static function getAdminSearchRepository($baseUrl, $customerKey, $username, $password) {
		self::getDepedenciesAutoloaded();
		$guzzle 			= self::getPreparedGuzzleClient($customerKey);
		$dateTimeService 	= new \Searchperience\Api\Client\System\DateTime\DateTimeService();

		$adminSearchStorage	= new \Searchperience\Api\Client\System\Storage\RestAdminSearchBackend();
		$adminSearchStorage->injectRestClient($guzzle);
		$adminSearchStorage->injectDateTimeService($dateTimeService);
		$adminSearchStorage->setBaseUrl($baseUrl);
		$adminSearchStorage->setUsername($username);
		$adminSearchStorage->setPassword($password);

		$adminSearchRepository = new \Searchperience\Api\Client\Domain\AdminSearch\AdminSearchRepository();
		$adminSearchRepository->injectStorageBackend($adminSearchStorage);
		$adminSearchRepository->injectValidator(\Symfony\Component\Validator\Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator());

		return $adminSearchRepository;
	}

    /**
     * @param string $baseUrl
     * @param string $customerKey
     * @param string $username
     * @param string $password
     * @return \Searchperience\Api\Client\Domain\CommandLog\CommandLogRepository
     */
    public static function getCommandLogRepository($baseUrl, $customerKey, $username, $password) {
        self::getDepedenciesAutoloaded();

        $guzzle = self::getPreparedGuzzleClient($customerKey);
        $dateTimeService = new \Searchperience\Api\Client\System\DateTime\DateTimeService();

        $commandLogStorage = new \Searchperience\Api\Client\System\Storage\RestCommandLogBackend();
        $commandLogStorage->injectRestClient($guzzle);
        $commandLogStorage->injectDateTimeService($dateTimeService);
        $commandLogStorage->setBaseUrl($baseUrl);
        $commandLogStorage->setUsername($username);
        $commandLogStorage->setPassword($password);

        $commandLogRepository = new \Searchperience\Api\Client\Domain\CommandLog\CommandLogRepository();
        $commandLogRepository->injectStorageBackend($commandLogStorage);
        $commandLogRepository->injectFilterCollectionFactory(new \Searchperience\Api\Client\Domain\CommandLog\Filters\FilterCollectionFactory());
        $commandLogRepository->injectValidator(\Symfony\Component\Validator\Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator());

        return $commandLogRepository;
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

	/**
	 * load some dependencies
	 */
	protected static function getDepedenciesAutoloaded() {
		// TODO resolve this "autoloading" in a right way
		class_exists('\Symfony\Component\Validator\Constraints\Url');
		class_exists('\Symfony\Component\Validator\Constraints\NotBlank');
		class_exists('\Symfony\Component\Validator\Constraints\Length');
	}
}
