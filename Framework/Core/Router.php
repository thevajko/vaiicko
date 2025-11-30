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
    private string $action;

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

        $this->action = $this->getAction();
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
        if (isset($this->action)) {
            return $this->action;
        }

        $requested = trim((string)($_GET['a'] ?? ''));
        $requested = $requested === '' ? 'index' : $requested;

        $this->action = $this->resolveActionName($requested);
        return $this->action;
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
     * Exposes the controller path segments for use when constructing default view paths.
     *
     * @return string The controller path segments.
     */
    public function getControllerViewPath(): string
    {
        return implode(DIRECTORY_SEPARATOR, $this->parseControllerSegments());
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
