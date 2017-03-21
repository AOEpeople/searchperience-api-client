<?php

namespace Searchperience\Api\Client\Domain\Command;

use Searchperience\Api\Client\Domain\Document\Document;

/**
 * Class ExecuteIndexerCommand
 * @package Searchperience\Api\Client\Domain\Command
 */
class ExecuteIndexerCommand extends AbstractCommand
{

    /**
     * @var string
     */
    protected $name = 'ExecuteIndexer';

    /**
     * @param $count
     */
    public function setCount($count)
    {
        $this->arguments['count'] = $count;
    }

    /**
     * @param $skipDocumentLinks
     */
    public function setSkipDocumentLinks($skipDocumentLinks)
    {
        $this->arguments['skipDocumentLinks'] = $skipDocumentLinks;
    }
}