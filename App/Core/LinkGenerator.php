<?php

namespace App\Core;

/**
 * Helper class to generate url links
 */
class LinkGenerator
{
    private Request $request;
    private Router $router;

    public function __construct(Request $request, Router $router)
    {
        $this->request = $request;
        $this->router = $router;
    }

    /**
     * Returns url to specific controller / action
     * Usage:
     * url("home.index)                                           //HomeController, action index
     * url("index")                                               //Current controller and index action
     * url("home.index", ["param1" => 1, "param2" => true])       //HomeController, action index
     * url("index", ["param1" => 1, "param2" => true])            //Current controller and index action
     * url(["param1" => 1, "param2" => true])                     //Current controller and current action with parameters
     * url("home.index", ["param1" => 1, "param2" => true], true) //Absolute url http://localhost/?c=aaa&b=ccc
     * @param string|array $destination [controller.]action
     * @param mixed $parameters
     * @param bool $absolute Returns url as absolute with domain name
     * @param bool $appendParameters Take current parameters from request and appends another parameters to it
     * @return string
     * @throws \Exception
     */
    public function url(
        string|array $destination,
        array $parameters = [],
        bool $absolute = false,
        bool $appendParameters = false
    ): string {
        if (is_array($destination)) {
            if ($parameters != []) {
                $caller = debug_backtrace()[0];
                throw new \Exception(
                    "Wrong parameters combination in url() call at {$caller['file']}:{$caller['line']}"
                );
            }

            $parameters = $destination;
            $destination = "{$this->router->getControllerName()}.{$this->router->getAction()}";
        }
        if (!str_contains($destination, ".")) {
            $destination = "{$this->router->getControllerName()}.{$destination}";
        }

        list($controller, $action) = explode(".", $destination);

        //Builds query args
        $args = $appendParameters ? $this->request->getGet() : [];
        $args = ["c" => lcfirst($controller), "a" => $action != "index" ? $action : null] + $parameters + $args;

        $basePath = $absolute ? $this->request->getBaseUrl() : "";

        return $basePath . "?" . http_build_query($args);
    }
}
