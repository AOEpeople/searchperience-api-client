<?php

namespace Searchperience\Api\Client\Domain\Synonym;

/**
 * @author Timo Schmidt <timo.schmidt@aoe.com>
 */
class SynonymRepository {

	/**
	 * @var \Searchperience\Api\Client\System\Storage\SynonymBackendInterface
	 */
	protected $storageBackend;

	/**
	 * @var \Symfony\Component\Validator\ValidatorInterface
	 */
	protected $synonymValidator;

	/**
	 * Injects the storage backend.
	 *
	 * @param \Searchperience\Api\Client\System\Storage\SynonymBackendInterface $storageBackend
	 * @return void
	 */
	public function injectStorageBackend(\Searchperience\Api\Client\System\Storage\SynonymBackendInterface $storageBackend) {
		$this->storageBackend = $storageBackend;
	}

	/**
	 * Injects the validation service
	 *
	 * @param \Symfony\Component\Validator\ValidatorInterface $synonymValidator
	 * @return void
	 */
	public function injectValidator(\Symfony\Component\Validator\ValidatorInterface $synonymValidator) {
		$this->synonymValidator = $synonymValidator;
	}

	/**
	 * Used to add a synonym (tagName needs to be setted)
	 *
	 * @param Synonym $synonym
	 * @return integer HTTP Status code
	 */
	public function add(Synonym $synonym) {
		$violations = $this->synonymValidator->validate($synonym);

		if ($violations->count() > 0) {
			throw new InvalidArgumentException('Given object of type "' . get_class($synonym) . '" is not valid: ' . PHP_EOL . $violations);
		}

		$status = $this->storageBackend->post($synonym->getTagName(), $synonym);
		return $status;
	}


	/**
	 * @param string $mainWord
	 * @param string $tagName
	 * @return \Searchperience\Api\Client\Domain\Synonym\Synonym
	 * @throws InvalidArgumentException
	 */
	public function getByMainWord($mainWord, $tagName) {
		if (!is_string($mainWord)) {
			throw new \InvalidArgumentException('Method "' . __METHOD__ . '" accepts only strings values as $mainWord. Input was: ' . serialize($mainWord));
		}

		if (!is_string($tagName)) {
			throw new \InvalidArgumentException('Method "' . __METHOD__ . '" accepts only strings values as $tagName. Input was: ' . serialize($tagName));
		}

		return $this->storageBackend->getByMainWord($tagName, $mainWord);
	}

	/**
	 * @return SynonymCollection
	 */
	public function getAll() {
		return $this->storageBackend->getAll();
	}

	/**
	 * @param string $tagName
	 * @return SynonymCollection
	 */
	public function getAllByTagName($tagName) {
		if (!is_string($tagName)) {
			throw new InvalidArgumentException('Method "' . __METHOD__ . '" accepts only strings values as $tagName. Input was: ' . serialize($tagName));
		}

		return $this->storageBackend->getAllByTag($tagName);
	}

	/**
	 * Delete a synonym from the api.
	 *
	 * @param Synonym $synonym
	 */
	public function delete(Synonym $synonym) {
		return $this->deleteByMainWord($synonym->getMainWord(), $synonym->getTagName());
	}

	/**
	 * Deletes synonym by mainWord and tagName.
	 *
	 * @param string $mainWord
	 * @param string $tagName
	 */
	public function deleteByMainWord($mainWord, $tagName) {
		if (!is_string($mainWord)) {
			throw new InvalidArgumentException('Method "' . __METHOD__ . '" accepts only strings values as $mainWord. Input was: ' . serialize($mainWord));
		}

		if (!is_string($tagName)) {
			throw new InvalidArgumentException('Method "' . __METHOD__ . '" accepts only strings values as $tagName. Input was: ' . serialize($tagName));
		}

		return $this->storageBackend->deleteByMainWord($tagName, $mainWord);
	}
}