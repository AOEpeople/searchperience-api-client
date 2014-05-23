<?php

namespace Searchperience\Api\Client\System\Storage;

/**
 * @author Timo Schmidt <timo.schmidt@aoe.com>
 */
interface BackendInterface {

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

}
