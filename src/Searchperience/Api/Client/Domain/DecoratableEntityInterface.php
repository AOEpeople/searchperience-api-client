<?php
/**
 * Created by IntelliJ IDEA.
 * User: pavelbogomolenko
 * Date: 07/07/14
 * Time: 15:15
 * To change this template use File | Settings | File Templates.
 */

namespace Searchperience\Api\Client\Domain;

/**
 * Class DecoratableEntityInterface
 * @package Searchperience\Api\Client\Domain
 */
interface DecoratableEntityInterface {
	/**
	 * @param AbstractEntityCollection $collection
	 * @param string $type
	 * @return mixed
	 */
	public function decorateAll(AbstractEntityCollection $collection, $type = 'AbstractEntityCollection');

	/**
	 * @param AbstractEntity $entity
	 * @return mixed
	 */
	public function decorate(AbstractEntity $entity);
}