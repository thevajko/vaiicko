<?php

namespace App;

use App\Config\Configuration;
use App\Core\IAuthenticator;
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

        //inject app into Controller
        call_user_func([$this->router->getController(), 'setApp'], $this);


        if ($this->router->getController()->authorize($this->router->getAction())) {
            // call appropriate method of the controller class
            $response = call_user_func([$this->router->getController(), $this->router->getAction()]);
            // return view to user
            if ($response instanceof Response) {
                $response->generate();
            } else {
                throw new \Exception("Action {$this->router->getFullControllerName()}::{$this->router->getAction()} didn't return an instance of Response.");
            }
        } else {
            if ($this->auth->isLogged() || !defined('\\App\\Config\\Configuration::LOGIN_URL')) {
                http_response_code(403);
                echo '<h1>403 Forbidden</h1>';
            } else {
                (new RedirectResponse(Configuration::LOGIN_URL))->generate();

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