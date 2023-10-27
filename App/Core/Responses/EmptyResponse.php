<?php

namespace App\Core\Responses;

/**
 * Class EmptyResponse
 * Empty response with no content
 * @package App\Core\Responses
 */
class EmptyResponse extends Response
{
    public function __construct()
    {
        $this->setStatusCode(204);
    }


    protected function generate(): void
    {
    }
}
