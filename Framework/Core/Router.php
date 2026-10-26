<?php

namespace Framework\Core;

use Framework\Http\HttpException;

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
    private string $controllerName;
    private string $action;

    /**
     * Processes the current URL to determine the controller and action to run. This method initializes the controller
     * instance based on the parsed controller name and sets the action to be executed.
     *
     * @return void
     */
    public function processURL(): void
    {
        // Allow only valid PHP identifiers, so user input never gets into class/method names
        foreach (['controller' => $this->getControllerName(), 'action' => $this->getAction()] as $type => $name) {
            if (!self::isValidName($name)) {
                throw self::notFound("Invalid {$type} name \"{$name}\" in the URL (allowed: letters, digits, _).");
            }
        }

        // The details are passed as a previous exception, so they are shown only if exception details are enabled
        $fullControllerName = $this->getFullControllerName();
        if (!\ClassLoader::classExists($fullControllerName)) {
            throw self::notFound("Controller class {$fullControllerName} was not found (expected in file "
                . \ClassLoader::getClassFile($fullControllerName) . ").");
        }
        $this->controller = new $fullControllerName();

        // The action must be an existing public method of the controller
        if (!is_callable([$this->controller, $this->getAction()])) {
            throw self::notFound("Action {$this->getAction()} was not found in controller {$fullControllerName}.");
        }

        $this->controllerName = $this->getControllerName();
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
        return 'App\Controllers\\' . $this->getControllerName() . "Controller";
    }

    /**
     * Retrieves the controller name from the URL parameters. If no controller is specified, it defaults to "Home".
     *
     * @return string The name of the controller.
     */
    public function getControllerName(): string
    {
        return (!isset($_GET['c']) || empty(trim(@$_GET['c']))) ? "Home" : trim(ucfirst($_GET['c']));
    }

    /**
     * Retrieves the action name from the URL parameters. If no action is specified, it defaults to "index".
     *
     * @return string The name of the action to be executed.
     */
    public function getAction(): string
    {
        return (!isset($_GET['a']) || empty(trim(@$_GET['a']))) ? "index" : $_GET['a'];
    }

    /**
     * Checks whether the name of the controller or the action from the URL is a valid identifier.
     *
     * @param string $name The name to check.
     * @return bool True if the name contains only letters, digits and underscores and does not start with a digit.
     */
    private static function isValidName(string $name): bool
    {
        return preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $name) === 1;
    }

    /**
     * Creates a 404 exception with the detailed reason for developers.
     *
     * @param string $detail Description of the problem (shown only if exception details are enabled).
     * @return HttpException The exception to throw.
     */
    private static function notFound(string $detail): HttpException
    {
        return new HttpException(404, null, new \Exception($detail));
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
}
