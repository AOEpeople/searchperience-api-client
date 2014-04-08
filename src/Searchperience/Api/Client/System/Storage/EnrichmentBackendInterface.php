<?php

namespace Searchperience\Api\Client\System\Storage;

/**
 * Interface UrlqueueBackendInterface
 * @package Searchperience\Api\Client\System\Storage
 * @author: Timo Schmidt <timo.schmidt@aoe.com>
 */
interface EnrichmentBackendInterface {

	/**
	 * @param string $username
	 * @return void
	 */
	public function setUsername($username);

	/**
	 * @param string $password
	 * @return void
	 */
	public function setPassword($password);

	/**
	 * @param string $baseUrl http://api.searchperience.me/
	 * @return mixed
	 */
	public function setBaseUrl($baseUrl);

	/**
	 * @param \Searchperience\Api\Client\Domain\Enrichment\Enrichment $enrichment
	 *
	 * @throws \Searchperience\Common\Http\Exception\InternalServerErrorException
	 * @throws \Searchperience\Common\Http\Exception\ForbiddenException
	 * @throws \Searchperience\Common\Http\Exception\ClientErrorResponseException
	 * @throws \Searchperience\Common\Http\Exception\UnauthorizedException
	 * @throws \Searchperience\Common\Http\Exception\MethodNotAllowedException
	 * @throws \Searchperience\Common\Http\Exception\RequestEntityTooLargeException
	 * @return mixed
	 */
	public function post(\Searchperience\Api\Client\Domain\Enrichment\Enrichment $enrichment);

	/**
	 * @param string $id
	 *
	 * @throws \Searchperience\Common\Http\Exception\InternalServerErrorException
	 * @throws \Searchperience\Common\Http\Exception\ForbiddenException
	 * @throws \Searchperience\Common\Http\Exception\ClientErrorResponseException
	 * @throws \Searchperience\Common\Http\Exception\UnauthorizedException
	 * @throws \Searchperience\Common\Http\Exception\MethodNotAllowedException
	 * @throws \Searchperience\Common\Http\Exception\RequestEntityTooLargeException
	 * @return \Searchperience\Api\Client\Domain\Document\Enrichment
	 */
	public function getById($id);

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
	 * @return \Searchperience\Api\Client\Domain\Document\EnrichmentCollection
	 * @return mixed
	 */
	public function getAllByFilterCollection($start, $limit, \Searchperience\Api\Client\Domain\Filters\FilterCollection $filterCollection = null);
}
