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
     * Process URL to get controller and action
     */
    public function processURL()
    {
        $this->controllerName = $this->getControllerName();
        $this->action = $this->getAction();
    }

    /**
     * Return a controller instance of from a URL (Home controller as default)
     * @return string
     */
    public function getFullControllerName(): string
    {
        return 'App\Controllers\\' . $this->getControllerName() . "Controller";
    }

    /**
     * Return a controller name from a URL (home controller action by default)
     * @return string
     */
    public function getControllerName() : string
    {
            return empty(trim(@$_GET['c'])) ? "Home" : trim($_GET['c']);
    }

    /**
     * Return an action name from a URL (index action by default)
     * @return string
     */
    public function getAction(): string
    {
        return (empty(trim(@$_GET['a'])) ? "index" : $_GET['a']);
    }
}