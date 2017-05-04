<?php

namespace Searchperience\Api\Client\Domain\Command;

use Searchperience\Api\Client\Domain\Document\Document;

/**
 * Class ExecuteReCreateSuggestionsCommand
 * @package Searchperience\Api\Client\Domain\Command
 */
class ExecuteReCreateSuggestionsCommand extends AbstractCommand
{

    /**
     * @var string
     */
    protected $name = 'ExecuteReCreateSuggestions';

    /**
     * @param $count
     */
    public function setCount($count)
    {
        $this->arguments['count'] = $count;
    }
}