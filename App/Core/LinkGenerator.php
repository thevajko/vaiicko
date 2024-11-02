<?php

namespace App\Core;

use App\Core\Http\Request;
use Exception;

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
        array $parameters = [],
        bool $absolute = false,
        bool $appendParameters = false
    ): string {
        // If destination is an array, set parameters accordingly
        if (is_array($destination)) {
            if ($parameters != []) {
                $caller = debug_backtrace()[0];
                throw new Exception(
                    "Wrong parameters combination in url() call at {$caller['file']}:{$caller['line']}"
                );
            }

            $parameters = $destination;
            $destination = "{$this->router->getControllerName()}.{$this->router->getAction()}";
        }

        // If destination does not specify a controller, assume current controller
        if (!str_contains($destination, ".")) {
            $destination = "{$this->router->getControllerName()}.{$destination}";
        }

        // Split the destination into controller and action
        list($controller, $action) = explode(".", $destination);

        // Build query arguments
        $args = $appendParameters ? $this->request->getGet() : [];
        $args = ["c" => lcfirst($controller), "a" => $action != "index" ? $action : null] + $parameters + $args;

        // Determine the base URL
        $basePath = $absolute ? $this->request->getBaseUrl() : "";

        // Construct and return the final URL
        return $basePath . "?" . http_build_query($args);
    }
}
