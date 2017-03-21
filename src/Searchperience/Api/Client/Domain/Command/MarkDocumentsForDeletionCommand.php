<?php

namespace Searchperience\Api\Client\Domain\Command;

/**
 * Class MarkDocumentsForDeletionCommand
 * @package Searchperience\Api\Client\Domain\Command
 */
class MarkDocumentsForDeletionCommand extends AbstractCommand
{

    /**
     * @var string
     */
    protected $name = 'MarkDocumentsForDeletion';

    public function setSource($source)
    {
        $this->arguments['source'] = $source;
    }

    public function setMimeType($mimeType)
    {
        $this->arguments['mimeType'] = $mimeType;
    }
}
