<?php

namespace Searchperience\Api\Client\System\Storage;

use Searchperience\Api\Client\Domain\Synonym\SynonymTagCollection;

/**
* Interface SynonymBackendInterface
* @package Searchperience\Api\Client\System\Storage
* @author: Timo Schmidt <timo.schmidt@aoe.com>
*/
interface SynonymTagBackendInterface extends BackendInterface {

	/**
	 * @throws \Searchperience\Common\Http\Exception\InternalServerErrorException
	 * @throws \Searchperience\Common\Http\Exception\ForbiddenException
	 * @throws \Searchperience\Common\Http\Exception\ClientErrorResponseException
	 * @throws \Searchperience\Common\Http\Exception\UnauthorizedException
	 * @throws \Searchperience\Common\Http\Exception\MethodNotAllowedException
	 * @throws \Searchperience\Common\Http\Exception\RequestEntityTooLargeException
	 * @return SynonymTagCollection
	 */
	public function getAll();
}