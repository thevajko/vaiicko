<?php

namespace App\Core;

/**
 * Class Route
 * Represent concrete route to specific controller with action
 * @package App\Core
 */
class Route {
    private string $controller;
    private string $action;

    public function __construct(string $controller, string $action)
    {
        $this->controller = $controller;
        $this->action = $action;
    }

    public function getControllerClassName(): string
    {
        return 'App\Controllers\\' . $this->getController() . "Controller";
    }

    public function getController(): string
    {
        return $this->controller;
    }

    public function getAction(): string
    {
        return $this->action;
    }
}