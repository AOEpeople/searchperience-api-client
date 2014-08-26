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
	 * @return mixed
	 */
	public function decorateAll(AbstractEntityCollection $collection);

	/**
	 * @param AbstractEntity $entity
	 * @return mixed
	 */
	public function checkTypeAndDecorate( $entity);
}