<?php

namespace App\Core;

/**
 * Class Router
 * Very simple router (controller and action name gets from an URL)
 * @package App\Core
 */
class Router
{
    public function processURL()
    {
        $fullControllerName = $this->getFullControllerName();
        $controller = new $fullControllerName();
        $action = $this->getAction();

        return ['controller' => $controller, 'action' => $action];
    }

    /**
     * Returns a controller instance of from an URL (Home controller as default)
     * @return string
     */
    public function getFullControllerName(): string
    {
        $controllerName = empty(trim(@$_GET['c'])) ? "Home" : trim($_GET['c']);
        return 'App\Controllers\\' . $controllerName . "Controller";

    }

    /**
     * Returns an action name from an URL (index action by default)
     * @return string
     */
    public function getAction(): string
    {
        return (empty(trim(@$_GET['a'])) ? "index" : $_GET['a']);
    }
}