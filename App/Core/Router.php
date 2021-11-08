<?php

namespace App\Core;

/**
 * Class Router
 * Very simple router (controller and action name gets from an URL)
 * @package App\Core
 */
class Router
{
    private $controller;
    private $controllerName;
    private $action;

    /**
     * Processes an URL and sets, which controller and action to run
     */
    public function processURL()
    {
        $fullControllerName = $this->getFullControllerName();
        $this->controller = new $fullControllerName();

        $this->controllerName = $this->getControllerName();

        $this->action = $this->getAction();
    }

    /**
     * Returns a full controller name (with their namespace path) from an URL (Home controller as default)
     * @return string
     */
    public function getFullControllerName(): string
    {
        return 'App\Controllers\\' . $this->getControllerName() . "Controller";
    }

    /**
     * Returns a controller name from an URL (Home controller action by default)
     * @return string
     */
    public function getControllerName() : string
    {
            return empty(trim(@$_GET['c'])) ? "Home" : trim(ucfirst(strtolower($_GET['c'])));
    }

    /**
     * Returns an action name from an URL (index action by default)
     * @return string
     */
    public function getAction(): string
    {
        return (empty(trim(@$_GET['a'])) ? "index" : $_GET['a']);
    }

    /**
     * Returns a controller instance of from an URL (Home controller as default)
     * @return mixed
     */
    public function getController()
    {
        return $this->controller;
    }
}