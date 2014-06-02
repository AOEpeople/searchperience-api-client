<?php

namespace Searchperience\Api\Client\System\Storage;

use Searchperience\Api\Client\Domain\Stopword\StopwordTagCollection;

/**
 * Interface StopwordBackendInterface
 * @package Searchperience\Api\Client\System\Storage
 */
interface StopwordTagBackendInterface extends BackendInterface {

	/**
	 * @throws \Searchperience\Common\Http\Exception\InternalServerErrorException
	 * @throws \Searchperience\Common\Http\Exception\ForbiddenException
	 * @throws \Searchperience\Common\Http\Exception\ClientErrorResponseException
	 * @throws \Searchperience\Common\Http\Exception\UnauthorizedException
	 * @throws \Searchperience\Common\Http\Exception\MethodNotAllowedException
	 * @throws \Searchperience\Common\Http\Exception\RequestEntityTooLargeException
	 * @return StopwordTagCollection
	 */
	public function getAll();
}