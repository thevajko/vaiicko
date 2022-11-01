<?php


namespace App\Core\Responses;

/**
 * Class Response
 * Abstract class for creating responses
 * @package App\Core\Responses
 */
abstract class Response
{
    /**
     * Method needed to implement
     * @return mixed
     */
    abstract public function generate();
}