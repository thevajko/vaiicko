<?php

namespace Framework\Core;

/**
 * Class Router
 *
 * The Router class is responsible for handling URL routing in the application. It processes incoming requests,
 * determines the appropriate controller and action to invoke, and creates an instance of the specified controller.
 * By default, it routes to a "Home" controller and "index" action when no specific controller or action is provided
 * in the URL.
 *
 * @package App\Core
 */
class Router
{
    private object $controller;

    /**
     * Processes the current URL to determine the controller and action to run. This method initializes the controller
     * instance based on the parsed controller name and sets the action to be executed.
     *
     * @return void
     */
    public function processURL(): void
    {
        $fullControllerName = $this->getFullControllerName();
        $this->controller = new $fullControllerName();
    }

    /**
     * Constructs and returns the fully qualified name of the controller by appending the namespace to the controller
     * name obtained from the URL. Defaults to the "HomeController" if no controller is specified in the URL.
     *
     * @return string The full controller name including namespace.
     */
    public function getFullControllerName(): string
    {
        $segments = $this->parseControllerSegments();
        return 'App\\Controllers\\' . implode('\\', $segments) . 'Controller';
    }

    /**
     * Retrieves the controller name from the URL parameters. If no controller is specified, it defaults to "Home".
     *
     * @return string The name of the controller.
     */
    public function getControllerName(): string
    {
        $segments = $this->parseControllerSegments();
        return end($segments);
    }

    /**
     * Retrieves the action name from the URL parameters. If no action is specified, it defaults to "index".
     *
     * @return string The name of the action to be executed.
     */
    public function getAction(): string
    {
        $requested = trim((string)($_GET['a'] ?? ''));
        $requested = $requested === '' ? 'index' : $requested;

        return $this->resolveActionName($requested);
    }

    /**
     * Returns the instance of the controller determined from the URL. This instance can be used to invoke the
     * specified action.
     *
     * @return object The instantiated controller object.
     */
    public function getController(): object
    {
        return $this->controller;
    }

    /**
     * Parses the controller segments from the URL parameter 'c'. It splits the parameter by '/' and formats each
     * segment to follow the PascalCase naming convention. If no segments are provided, it defaults to ['Home'].
     *
     * @return array An array of formatted controller segments.
     */
    private function parseControllerSegments(): array
    {
        $raw = trim($_GET['c'] ?? '');

        if ($raw === '') {
            return ['Home'];
        }

        $parts = array_values(array_filter(explode('/', $raw), static fn($part) => $part !== ''));

        if (empty($parts)) {
            return ['Home'];
        }

        return array_map(
            static fn($part) => str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', strtolower($part)))),
            $parts
        );
    }

    /**
     * Resolves the action name to be case-insensitive by matching against the instantiated controller’s methods.
     *
     * @param string $action The requested action name.
     * @return string The resolved action name, matching the case of the controller's method.
     */
    private function resolveActionName(string $action): string
    {
        if (!isset($this->controller)) {
            return $action;
        }

        foreach (get_class_methods($this->controller) as $method) {
            if (strcasecmp($method, $action) === 0) {
                return $method;
            }
        }
        return $action;
    }
}
