<?php

namespace Searchperience\Api\Client\System\Storage;

/**
 * Interface IndexerCommandLogBackendInterface
 * @package Searchperience\Api\Client\System\Storage
 * @author: Nikolay Diaur <nikolay.diaur@aoe.com>
 */
interface CommandLogBackendInterface extends BackendInterface {

    /**
     * @param string $processId
     *
     * @throws \Searchperience\Common\Http\Exception\InternalServerErrorException
     * @throws \Searchperience\Common\Http\Exception\ForbiddenException
     * @throws \Searchperience\Common\Http\Exception\ClientErrorResponseException
     * @throws \Searchperience\Common\Http\Exception\UnauthorizedException
     * @throws \Searchperience\Common\Http\Exception\MethodNotAllowedException
     * @throws \Searchperience\Common\Http\Exception\RequestEntityTooLargeException
     * @return \Searchperience\Api\Client\Domain\CommandLog\CommandLog
     */
    public function getByProcessId($processId);

	/**
	 * @param int $start
	 * @param int $limit
	 * @param \Searchperience\Api\Client\Domain\Filters\FilterCollection $filterCollection
	 * @throws \Searchperience\Common\Http\Exception\InternalServerErrorException
	 * @throws \Searchperience\Common\Http\Exception\ForbiddenException
	 * @throws \Searchperience\Common\Http\Exception\ClientErrorResponseException
	 * @throws \Searchperience\Common\Http\Exception\UnauthorizedException
	 * @throws \Searchperience\Common\Http\Exception\MethodNotAllowedException
	 * @throws \Searchperience\Common\Http\Exception\RequestEntityTooLargeException
	 * @return \Searchperience\Api\Client\Domain\CommandLog\CommandLogCollection
	 * @return mixed
	 */
	public function getAllByFilterCollection($start, $limit, \Searchperience\Api\Client\Domain\Filters\FilterCollection $filterCollection = null);
}