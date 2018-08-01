<?php

namespace Searchperience\Api\Client\System\Storage;

use Searchperience\Api\Client\Domain\Stopword\Stopword;
use Searchperience\Api\Client\Domain\Stopword\StopwordCollection;

/**
 * Interface StopwordBackendInterface
 * @package Searchperience\Api\Client\System\Storage
 */
interface StopwordBackendInterface extends BackendInterface
{

    /**
     * @throws \Searchperience\Common\Http\Exception\InternalServerErrorException
     * @throws \Searchperience\Common\Http\Exception\ForbiddenException
     * @throws \Searchperience\Common\Http\Exception\ClientErrorResponseException
     * @throws \Searchperience\Common\Http\Exception\UnauthorizedException
     * @throws \Searchperience\Common\Http\Exception\MethodNotAllowedException
     * @throws \Searchperience\Common\Http\Exception\RequestEntityTooLargeException
     * @return mixed
     */
    public function getAll();

    /**
     * @param int $start
     * @param int $limit
     * @param \Searchperience\Api\Client\Domain\Filters\FilterCollection|null $filterCollection
     * @throws \Searchperience\Common\Http\Exception\InternalServerErrorException
     * @throws \Searchperience\Common\Http\Exception\ForbiddenException
     * @throws \Searchperience\Common\Http\Exception\ClientErrorResponseException
     * @throws \Searchperience\Common\Http\Exception\UnauthorizedException
     * @throws \Searchperience\Common\Http\Exception\MethodNotAllowedException
     * @throws \Searchperience\Common\Http\Exception\RequestEntityTooLargeException
     * @return mixed
     */
    public function getAllByFilterCollection($start, $limit, \Searchperience\Api\Client\Domain\Filters\FilterCollection $filterCollection = null);

    /**
     * @param string $id
     *
     * @throws \Searchperience\Common\Http\Exception\InternalServerErrorException
     * @throws \Searchperience\Common\Http\Exception\ForbiddenException
     * @throws \Searchperience\Common\Http\Exception\ClientErrorResponseException
     * @throws \Searchperience\Common\Http\Exception\UnauthorizedException
     * @throws \Searchperience\Common\Http\Exception\MethodNotAllowedException
     * @throws \Searchperience\Common\Http\Exception\RequestEntityTooLargeException
     * @return \Searchperience\Api\Client\Domain\Stopword\Stopword
     */
    public function getById($id);

    /**
     * @param Stopword $stopword
     * @throws \Searchperience\Common\Http\Exception\InternalServerErrorException
     * @throws \Searchperience\Common\Http\Exception\ForbiddenException
     * @throws \Searchperience\Common\Http\Exception\ClientErrorResponseException
     * @throws \Searchperience\Common\Http\Exception\UnauthorizedException
     * @throws \Searchperience\Common\Http\Exception\MethodNotAllowedException
     * @throws \Searchperience\Common\Http\Exception\RequestEntityTooLargeException
     * @return mixed
     */
    public function post(Stopword $stopword);

    /**
     * @throws \Searchperience\Common\Http\Exception\InternalServerErrorException
     * @throws \Searchperience\Common\Http\Exception\ForbiddenException
     * @throws \Searchperience\Common\Http\Exception\ClientErrorResponseException
     * @throws \Searchperience\Common\Http\Exception\UnauthorizedException
     * @throws \Searchperience\Common\Http\Exception\MethodNotAllowedException
     * @throws \Searchperience\Common\Http\Exception\RequestEntityTooLargeException
     * @return mixed
     */
    public function deleteAll();

    /**
     * @param Stopword $stopword
     * @throws \Searchperience\Common\Http\Exception\InternalServerErrorException
     * @throws \Searchperience\Common\Http\Exception\ForbiddenException
     * @throws \Searchperience\Common\Http\Exception\ClientErrorResponseException
     * @throws \Searchperience\Common\Http\Exception\UnauthorizedException
     * @throws \Searchperience\Common\Http\Exception\MethodNotAllowedException
     * @throws \Searchperience\Common\Http\Exception\RequestEntityTooLargeException
     * @return mixed
     */
    public function delete(Stopword $stopword);

    /**
     * @param int $id
     * @throws \Searchperience\Common\Http\Exception\InternalServerErrorException
     * @throws \Searchperience\Common\Http\Exception\ForbiddenException
     * @throws \Searchperience\Common\Http\Exception\ClientErrorResponseException
     * @throws \Searchperience\Common\Http\Exception\UnauthorizedException
     * @throws \Searchperience\Common\Http\Exception\MethodNotAllowedException
     * @throws \Searchperience\Common\Http\Exception\RequestEntityTooLargeException
     * @return mixed
     */
    public function deleteById($id);

    /**
     * @throws \Searchperience\Common\Http\Exception\InternalServerErrorException
     * @throws \Searchperience\Common\Http\Exception\ForbiddenException
     * @throws \Searchperience\Common\Http\Exception\ClientErrorResponseException
     * @throws \Searchperience\Common\Http\Exception\UnauthorizedException
     * @throws \Searchperience\Common\Http\Exception\MethodNotAllowedException
     * @throws \Searchperience\Common\Http\Exception\RequestEntityTooLargeException
     */
    public function pushAll();
}