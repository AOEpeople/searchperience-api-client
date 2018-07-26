<?php

namespace Searchperience\Api\Client\Domain\Stopword;

use InvalidArgumentException;
use Searchperience\Api\Client\Domain\AbstractRepository;
use Searchperience\Api\Client\System\Storage\AbstractRestBackend;

/**
 * Class StopwordRepository
 * @package Searchperience\Api\Client\Domain\Stopword
 */
class StopwordRepository extends AbstractRepository {

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
     * @return StopwordCollection
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
        $stopwords = $this->getAllByFilterCollection($start, $limit, $filterCollection, $sortingField, $sortingType);

        return $stopwords;
    }

    /**
     * @param $start
     * @param $limit
     * @param \Searchperience\Api\Client\Domain\Filters\FilterCollection $filtersCollection
     * @param $sortingField
     * @param $sortingType
     * @return StopwordCollection
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

        $stopwords = $this->storageBackend->getAllByFilterCollection($start, $limit, $filtersCollection, $sortingField, $sortingType);
        return $this->decorateAll($stopwords);
    }

	/**
	 * Used to add a stopword (tagName needs to be setted)
	 *
	 * @param Stopword $stopword
	 * @return integer HTTP Status code
	 * @throws \InvalidArgumentException
	 */
	public function add(Stopword $stopword) {
        $violations = $this->validator->validate($stopword);

		if ($violations->count() > 0) {
			throw new \InvalidArgumentException('Given object of type "' . get_class($stopword) . '" is not valid: ' . PHP_EOL . $violations);
		}

		$status = $this->storageBackend->post($stopword);
		return $status;
	}

	/**
	 * @param int $id
	 * @return \Searchperience\Api\Client\Domain\Stopword\Stopword
	 * @throws \InvalidArgumentException
	 */
	public function getById($id) {
		if (!is_numeric($id)) {
			throw new \InvalidArgumentException('Method "' . __METHOD__ . '" accepts only numeric values as $id. Input was: ' . serialize($id));
		}

		return $this->checkTypeAndDecorate($this->storageBackend->getById($id));
	}

	/**
	 * @return StopwordCollection
	 */
	public function getAll() {
		return $this->decorateAll($this->storageBackend->getAll());
	}

	/**
	 * @param string $tagName
	 * @return mixed
	 * @throws \InvalidArgumentException
	 */
	public function getAllByTagName($tagName) {
		if (!is_string($tagName)) {
			throw new \InvalidArgumentException('Method "' . __METHOD__ . '" accepts only strings values as $tagName. Input was: ' . serialize($tagName));
		}
		return $this->decorateAll($this->storageBackend->getAllByTag($tagName));
	}

	/**
	 * Deletes all stopwords for all tags
	 */
	public function deleteAll() {
		return $this->storageBackend->deleteAll();
	}

	/**
	 * Delete a stopword from the api.
	 * @param Stopword $stopword
	 * @return mixed
	 */
	public function delete(Stopword $stopword) {
		return $this->deleteById($stopword->getId());
	}

	/**
	 * Deletes stopword by id.
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
}