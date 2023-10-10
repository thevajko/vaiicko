<?php


namespace App\Core\Responses;

/**
 * Class Response
 * Abstract class for creating responses
 * @package App\Core\Responses
 */
abstract class Response
{
    private int $statusCode = 200;


    //TODO lepsi nazov
    public function generateWholeResponse() {
        http_response_code($this->statusCode);
        $this->generate();
    }

    /**
     * Method needed to implement
     * @return mixed
     */
    abstract protected function generate();

    //TODO premysliet ci nieje lepsi nazov withStatusCode
    public function setStatusCode(int $statusCode): Response
    {
        $this->statusCode = $statusCode;
        return $this;
    }
}