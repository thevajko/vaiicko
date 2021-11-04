<?php

namespace App\Core\Responses;

/**
 * Class Response
 * Abstract class for creating responses
 * @package App\Core\Responses
 */
abstract class Response
{
    abstract public function generate();
}