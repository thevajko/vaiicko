<?php

namespace App;

use App\Config\Configuration;
use App\Core\ControllerContext;
use App\Core\DB\Connection;
use App\Core\Request;
use App\Core\Responses\RedirectResponse;
use App\Core\Responses\Response;
use App\Core\Router;

/**
 * Class App
 * Main Application class
 * @package App
 */
class App
{
    /**
     * @var Router
     */
    private $router;

    /**
     * App constructor
     */
    public function __construct()
    {
        $this->router = new Router();
    }

    /**
     * Runs the application
     * @throws \Exception
     */
    public function run()
    {
        ob_start();

        // Create request object from http request
        $request = new Request();

        // Create authentificator for request
        $auth = (defined('\\App\\Config\\Configuration::AUTH_CLASS')) ? new (Configuration::AUTH_CLASS)($request) : null;

        // route params from url
        $route = $this->router->processRequest($request);

        // create controller with context
        $context = new ControllerContext($request, $route, $auth);
        $controller = new ($route->getControllerClassName())();

        //inject context into Controller
        call_user_func([$controller, 'setContext'], $context);

        if (!$controller->authorize($route->getAction())) {
            if ($auth->isLogged() || !defined('\\App\\Config\\Configuration::LOGIN_URL')) {
                http_response_code(403);
                echo '<h1>403 Forbidden</h1>';
            } else {
                (new RedirectResponse(Configuration::LOGIN_URL))->generate();

            }
        } else {
            // call appropriate method of the controller class
            $response = call_user_func([$controller, $route->getAction()]);
            // return view to user
            if ($response instanceof Response) {
                $response->generate();
            } else {
                throw new \Exception("Action {$route->getControllerClassName()}::{$route->getAction()} didn't return an instance of Response.");
            }
        }

        // if SQL debugging in configuration is allowed, display all SQL queries
        if (Configuration::DEBUG_QUERY) {
            $queries = array_map(function ($q) {$lines = explode("\n", $q); return '<pre>' . (substr($lines[1], 0, 7) == 'Params:'? 'Sent '.$lines[0] : $lines[1]) .'</pre>';} , Connection::getQueryLog());
            echo implode(PHP_EOL . PHP_EOL, $queries);
        }
    }

    /**
     * @return Router
     */
    public function getRouter(): Router
    {
        return $this->router;
    }
}