<?php

namespace Searchperience\Api\Client\Domain\Command;

/**
 * Class MarkDocumentsForProcessingCommand
 * @package Searchperience\Api\Client\Domain\Command
 */
class MarkDocumentsForProcessingCommand extends AbstractCommand
{

    /**
     * @var string
     */
    protected $name = 'MarkDocumentsForProcessing';

    public function setSource($source)
    {
        $this->arguments['source'] = $source;
    }

    public function setMimeType($mimeType) {
        $this->arguments['mimeType'] = $mimeType;
    }
}
