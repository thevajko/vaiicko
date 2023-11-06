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

    /**
     * Sends response with headers to client
     */
    public function send(): void
    {
        http_response_code($this->statusCode);
        $this->generate();
    }

    /**
     * Method needed to be implemented
     */
    abstract protected function generate(): void;

    /**
     * Set HTTP Status code of response
     * @param int $statusCode
     * @return $this
     */
    public function setStatusCode(int $statusCode): Response
    {
        $this->statusCode = $statusCode;
        return $this;
    }


    /**
     * Set response cookies @see setcookie()
     * @param string $name
     * @param $value
     * @param $expires_or_options
     * @param $path
     * @param $domain
     * @param $secure
     * @param $httponly
     * @return bool
     */
    public function setCookie(
        string $name,
        $value = "",
        $expires_or_options = 0,
        $path = "",
        $domain = "",
        $secure = false,
        $httponly = false): bool
    {
        return setcookie($name, $value, $expires_or_options, $path, $domain, $secure, $httponly);
    }
}
