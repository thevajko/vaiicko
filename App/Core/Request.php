<?php

namespace App\Core;

/**
 * Class Request
 * Object request wrapping HTTP request
 * @package App\Core
 */
class Request
{
    private array $get;
    private array $post;
    private array $server;
    private array $files;

    /**
     * Request constructor
     */
    public function __construct()
    {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->server = $_SERVER;
        $this->files = $_FILES;
    }

    /**
     * Is request method GET
     * @return bool
     */
    public function isGet(): bool
    {
        return $this->server('REQUEST_METHOD') == 'GET';
    }

    /**
     * Is request method POST
     * @return bool
     */
    public function isPost(): bool
    {
        return $this->server('REQUEST_METHOD') == 'POST';
    }

    /**
     * Is request AJAX?
     * This method works only, if you set the HTTP_X_REQUESTED_WITH header set to xmlhttprequest in JS ajax call
     * @return bool
     */
    public function isAjax(): bool
    {
        return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
    }

    /**
     * Returns true if HTTP request has json content
     * @return bool
     */
    public function isJson(): bool
    {
        return isset($_SERVER['CONTENT_TYPE']) && $_SERVER['CONTENT_TYPE'] == "application/json";
    }

    /**
     * Returns true if client in request demands JSON formatted response.
     * Only valid value in request headers is 'application/json'.
     * @return bool
     */
    public function wantsJson(): bool
    {
        return isset($_SERVER['HTTP_ACCEPT']) && $_SERVER['HTTP_ACCEPT'] == "application/json";
    }

    /**
     * Reads request body as json object
     * @return mixed
     * @throws \JsonException
     */
    public function json(): mixed
    {
        return json_decode(file_get_contents('php://input'), flags: JSON_THROW_ON_ERROR);
    }

    /**
     * Check if request contains value
     * @param string $key
     * @return bool
     */
    public function hasValue(string $key): bool
    {
        return isset($this->post[$key]) || isset($this->get[$key]);
    }

    /**
     * Returns query parameter from request, if key not specified, returns array with all parameters
     * @param string|null $key
     * @return mixed
     */
    public function get(?string $key = null): mixed
    {
        if ($key == null) {
            return $this->get;
        }
        return (isset($this->get[$key])) ? $this->get[$key] : null;
    }

    /**
     * Returns post parameter from request, if key not specified, returns array with all parameters
     * @param string|null $key
     * @return mixed
     */
    public function post(?string $key = null): mixed
    {
        if ($key == null) {
            return $this->post;
        }
        return (isset($this->post[$key])) ? $this->post[$key] : null;
    }

    /**
     * Returns SERVER (set by web server) variable from request, if key not specified, returns array with all variables
     * @param string|null $key
     * @return array
     */
    public function server(?string $key = null): mixed
    {
        if ($key == null) {
            return $this->server;
        }
        return (isset($this->server[$key])) ? $this->server[$key] : null;
    }

    /**
     * Returns FILE from request, if key not specified, returns array with all files
     * @param string|null $key
     * @return mixed
     */
    public function file(?string $key = null): mixed
    {
        if ($key == null) {
            return $this->files;
        }
        return (isset($this->files[$key])) ? $this->files[$key] : null;
    }

    /**
     * Return a value for given key from request (order: POST, GET)
     * @param string $key
     * @return mixed|null
     */
    public function value(string $key): mixed
    {
        return $this->post($key) ?? $this->get($key);
    }

    /**
     * Returns base url of this request
     * http://localhost/myproject/
     * @return string
     */
    public function getBaseUrl(): string
    {
        $path = $_SERVER['PHP_SELF'];
        $hostName = $_SERVER['HTTP_HOST'];

        //Gets prorocol
        $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"], 0, 5)) == 'https' ? 'https' : 'http';

        return $protocol . '://' . $hostName . $path;
    }
}
