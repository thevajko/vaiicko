<?php

namespace Framework\Http\Responses;

use Framework\Http\HttpException;

/**
 * Class Response
 * Abstract base class for creating various types of HTTP responses.
 * @package App\Core\Responses
 */
abstract class Response
{
    // HTTP status code for the response, defaulting to 200 (OK)
    private int $statusCode = 200;

    /**
     * Sends the response to the client.
     *
     * This method sets the HTTP status code and triggers the generation
     * of the response content by calling the abstract `generate()` method.
     * @throws \Framework\Http\HttpException If any output has already been sent, indicating a misuse of output
     *                       in the controller or elsewhere before sending the response.
     */
    public function send(): void
    {
        // Check if output was echoed
        if (ob_get_contents()) {
            throw new HttpException(
                500,
                'It is not allowed to send any output (e.g. echo) from the controller.'
            );
        }
        http_response_code($this->statusCode);
        $this->generate();
    }

    /**
     * Sets the HTTP status code for the response.
     *
     * @return void Returns the current instance for method chaining
     */
    abstract protected function generate(): void;

    /**
     * Sets the HTTP status code for the response.
     *
     * @param int $statusCode The HTTP status code (e.g., 200, 404, 500)
     * @return $this Returns the current instance for method chaining
     */
    public function setStatusCode(int $statusCode): Response
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * Sets a cookie for the response.
     *
     * @param string $name The name of the cookie
     * @param mixed $value The value of the cookie
     * @param int $expires_or_options Expiration time as a Unix timestamp or an options array
     * @param string $path The path on the server where the cookie will be available
     * @param string $domain The (sub)domain that the cookie is available to
     * @param bool $secure Whether the cookie should be transmitted over a secure HTTPS connection
     * @param bool $httponly Whether the cookie should be accessible only through the HTTP protocol
     * @return bool Returns true if the cookie was successfully set, false otherwise
     *
     * @see https://www.php.net/manual/en/function.setcookie.php
     */
    public function setCookie(
        string $name,
        string $value = "",
        int $expires_or_options = 0,
        string $path = "",
        string $domain = "",
        bool $secure = false,
        bool $httponly = false
    ): bool {
        return setcookie($name, $value, $expires_or_options, $path, $domain, $secure, $httponly);
    }
}
