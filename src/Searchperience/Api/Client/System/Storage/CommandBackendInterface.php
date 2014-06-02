<?php

namespace Searchperience\Api\Client\System\Storage;

/**
 *
 * @author Timo Schmidt <timo.schmidt@aoe.com>
 */
interface CommandBackendInterface extends BackendInterface {

	/**
	 * @param \Searchperience\Api\Client\Domain\Command\AbstractCommand $command
	 *
	 * @throws \Searchperience\Common\Http\Exception\InternalServerErrorException
	 * @throws \Searchperience\Common\Http\Exception\ForbiddenException
	 * @throws \Searchperience\Common\Http\Exception\ClientErrorResponseException
	 * @throws \Searchperience\Common\Http\Exception\DocumentNotFoundException
	 * @throws \Searchperience\Common\Http\Exception\UnauthorizedException
	 * @throws \Searchperience\Common\Http\Exception\MethodNotAllowedException
	 * @throws \Searchperience\Common\Http\Exception\RequestEntityTooLargeException
	 * @return mixed
	 */
	public function post(\Searchperience\Api\Client\Domain\Command\AbstractCommand $command);

}