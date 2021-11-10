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

    private bool $ajax = false;

    /**
     * Request constructor.
     */
    public function __construct()
    {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->request = $_REQUEST;
        $this->server = $_SERVER;

        $this->ajax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
    }

    /**
     * Is request AJAX?
     * @return bool
     */
    public function isAjax(): bool
    {
        return $this->ajax;
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
     * Return a value for given key from request (order: POST, GET)
     * @param $key
     * @return mixed|null
     */
    public function getValue($key)
    {
        if (isset($_POST[$key])) {
            return $_POST[$key];
        } else if (isset($_GET[$key])){
            return $_GET[$key];
        } else {
            return null;
        }
    }
}
