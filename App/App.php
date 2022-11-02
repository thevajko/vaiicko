<?php

namespace App;

use App\Config\Configuration;
use App\Core\IAuthenticator;
use App\Core\DB\Connection;
use App\Core\Request;
use App\Core\Responses\RedirectResponse;
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
     * @var Request
     */
    private Request $request;

    private ?IAuthenticator $auth;

    /**
     * App constructor
     */
    public function __construct()
    {
        $this->router = new Router();
        $this->request = new Request();

        // Check if there is an authenticator
        if (defined('\\App\\Config\\Configuration::AUTH_CLASS')) {
            //$authClass = Configuration::AUTH_CLASS;
            $this->auth = new (Configuration::AUTH_CLASS)();
        } else {
            $this->auth = null;
        }
    }

    /**
     * Runs the application
     * @throws \Exception
     */
    public function run()
    {
        ob_start();

        // get a controller and action from URL
        $this->router->processURL();

        //create a Controller and inject App into it
        $controllerName = $this->router->getFullControllerName();
        $controller = new $controllerName($this);

        if ($controller->authorize($this->router->getAction())) {
            // call appropriate method of the controller class
            $response = call_user_func([$controller, $this->router->getAction()]);
            // return view to user
            $response->generate();
        } else {
            if ($this->auth->isLogged() or !defined('\\App\\Config\\Configuration::LOGIN_URL')) {
                http_response_code(403);
                echo '<h1>403 Forbidden</h1>';
            } else {
                (new RedirectResponse(Configuration::LOGIN_URL))->generate();

            }
        }

        // if DEBUG for SQL is set, show SQL queries to DB
        if (Configuration::DEBUG_QUERY) {
            $queries = array_map(function ($q) {
                $lines = explode("\n", $q);
                return '<pre>' . (substr($lines[1], 0, 7) == 'Params:' ? 'Sent ' . $lines[0] : $lines[1]) . '</pre>';
            }, Connection::getQueryLog());
            echo PHP_EOL . PHP_EOL . implode(PHP_EOL . PHP_EOL, $queries) . "\n\nTotal queries: " . count($queries);
        }
    }

    /**
     * @return Router
     */
    public function getRouter(): Router
    {
        return $this->router;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @return IAuthenticator|null
     */
    public function getAuth(): ?IAuthenticator
    {
        return $this->auth;
    }
}