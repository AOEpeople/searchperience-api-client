<?php
/**
 * Created by IntelliJ IDEA.
 * User: pavelbogomolenko
 * Date: 07/07/14
 * Time: 13:38
 * To change this template use File | Settings | File Templates.
 */

namespace Searchperience\Api\Client\Domain;

use Searchperience\Common\Exception\InvalidArgumentException;
use Searchperience\Api\Client\Domain\Insight;
use Searchperience\Api\Client\Domain;
use Searchperience\Api\Client\System\Storage\AbstractRestBackend;

/**
 * Class AbstractRepository
 * @package Searchperience\Api\Client\Domain
 */
abstract class AbstractRepository implements DecoratableEntityInterface {

	/**
	 * @var string
	 */
	protected $entityCollectionName;

	/**
	 * @var \Searchperience\Api\Client\System\Storage\AbstractRestBackend
	 */
	protected $storageBackend;

	/**
	 * @var \Symfony\Component\Validator\ValidatorInterface
	 */
	protected $validator;

	/**
	 * @var \Searchperience\Api\Client\Domain\ActivityLogs\Filters\FilterCollectionFactory
	 */
	protected $filterCollectionFactory;

	/**
	 * Injects the filter collection factory
	 *
	 * @param \Searchperience\Api\Client\Domain\Filters\AbstractFilterCollectionFactory $filterCollectionFactory
	 * @return void
	 */
	public function injectFilterCollectionFactory(\Searchperience\Api\Client\Domain\Filters\AbstractFilterCollectionFactory $filterCollectionFactory) {
		$this->filterCollectionFactory = $filterCollectionFactory;
	}

	/**
	 * Injects the storage backend.
	 *
	 * @param \Searchperience\Api\Client\System\Storage\AbstractRestBackend $storageBackend
	 * @return void
	 */
	public function injectStorageBackend(\Searchperience\Api\Client\System\Storage\AbstractRestBackend $storageBackend) {
		$this->storageBackend = $storageBackend;
	}

	/**
	 * Injects the validation service
	 *
	 * @param \Symfony\Component\Validator\ValidatorInterface $documentValidator
	 * @return void
	 */
	public function injectValidator(\Symfony\Component\Validator\ValidatorInterface $documentValidator) {
		$this->validator = $documentValidator;
	}

	/**
	 * @param string $entityCollectionName
	 */
	public function setEntityCollectionName($entityCollectionName) {
		$this->entityCollectionName = $entityCollectionName;
	}

	/**
	 * @return \Searchperience\Api\Client\Domain\AbstractEntityCollection $this->entityCollection
	 */
	protected function getEntityCollection() {
		return new $this->entityCollectionName();
	}

	/**
	 * @param AbstractEntityCollection $collection
	 * @return mixed|string
	 * @throws \Searchperience\Common\Exception\InvalidArgumentException
	 */
	public function decorateAll(AbstractEntityCollection $collection) {

		$entityCollection = $this->getEntityCollection();

		if(!$entityCollection instanceof AbstractEntityCollection) {
			throw new InvalidArgumentException(
				sprintf('type param must be an instance of Searchperience\Api\Client\Domain\AbstractEntityCollection. %s given', get_class($entityCollection)),
				123456781231239124
			);
		}

		$entityCollection->setTotalCount($collection->getTotalCount());
		foreach ($collection as $entity) {
			$entityCollection->append($this->checkTypeAndDecorate($entity));
		}
		return $entityCollection;
	}

	/**
	 * @param mixed $entity
	 * @return mixed
	 */
	public function checkTypeAndDecorate($entity) {
		if($entity !== null) {
			return $this->decorate($entity);
		}

		return $entity;
	}

	/**
	 * @param AbstractEntity $entity
	 * @return AbstractEntity
	 */
	public function decorate(AbstractEntity $entity) {
		return $entity;
	}

	/**
	 * Method to retrieve all entities by filters
	 *
	 * @param int $start
	 * @param int $limit
	 * @param array $filterArguments
	 * @param string $sortingField
	 * @param $sortingType
	 * @return AbstractEntityCollection
	 * @throws InvalidArgumentException
	 */
	public function getAllByFilters($start = 0, $limit = 10, array $filterArguments = array(), $sortingField = '', $sortingType = AbstractRestBackend::SORTING_DESC) {
		if (!is_integer($start)) {
			throw new InvalidArgumentException('Method "' . __METHOD__ . '" accepts only integer values as $start. Input was: ' . serialize($start));
		}
		if (!is_integer($limit)) {
			throw new InvalidArgumentException('Method "' . __METHOD__ . '" accepts only integer values as $limit. Input was: ' . serialize($limit));
		}
		if (!is_array($filterArguments)) {
			throw new InvalidArgumentException('Method "' . __METHOD__ . '" accepts only integer values as $filterArguments. Input was: ' . serialize($filterArguments));
		}
		if (!is_string($sortingField)) {
			throw new InvalidArgumentException('Method "' . __METHOD__ . '" accepts only string values as $sortingField. Input was: ' . serialize($sortingField));
		}
		if (!is_string($sortingType)) {
			throw new InvalidArgumentException('Method "' . __METHOD__ . '" accepts only string values as $sortingType. Input was: ' . serialize($sortingType));
		}

		$filterCollection = $this->filterCollectionFactory->createFromFilterArguments($filterArguments);
		return $this->getAllByFilterCollection($start, $limit, $filterCollection, $sortingField, $sortingType);
	}

	/**
	 * @param int $start
	 * @param int $limit
	 * @param \Searchperience\Api\Client\Domain\Filters\FilterCollection $filtersCollection
	 * @param string $sortingField
	 * @param string $sortingType
	 *
	 * @return AbstractEntityCollection
	 * @throws \Searchperience\Common\Exception\InvalidArgumentException
	 */
	public function getAllByFilterCollection($start, $limit, \Searchperience\Api\Client\Domain\Filters\FilterCollection $filtersCollection = null, $sortingField = '', $sortingType = AbstractRestBackend::SORTING_DESC) {
		if (!is_integer($start)) {
			throw new InvalidArgumentException('Method "' . __METHOD__ . '" accepts only integer values as $start. Input was: ' . serialize($start));
		}
		if (!is_integer($limit)) {
			throw new InvalidArgumentException('Method "' . __METHOD__ . '" accepts only integer values as $limit. Input was: ' . serialize($limit));
		}
		if (!is_string($sortingField)) {
			throw new InvalidArgumentException('Method "' . __METHOD__ . '" accepts only string values as $sortingField. Input was: ' . serialize($sortingField));
		}
		if (!is_string($sortingType)) {
			throw new InvalidArgumentException('Method "' . __METHOD__ . '" accepts only string values as $sortingType. Input was: ' . serialize($sortingType));
		}

		$activityLogs = $this->storageBackend->getAllByFilterCollection($start, $limit, $filtersCollection, $sortingField, $sortingType);

		return $this->decorateAll($activityLogs);
	}
}