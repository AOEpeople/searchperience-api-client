<?php

namespace Searchperience\Api\Client\Domain\Command;
use Searchperience\Api\Client\Domain\AbstractEntity;

/**
 * Class AbstractCommand
 * @package Searchperience\Api\Client\Domain\Command
 * @author Timo Schmidt <timo.schmidt@aoe.com>
 */
abstract class AbstractCommand extends AbstractEntity{

	/**
	 * @var string
	 */
	protected $name = '';

	/**
	 * @var array
	 */
	protected $arguments = array();

	/**
	 * @return array
	 */
	public function getArguments() {
		return $this->arguments;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}
}