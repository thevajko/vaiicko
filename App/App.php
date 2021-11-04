<?php

namespace App;

use App\Core\DB\Connection;
use App\Core\Request;
use App\Core\Router;
use App\Config\Configuration;

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

    /**
     * App constructor
     */
    public function __construct()
    {
        $this->router = new Router();
        $this->request = new Request();
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

        // call appropriate method of the controller class
        $response =  call_user_func([$this->router->getController(), $this->router->getAction()]);

        $response->generate();

        // if SQL debugging in configuration is allowed, display all SQL queries
        if (Configuration::DEBUG_QUERY) {
            $queries = array_map(function ($q) {$lines = explode("\n", $q); return '<pre>' . (substr($lines[1], 0, 7) == 'Params:'? 'Sent '.$lines[0] : $lines[1]) .'</pre>';} , Connection::getQueryLog());
            echo implode(PHP_EOL . PHP_EOL, $queries);
        }
    }

    /**
     * Getter for router instance
     * @return Router
     */
    public function getRouter(): Router
    {
        return $this->router;
    }

    /**
     * Getter for Request instance
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

}