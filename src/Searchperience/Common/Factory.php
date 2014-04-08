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
