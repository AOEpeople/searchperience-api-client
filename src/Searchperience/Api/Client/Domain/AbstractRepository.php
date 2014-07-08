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

/**
 * Class AbstractRepository
 * @package Searchperience\Api\Client\Domain
 */
abstract class AbstractRepository implements DecoratableEntityInterface {
	/**
	 * @var \Searchperience\Api\Client\System\Storage\AbstractRestBackend
	 */
	protected $storageBackend;

	/**
	 * @var \Symfony\Component\Validator\ValidatorInterface
	 */
	protected $validator;

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
	 * @param AbstractEntityCollection $collection
	 * @param string $type
	 * @return AbstractEntityCollection
	 * @throws InvalidArgumentException
	 */
	public function decorateAll(AbstractEntityCollection $collection, $type = 'Searchperience\Api\Client\Domain\AbstractEntityCollection') {

		if(! class_exists($type)) {
			throw new InvalidArgumentException(sprintf('type param must be an instance of Searchperience\Api\Client\Domain\AbstractEntityCollection. %s give', $type), 123456789124);
		}

		$newCollection = new $type();
		if (! is_subclass_of($type, 'Searchperience\Api\Client\Domain\AbstractEntityCollection')) {
			throw new InvalidArgumentException(sprintf('type param must be an instance of Searchperience\Api\Client\Domain\AbstractEntityCollection. %s give', $type), 123456789124);
		}

		$newCollection = new $type();
		$newCollection->setTotalCount($collection->getTotalCount());
		foreach ($collection as $entity) {
			$newCollection->append($this->decorate($entity));
		}
		return $newCollection;
	}

	/**
	 * @param AbstractEntity $entity
	 * @return AbstractEntity
	 */
	public function decorate(AbstractEntity $entity) {
		return $entity;
	}
}