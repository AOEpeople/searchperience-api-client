<?php

namespace Searchperience\Api\Client\System\Storage;

/**
 * Interface UrlqueueBackendInterface
 * @package Searchperience\Api\Client\System\Storage
 * @author: Timo Schmidt <timo.schmidt@aoe.com>
 */
interface UrlQueueStatusBackendInterface extends BackendInterface {

	/**
	 * @throws \Searchperience\Common\Http\Exception\InternalServerErrorException
	 * @throws \Searchperience\Common\Http\Exception\ForbiddenException
	 * @throws \Searchperience\Common\Http\Exception\ClientErrorResponseException
	 * @throws \Searchperience\Common\Http\Exception\UnauthorizedException
	 * @throws \Searchperience\Common\Http\Exception\MethodNotAllowedException
	 * @throws \Searchperience\Common\Http\Exception\RequestEntityTooLargeException
	 * @return mixed
	 */
	public function get();

}
