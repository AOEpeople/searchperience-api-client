<?php

namespace Searchperience\Api\Client\Domain\Command;

use Searchperience\Api\Client\Domain\Command\AbstractCommand;
use Searchperience\Api\Client\System\Storage\AbstractRestBackend;
use Searchperience\Common\Exception\InvalidArgumentException;
use Symfony\Component\Validator\Validation;

/**
 * Class CommandExecutionService
 * @package Searchperience\Api\Client\Domain
 * @author: Timo Schmidt <timo.schmidt@aoe.com>
 */
class CommandExecutionService {

	/**
	 * @var \Searchperience\Api\Client\System\Storage\CommandBackendInterface
	 */
	protected $executionBackend;

	/**
	 * Injects the storage backend.
	 *
	 * @param \Searchperience\Api\Client\System\Storage\CommandBackendInterface $executionBackend
	 * @return void
	 */
	public function injectExecutionBackend(\Searchperience\Api\Client\System\Storage\CommandBackendInterface $executionBackend) {
		$this->executionBackend = $executionBackend;
	}

	/**
	 * @param AbstractCommand $command
	 * @return integer
	 */
	public function execute(AbstractCommand $command) {
		return $this->executionBackend->post($command);
	}
}
