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
    private array $request;
    private array $server;
    private array $files;

    /**
     * Request constructor
     */
    public function __construct()
    {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->request = $_REQUEST;
        $this->server = $_SERVER;
        $this->files = $_FILES;
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
     * Returns true if HTTP request has defined content type as 'application/json'
     * @return bool
     */
    public function isContentTypeJSON(): bool
    {
        return $_SERVER['CONTENT_TYPE'] == "application/json";
    }

    /**
     * Returns true if client in request demands JSON formatted response.
     * Only valid value in request headers is 'application/json'.
     * @return bool
     */
    public function clientRequestsJSON(): bool
    {
        return isset($_SERVER['HTTP_ACCEPT']) && $_SERVER['HTTP_ACCEPT'] == "application/json";
    }

    /**
     * Try to convert default input of PHP to JSON object. Returns null if there
     * is a parsing error
     * @return mixed
     * @throws \JsonException
     */
    public function getRawBodyJSON(): mixed
    {
        return json_decode(file_get_contents('php://input'), flags: JSON_THROW_ON_ERROR);
    }

    /**
     * Getter for GET variables
     * @return array
     */
    public function getGet(): array
    {
        return $this->get;
    }

    /**
     * Getter for POST variables
     * @return array
     */
    public function getPost(): array
    {
        return $this->post;
    }

    /**
     * Getter for both GET and POST variables
     * @return array
     */
    public function getRequest(): array
    {
        return $this->request;
    }

    /**
     * Getter for SERVER variables (set by web server)
     * @return array
     */
    public function getServer(): array
    {
        return $this->server;
    }

    /**
     * Getter for FILES variables
     * @return array
     */
    public function getFiles(): array
    {
        return $this->files;
    }

    /**
     * Return a value for given key from request (order: POST, GET)
     * @param $key
     * @return mixed|null
     */
    public function getValue($key)
    {
        if (isset($_POST[$key])) {
            return $_POST[$key];
        } elseif (isset($_GET[$key])) {
            return $_GET[$key];
        } else {
            return null;
        }
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
