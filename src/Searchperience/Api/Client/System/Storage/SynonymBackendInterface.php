<?php

namespace Searchperience\Api\Client\System\Storage;

use Searchperience\Api\Client\Domain\Synonym\Synonym;
use Searchperience\Api\Client\Domain\Synonym\SynonymCollection;

/**
* Interface SynonymBackendInterface
* @package Searchperience\Api\Client\System\Storage
* @author: Timo Schmidt <timo.schmidt@aoe.com>
*/
interface SynonymBackendInterface extends BackendInterface {

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
	 * @param string $tagName
	 * @throws \Searchperience\Common\Http\Exception\InternalServerErrorException
	 * @throws \Searchperience\Common\Http\Exception\ForbiddenException
	 * @throws \Searchperience\Common\Http\Exception\ClientErrorResponseException
	 * @throws \Searchperience\Common\Http\Exception\UnauthorizedException
	 * @throws \Searchperience\Common\Http\Exception\MethodNotAllowedException
	 * @throws \Searchperience\Common\Http\Exception\RequestEntityTooLargeException
	 * @return mixed
	 */
	public function getAllByTag($tagName);

	/**
	 * @param string $tagName
	 * @param string $mainWord
 	 * @throws \Searchperience\Common\Http\Exception\InternalServerErrorException
	 * @throws \Searchperience\Common\Http\Exception\ForbiddenException
	 * @throws \Searchperience\Common\Http\Exception\ClientErrorResponseException
	 * @throws \Searchperience\Common\Http\Exception\UnauthorizedException
	 * @throws \Searchperience\Common\Http\Exception\MethodNotAllowedException
	 * @throws \Searchperience\Common\Http\Exception\RequestEntityTooLargeException
	 * @return \Searchperience\Api\Client\Domain\Synonym\Synonym
	 */
	public function getByMainWord($tagName, $mainWord);

	/**
	 * @param string $tagName
	 * @param Synonym $synonym
	 * @throws \Searchperience\Common\Http\Exception\InternalServerErrorException
	 * @throws \Searchperience\Common\Http\Exception\ForbiddenException
	 * @throws \Searchperience\Common\Http\Exception\ClientErrorResponseException
	 * @throws \Searchperience\Common\Http\Exception\UnauthorizedException
	 * @throws \Searchperience\Common\Http\Exception\MethodNotAllowedException
	 * @throws \Searchperience\Common\Http\Exception\RequestEntityTooLargeException
	 * @return mixed
	 */
	public function post($tagName, Synonym $synonym);

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
	 * @param string $tagName
	 * @param Synonym $synonym
	 * @throws \Searchperience\Common\Http\Exception\InternalServerErrorException
	 * @throws \Searchperience\Common\Http\Exception\ForbiddenException
	 * @throws \Searchperience\Common\Http\Exception\ClientErrorResponseException
	 * @throws \Searchperience\Common\Http\Exception\UnauthorizedException
	 * @throws \Searchperience\Common\Http\Exception\MethodNotAllowedException
	 * @throws \Searchperience\Common\Http\Exception\RequestEntityTooLargeException
	 * @return mixed
	 */
	public function delete($tagName, Synonym $synonym);

	/**
	 * @param string $tagName
	 * @param string $mainWord
	 * @throws \Searchperience\Common\Http\Exception\InternalServerErrorException
	 * @throws \Searchperience\Common\Http\Exception\ForbiddenException
	 * @throws \Searchperience\Common\Http\Exception\ClientErrorResponseException
	 * @throws \Searchperience\Common\Http\Exception\UnauthorizedException
	 * @throws \Searchperience\Common\Http\Exception\MethodNotAllowedException
	 * @throws \Searchperience\Common\Http\Exception\RequestEntityTooLargeException
	 * @return mixed
	 */
	public function deleteByMainWord($tagName, $mainWord);
}