<?php

namespace Framework\Support;

use Exception;
use Framework\Core\Router;
use Framework\Http\Request;

/**
 * Class LinkGenerator
 *
 * A helper class that facilitates the generation of URL links for different controllers and actions within
 * the application. This class leverages the current request context to create URLs that are appropriate for the current
 * routing setup.
 *
 * The LinkGenerator can construct both relative and absolute URLs and allows for the inclusion of parameters
 * in the generated URLs.
 *
 * @package App\Core
 */
class LinkGenerator
{
    private Request $request;
    private Router $router;

    /**
     * LinkGenerator constructor.
     *
     * Initializes the LinkGenerator with the current Request and Router instances.
     *
     * @param Request $request The current HTTP request object, used to retrieve base URL and query parameters.
     * @param Router $router The router instance, used to resolve controller and action names.
     */
    public function __construct(Request $request, Router $router)
    {
        $this->request = $request;
        $this->router = $router;
    }

    /**
     * Generates a URL to a specific controller/action.
     *
     * This method allows the creation of URLs based on the application's routing configuration. The destination can be
     * specified as a string in the format "controller.action" or as an associative array of parameters. The method can
     * also return either a relative or absolute URL depending on the provided parameters.
     *
     * Examples:
     * - url("home.index")                                    // URL for HomeController's index action
     * - url("api.messages.index")                            // URL for Api\MessagesController's index action
     * - url("index")                                         // URL for the current controller's index action
     * - url("home.index", ["param1" => 1, "param2" => true]) // URL with parameters for HomeController's index action
     * - url("index", ["param1" => 1, "param2" => true])      // URL with parameters for the current controller and
     *                                                           index action
     * - url(["param1" => 1, "param2" => true])               // URL for current controller and action with parameters
     * - url("home.index", ["param1" => 1], true)             // Absolute url HomeController's index action
     *                                                           with parameters
     *
     * @param string|array $destination The controller and action (format: "controller.action") or an associative array
     *                                  of parameters.
     * @param array $parameters Optional parameters to include in the URL.
     * @param bool $absolute If true, returns the URL as an absolute URL (including the domain name).
     * @param bool $appendParameters If true, appends current request GET parameters to the generated URL.
     * @return string The generated URL.
     * @throws Exception If the parameters are incorrectly combined.
     */
    public function url(
        string|array $destination,
        array        $parameters = [],
        bool         $absolute = false,
        bool         $appendParameters = false
    ): string
    {
        $currentControllerPath = implode('.', $this->router->getControllerSegments());

        if (is_array($destination)) {
            if ($parameters != []) {
                $caller = debug_backtrace()[0];
                throw new Exception(
                    "Wrong parameters combination in url() call at {$caller['file']}:{$caller['line']}"
                );
            }

            $parameters = $destination;
            $destination = $currentControllerPath . '.' . $this->router->getAction();
        }

        if (!str_contains($destination, ".")) {
            $destination = $currentControllerPath . '.' . $destination;
        }

        $parts = array_values(array_filter(explode('.', $destination), static fn($part) => $part !== ''));
        if ($parts === []) {
            $parts = $this->router->getControllerSegments();
        }

        if (count($parts) === 1) {
            $parts = array_merge($this->router->getControllerSegments(), $parts);
        }

        $action = array_pop($parts);
        $controllerSegments = $parts ?: $this->router->getControllerSegments();
        $controller = $this->buildControllerQueryValue($controllerSegments);

        // Build query arguments
        $args = $appendParameters ? $this->request->get() : [];
        $args = ["c" => $controller, "a" => $action != "index" ? $action : null] + $parameters + $args;

        // Determine the base URL
        $basePath = $absolute ? $this->request->getBaseUrl() : "";

        // Construct and return the final URL
        return $basePath . "?" . http_build_query($args);
    }

    /**
     * Builds the controller part of the query string from the given segments.
     *
     * This method normalizes the controller segments by trimming whitespace and converting
     * PascalCase to kebab-case (e.g., UserPhoto becomes user-photo). It then joins the
     * segments with slashes to form the controller path used in the URL.
     *
     * @param array $segments The segments representing the controller path.
     * @return string The normalized controller path for the query string.
     */
    private function buildControllerQueryValue(array $segments): string
    {
        $normalized = [];
        foreach ($segments as $segment) {
            $segment = trim((string)$segment);
            if ($segment === '') {
                continue;
            }
            $normalized[] = $this->toKebabCase($segment);
        }
        if (empty($normalized)) {
            return 'home';
        }
        return implode('/', $normalized);
    }

    /**
     * Converts a PascalCase or camelCase string to kebab-case.
     *
     * @param string $value The input string.
     * @return string The kebab-case version of the string.
     */
    private function toKebabCase(string $value): string
    {
        // Insert hyphen before uppercase letters, then lowercase the whole string
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', $value));
    }

    /**
     * Generates a URL to a static asset under the public web root.
     *
     * Examples:
     * - asset('css/styl.css')               // relative to app base path
     * - asset('/css/styl.css')              // leading slash is normalized
     * - asset('favicons/favicon-32x32.png') // favicon path
     * - asset('js/app.js', true)            // absolute URL with scheme and host
     */
    public function asset(string $path, bool $absolute = false): string
    {
        $scriptPath = $this->request->server('PHP_SELF') ?? '/';
        $dir = rtrim(str_replace('\\', '/', dirname($scriptPath)), '/');
        $assetPath = '/' . ltrim($path, '/');

        if ($absolute) {
            $serverProtocol = strtolower($this->request->server('SERVER_PROTOCOL') ?? 'http');
            $isHttps = str_starts_with($serverProtocol, 'https')
                || ($this->request->server('HTTPS') ?? '') === 'on'
                || ($this->request->server('REQUEST_SCHEME') ?? '') === 'https';
            $protocol = $isHttps ? 'https' : 'http';
            $host = $this->request->server('HTTP_HOST') ?? '';
            return $protocol . '://' . $host . $dir . $assetPath;
        }

        return ($dir ?: '') . $assetPath;
    }
}
