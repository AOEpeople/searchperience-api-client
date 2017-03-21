<?php

namespace Searchperience\Api\Client\Domain\Command;

use Searchperience\Api\Client\Domain\Document\Document;

/**
 * Class ExecuteCrawlerCommand
 * @package Searchperience\Api\Client\Domain\Command
 */
class ExecuteCrawlerCommand extends AbstractCommand
{

    /**
     * @var string
     */
    protected $name = 'ExecuteCrawler';

    /**
     * @param $count
     */
    public function setCount($count)
    {
        $this->arguments['count'] = $count;
    }
}