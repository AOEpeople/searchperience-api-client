<?php

namespace Searchperience\Api\Client\Domain\Synonym;

use InvalidArgumentException;
use Searchperience\Api\Client\Domain\AbstractRepository;
use Searchperience\Api\Client\System\Storage\AbstractRestBackend;

/**
 * @author Timo Schmidt <timo.schmidt@aoe.com>
 */
class SynonymRepository extends AbstractRepository {

    /**
     * @var \Searchperience\Api\Client\Domain\Filters\AbstractFilterCollectionFactory
     */
    protected $filterCollectionFactory;

    /**
     * Injects the filter collection factory
     *
     * @param \Searchperience\Api\Client\Domain\Filters\AbstractFilterCollectionFactory $filterCollectionFactory
     */
    public function injectFilterCollectionFactory(\Searchperience\Api\Client\Domain\Filters\AbstractFilterCollectionFactory $filterCollectionFactory) {
        $this->filterCollectionFactory = $filterCollectionFactory;
    }

    /**
     * Method to retrieve all enrichment items by filters
     *
     * @param int $start
     * @param int $limit
     * @param array $filterArguments
     * @param string $sortingField
     * @param string $sortingType
     *
     * @throws \Searchperience\Common\Exception\InvalidArgumentException
     * @return SynonymCollection
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
        $synonyms = $this->getAllByFilterCollection($start, $limit, $filterCollection, $sortingField, $sortingType);

        return $synonyms;
    }

    /**
     * @param $start
     * @param $limit
     * @param \Searchperience\Api\Client\Domain\Filters\FilterCollection $filtersCollection
     * @param $sortingField
     * @param $sortingType
     * @return SynonymCollection
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

        $synonyms = $this->storageBackend->getAllByFilterCollection($start, $limit, $filtersCollection, $sortingField, $sortingType);
        return $this->decorateAll($synonyms);
    }

	/**
	 * Used to add a synonym (tagName needs to be setted)
	 *
	 * @param Synonym $synonym
	 * @return integer HTTP Status code
	 * @throws \InvalidArgumentException
	 */
	public function add(Synonym $synonym) {
		$violations = $this->validator->validate($synonym);

		if ($violations->count() > 0) {
			throw new \InvalidArgumentException('Given object of type "' . get_class($synonym) . '" is not valid: ' . PHP_EOL . $violations);
		}

		$status = $this->storageBackend->post($synonym);
		return $status;
	}

    /**
     * @param int $id
     * @return \Searchperience\Api\Client\Domain\Synonym\Synonym
     * @throws \InvalidArgumentException
     */
    public function getById($id) {
        if (!is_numeric($id)) {
            throw new \InvalidArgumentException('Method "' . __METHOD__ . '" accepts only numeric values as $id. Input was: ' . serialize($id));
        }

        return $this->checkTypeAndDecorate($this->storageBackend->getById($id));
    }

	/**
	 * @return SynonymCollection
	 */
	public function getAll() {
		return $this->decorateAll($this->storageBackend->getAll());
	}

	/**
	 * Deletes all synonyms for all tags
	 */
	public function deleteAll() {
		return $this->storageBackend->deleteAll();
	}

	/**
	 * Delete a synonym from the api.
	 * @param Synonym $synonym
	 * @return mixed
	 * @throws \InvalidArgumentException
	 */
	public function delete(Synonym $synonym) {
        return $this->deleteById($synonym->getId());
	}

    /**
     * Deletes synonym by id.
     *
     * @param string $id
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function deleteById($id) {
        if (!is_numeric($id)) {
            throw new \InvalidArgumentException('Method "' . __METHOD__ . '" accepts only numbers values as $id. Input was: ' . serialize($id));
        }

        return $this->storageBackend->deleteById($id);
    }

    public function pushAll() {
        return $this->storageBackend->pushAll();
    }
}