<?php

namespace Searchperience\Api\Client\Domain\Command;

/**
 * Class MarkDocumentsForProcessingCommand
 * @package Searchperience\Api\Client\Domain\Command
 */
class ReQueueByUrlPatternCommand extends AbstractCommand
{

    /**
     * @var string
     */
    protected $name = 'ReQueueByUrlPattern';

    public function setUrlPattern($urlPattern)
    {
        $this->arguments['urlPattern'] = $urlPattern;
    }
}
