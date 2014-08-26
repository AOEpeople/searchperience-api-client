<?php

namespace Searchperience\Api\Client\Domain\Synonym;

use Searchperience\Api\Client\Domain\AbstractRepository;

/**
 * @author Timo Schmidt <timo.schmidt@aoe.com>
 */
class SynonymRepository extends AbstractRepository {

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

		$status = $this->storageBackend->post($synonym->getTagName(), $synonym);
		return $status;
	}


	/**
	 * @param string $mainWord
	 * @param string $tagName
	 * @return \Searchperience\Api\Client\Domain\Synonym\Synonym
	 * @throws \InvalidArgumentException
	 */
	public function getByMainWord($mainWord, $tagName) {
		if (!is_string($mainWord)) {
			throw new \InvalidArgumentException('Method "' . __METHOD__ . '" accepts only strings values as $mainWord. Input was: ' . serialize($mainWord));
		}

		if (!is_string($tagName)) {
			throw new \InvalidArgumentException('Method "' . __METHOD__ . '" accepts only strings values as $tagName. Input was: ' . serialize($tagName));
		}

		return $this->checkTypeAndDecorate($this->storageBackend->getByMainWord($tagName, $mainWord));
	}

	/**
	 * @return SynonymCollection
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
	 * Deletes all synonyms for all tags
	 */
	public function deleteAll() {
		return $this->storageBackend->deleteAll();
	}

	/**
	 * Delete a synonym from the api.
	 * @param Synonym $synonym
	 * @return mixed
	 */
	public function delete(Synonym $synonym) {
		return $this->deleteByMainWord($synonym->getMainWord(), $synonym->getTagName());
	}

	/**
	 * Deletes synonym by mainWord and tagName.
	 *
	 * @param string $mainWord
	 * @param string $tagName
	 * @return mixed
	 * @throws \InvalidArgumentException
	 */
	public function deleteByMainWord($mainWord, $tagName) {
		if (!is_string($mainWord)) {
			throw new \InvalidArgumentException('Method "' . __METHOD__ . '" accepts only strings values as $mainWord. Input was: ' . serialize($mainWord));
		}

		if (!is_string($tagName)) {
			throw new \InvalidArgumentException('Method "' . __METHOD__ . '" accepts only strings values as $tagName. Input was: ' . serialize($tagName));
		}

		return $this->storageBackend->deleteByMainWord($tagName, $mainWord);
	}
}