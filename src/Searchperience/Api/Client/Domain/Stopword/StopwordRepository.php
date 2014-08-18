<?php

namespace Searchperience\Api\Client\Domain\Stopword;

use Searchperience\Api\Client\Domain\AbstractRepository;

/**
 * Class StopwordRepository
 * @package Searchperience\Api\Client\Domain\Stopword
 */
class StopwordRepository extends AbstractRepository {

	/**
	 * Used to add a stopword (tagName needs to be setted)
	 *
	 * @param Stopword $stopword
	 * @return integer HTTP Status code
	 * @throws \InvalidArgumentException
	 */
	public function add(Stopword $stopword) {
		$violations = $this->stopwordValidator->validate($stopword);

		if ($violations->count() > 0) {
			throw new \InvalidArgumentException('Given object of type "' . get_class($stopword) . '" is not valid: ' . PHP_EOL . $violations);
		}

		$status = $this->storageBackend->post($stopword->getTagName(), $stopword);
		return $status;
	}

	/**
	 * @param string $word
	 * @param string $tagName
	 * @return \Searchperience\Api\Client\Domain\Stopword\Stopword
	 * @throws \InvalidArgumentException
	 */
	public function getByWord($word, $tagName) {
		if (!is_string($word)) {
			throw new \InvalidArgumentException('Method "' . __METHOD__ . '" accepts only strings values as $mainWord. Input was: ' . serialize($word));
		}

		if (!is_string($tagName)) {
			throw new \InvalidArgumentException('Method "' . __METHOD__ . '" accepts only strings values as $tagName. Input was: ' . serialize($tagName));
		}

		return $this->checkTypeAndDecorate($this->storageBackend->getByWord($tagName, $word));
	}

	/**
	 * @return StopwordCollection
	 */
	public function getAll() {
		return $this->decorateAll($this->storageBackend->getAll(), 'Searchperience\Api\Client\Domain\Stopword\StopwordCollection');
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
		return $this->decorateAll($this->storageBackend->getAllByTag($tagName), 'Searchperience\Api\Client\Domain\Stopword\StopwordCollection');
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
		return $this->deleteByWord($stopword->getWord(), $stopword->getTagName());
	}

	/**
	 * Deletes stopword by word and tagName.
	 *
	 * @param string $word
	 * @param string $tagName
	 * @return mixed
	 * @throws \InvalidArgumentException
	 */
	public function deleteByWord($word, $tagName) {
		if (!is_string($word)) {
			throw new \InvalidArgumentException('Method "' . __METHOD__ . '" accepts only strings values as $mainWord. Input was: ' . serialize($word));
		}

		if (!is_string($word)) {
			throw new \InvalidArgumentException('Method "' . __METHOD__ . '" accepts only strings values as $tagName. Input was: ' . serialize($tagName));
		}

		return $this->storageBackend->deleteByWord($tagName, $word);
	}
}