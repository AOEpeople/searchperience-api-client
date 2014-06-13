<?php

namespace Searchperience\Api\Client\System\Storage;

/**
 * Class RestCommandBackend
 * @package Searchperience\Api\Client\System\Storage
 */
class RestCommandBackend extends \Searchperience\Api\Client\System\Storage\AbstractRestBackend implements \Searchperience\Api\Client\System\Storage\CommandBackendInterface {

	/**
	 * @var string
	 */
	protected $endpoint = 'commands';

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
	 * @return integer
	 */
	public function post(\Searchperience\Api\Client\Domain\Command\AbstractCommand $command) {
		return $this->getPostResponseFromEndpoint($command);
	}


	/**
	 * Create an array containing only the available command property values.
	 *
	 * @param \Searchperience\Api\Client\Domain\Command\AbstractCommand $command
	 * @return array
	 */
	protected function buildRequestArray(\Searchperience\Api\Client\Domain\AbstractEntity $command) {
		$valueArray = array();

		/** @var $command \Searchperience\Api\Client\Domain\Command\AbstractCommand */
		$valueArray['name'] = $command->getName();

		$arguments = $command->getArguments();
		foreach($arguments as $argumentKey => $argumentValue) {
			$valueArray['arguments'][$argumentKey] = $argumentValue;
		}

		return $valueArray;
	}
}
