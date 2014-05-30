<?php

namespace Searchperience\Api\Client\Domain\Stopword;

/**
 * Class StopwordRepository
 * @package Searchperience\Api\Client\Domain\Stopword
 */
class StopwordRepository {

	/**
	 * @var \Searchperience\Api\Client\System\Storage\StopwordBackendInterface
	 */
	protected $storageBackend;

	/**
	 * @var \Symfony\Component\Validator\ValidatorInterface
	 */
	protected $stopwordValidator;

	/**
	 * Injects the storage backend.
	 *
	 * @param \Searchperience\Api\Client\System\Storage\StopwordBackendInterface $storageBackend
	 * @return void
	 */
	public function injectStorageBackend(\Searchperience\Api\Client\System\Storage\StopwordBackendInterface $storageBackend) {
		$this->storageBackend = $storageBackend;
	}

	/**
	 * Injects the validation service
	 *
	 * @param \Symfony\Component\Validator\ValidatorInterface $stopwordValidator
	 * @return void
	 */
	public function injectValidator(\Symfony\Component\Validator\ValidatorInterface $stopwordValidator) {
		$this->stopwordValidator = $stopwordValidator;
	}

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

		return $this->storageBackend->getByWord($tagName, $word);
	}

	/**
	 * @return StopwordCollection
	 */
	public function getAll() {
		return $this->storageBackend->getAll();
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

		return $this->storageBackend->getAllByTag($tagName);
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