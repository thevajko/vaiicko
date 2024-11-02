<?php

namespace Framework\Http;

/**
 * Class Request
 *
 * Represents an HTTP request. This class provides an interface for accessing various components of the request such as
 * query parameters, POST data, server variables, uploaded files, and cookies. It abstracts the access to global
 * PHP variables, allowing for easier and more organized handling of incoming requests in web applications.
 *
 * @package App\Core\Http
 */
class Request
{
    private array $get;      // Stores query parameters from the URL (e.g., ?key=value)
    private array $post;     // Stores data submitted through HTTP POST requests
    private array $server;   // Contains server and execution environment variables (e.g., headers, request method)
    private array $files;    // Stores uploaded files from the request
    private array $cookies;   // Stores cookies sent by the client

    /**
     * Request constructor
     *
     * Initializes the request properties with data from global PHP variables. This constructor populates the instance
     * properties with the current request's data immediately upon instantiation.
     */
    public function __construct()
    {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->server = $_SERVER;
        $this->files = $_FILES;
        $this->cookies = $_COOKIE;
    }

    /**
     * Determines if the request method is GET.
     *
     * @return bool True if the request method is GET, otherwise false.
     */
    public function isGet(): bool
    {
        return $this->server('REQUEST_METHOD') === 'GET';
    }

    /**
     * Determines if the request method is POST.
     *
     * @return bool True if the request method is POST, otherwise false.
     */
    public function isPost(): bool
    {
        return $this->server('REQUEST_METHOD') === 'POST';
    }

    /**
     * Checks if the request was made using AJAX.
     *
     * This method relies on the presence of the HTTP_X_REQUESTED_WITH header which is typically set to 'xmlhttprequest'
     * in AJAX calls.
     *
     * @return bool True if the request is an AJAX request, otherwise false.
     */
    public function isAjax(): bool
    {
        return $this->server('HTTP_X_REQUESTED_WITH') !== null &&
            strtolower($this->server('HTTP_X_REQUESTED_WITH')) == 'xmlhttprequest';
    }

    /**
     * Checks if the request body contains JSON data.
     *
     * @return bool True if the request body has a Content-Type of "application/json", otherwise false.
     */
    public function isJson(): bool
    {
        return $this->server('CONTENT_TYPE') === "application/json";
    }

    /**
     * Checks if the client requests a JSON formatted response.
     *
     * This method checks the 'Accept' header of the request for the value 'application/json'.
     *
     * @return bool True if the client expects a JSON response, otherwise false.
     */
    public function wantsJson(): bool
    {
        return $this->server('HTTP_ACCEPT') === "application/json";
    }

    /**
     * Reads and decodes the request body as a JSON object.
     *
     * @return mixed The decoded JSON object.
     * @throws \JsonException If the JSON cannot be decoded due to syntax errors.
     */
    public function json(): mixed
    {
        return json_decode(file_get_contents('php://input'), flags: JSON_THROW_ON_ERROR);
    }

    /**
     * Checks if the request contains a specific key in either POST or GET data.
     *
     * @param string $key The key to check for in the request data.
     * @return bool True if the key exists in either POST or GET data, otherwise false.
     */
    public function hasValue(string $key): bool
    {
        return isset($this->post[$key]) || isset($this->get[$key]);
    }

    /**
     * Retrieves a query parameter from the request.
     *
     * If a specific key is provided, it returns the corresponding value; if no key is specified, it returns all query
     * parameters as an array.
     *
     * @param string|null $key The key of the query parameter to retrieve, or null to retrieve all.
     * @return mixed The value of the specified parameter or an array of all parameters if key is null.
     */
    public function get(?string $key = null): mixed
    {
        return $key === null ? $this->get : ($this->get[$key] ?? null);
    }

    /**
     * Retrieves a POST parameter from the request.
     *
     * If a specific key is provided, it returns the corresponding value; if no key is specified, it returns all POST
     * parameters as an array.
     *
     * @param string|null $key The key of the POST parameter to retrieve, or null to retrieve all.
     * @return mixed The value of the specified parameter or an array of all parameters if key is null.
     */
    public function post(?string $key = null): mixed
    {
        return $key === null ? $this->post : ($this->post[$key] ?? null);
    }

    /**
     * Retrieves a SERVER variable from the request.
     *
     * If a specific key is provided, it returns the corresponding value; if no key is specified, it returns all server
     * variables as an array.
     *
     * @param string|null $key The key of the server variable to retrieve, or null to retrieve all.
     * @return mixed The value of the specified variable or an array of all variables if key is null.
     */
    public function server(?string $key = null): mixed
    {
        return $key === null ? $this->server : ($this->server[$key] ?? null);
    }

    /**
     * Retrieves uploaded files from the request.
     *
     * If a specific key is provided, it returns the corresponding UploadedFile object; if no key is specified,
     * it returns an array of all uploaded files.
     *
     * @param string|null $key The key of the file to retrieve, or null to retrieve all.
     * @return UploadedFile|UploadedFile[]|null The UploadedFile object or an array of files, or null if not found.
     */
    public function file(?string $key = null): UploadedFile|array|null
    {
        if ($key === null) {
            return array_map(fn($file) => new UploadedFile($file), $this->files);
        }
        return isset($this->files[$key]) ? new UploadedFile($this->files[$key]) : null;
    }

    /**
     * Retrieves the first value for a given key from the request data, checking POST parameters first, followed by GET
     * parameters.
     *
     * @param string $key The key to search for in the request data.
     * @return mixed|null The found value or null if not found.
     */
    public function value(string $key): mixed
    {
        return $this->post($key) ?? $this->get($key);
    }

    /**
     * Retrieves a cookie value from the request.
     *
     * If a specific key is provided, it returns the corresponding cookie value; if no key is specified, it returns all
     * cookies as an array.
     *
     * @param string|null $key The key of the cookie to retrieve, or null to retrieve all.
     * @return mixed The value of the specified cookie or an array of cookies if key is null.
     */
    public function cookie(?string $key = null): mixed
    {
        return $key === null ? $this->cookies : ($this->cookies[$key] ?? null);
    }

    /**
     * Constructs and returns the base URL of the current request.
     *
     * The base URL is derived from the PHP_SELF and HTTP_HOST server variables, determining the appropriate protocol
     * (HTTP or HTTPS) based on the server protocol.
     *
     * @return string The full base URL, including the scheme, host, and script path.
     */
    public function getBaseUrl(): string
    {
        $path = $this->server('PHP_SELF');
        $hostName = $this->server('HTTP_HOST');

        // Determine the request protocol (HTTP or HTTPS)
        $protocol = strtolower(substr($this->server('SERVER_PROTOCOL'), 0, 5)) === 'https' ?
            'https' : 'http';

        return $protocol . '://' . $hostName . $path;
    }
}
