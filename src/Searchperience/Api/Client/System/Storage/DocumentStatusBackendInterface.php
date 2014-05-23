<?php

namespace Searchperience\Api\Client\System\Storage;

/**
 * Interface DocumentBackendInterface
 * @package Searchperience\Api\Client\System\Storage
 * @author: Timo Schmidt <timo.schmidt@aoe.com>
 */
interface DocumentStatusBackendInterface extends BackendInterface {

	/**
	 * @throws \Searchperience\Common\Http\Exception\InternalServerErrorException
	 * @throws \Searchperience\Common\Http\Exception\ForbiddenException
	 * @throws \Searchperience\Common\Http\Exception\ClientErrorResponseException
	 * @throws \Searchperience\Common\Http\Exception\UnauthorizedException
	 * @throws \Searchperience\Common\Http\Exception\MethodNotAllowedException
	 * @throws \Searchperience\Common\Http\Exception\RequestEntityTooLargeException
	 * @return DocumentStatus
	 */
	public function get();

}
