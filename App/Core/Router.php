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
        try {
            $controller = new $fullControllerName();
        } catch (\Exception $e) {
            $controller = null;
        }
        $action = $this->getAction();

        if (!method_exists($controller, $action)) {
            http_response_code(404);
            echo '404 Not Found';
            die();
        }

        return ['controller' => $controller, 'action' => $action];
    }

    /**
     * Returns a controller instance of from an URL
     * @return AControllerBase
     */
    public function getFullControllerName(): string
    {
        $controllerName = empty(trim(@$_GET['c'])) ? "home" : trim($_GET['c']);
        return 'App\Controllers\\' . $controllerName . "Controller";

    }

    /**
     * Returns an action name from an URL
     * @return string
     */
    public function getAction(): string
    {
        return (empty(trim(@$_GET['a'])) ? "index" : $_GET['a']);
    }
}