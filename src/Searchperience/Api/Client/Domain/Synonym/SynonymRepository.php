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
	 * @param array $synonyms
     * @param string $tagName
	 * @return \Searchperience\Api\Client\Domain\Synonym\Synonym
	 * @throws \InvalidArgumentException
	 */
	public function getBySynonyms($synonyms, $tagName) {
		if (!is_array($synonyms)) {
			throw new \InvalidArgumentException('Method "' . __METHOD__ . '" accepts only array of string values as $synonyms. Input was: ' . serialize($synonyms));
		}

		if (!is_string($tagName)) {
			throw new \InvalidArgumentException('Method "' . __METHOD__ . '" accepts only strings values as $tagName. Input was: ' . serialize($tagName));
		}

		return $this->checkTypeAndDecorate($this->storageBackend->getBySynonyms($tagName, $synonyms));
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
		return $this->deleteBySynonyms($synonym->getSynonyms(), $synonym->getTagName());
	}

	/**
	 * Deletes synonym by synonyms and tagName.
	 *
	 * @param array $synonyms
     * @param string $tagName
	 * @return mixed
	 * @throws \InvalidArgumentException
	 */
	public function deleteBySynonyms($synonyms, $tagName) {
		if (!is_array($synonyms)) {
			throw new \InvalidArgumentException('Method "' . __METHOD__ . '" accepts only array of string values as $synonyms. Input was: ' . serialize($synonyms));
		}

		if (!is_string($tagName)) {
			throw new \InvalidArgumentException('Method "' . __METHOD__ . '" accepts only strings values as $tagName. Input was: ' . serialize($tagName));
		}

		return $this->storageBackend->deleteBySynonyms($tagName, $synonyms);
	}
}