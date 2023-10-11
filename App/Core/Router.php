<?php

namespace App\Core;

/**
 * Class Router
 * Very simple router (controller and action name gets from an URL)
 * @package App\Core
 */
class Router
{
    /**
     * Processes a URL and sets, which controller and action to run
     */
    public function processRequest(Request $request) : Route
    {
        return new Route($this->getControllerName($request), $this->getAction($request));
    }

    /**
     * Returns a controller name from a URL (Home controller action by default)
     * @return string
     */
    private function getControllerName(Request $request) : string
    {
        return (!isset($request->getGet()['c']) || empty(trim(@$request->getGet()['c']))) ? "Home" : trim(ucfirst(strtolower($request->getGet()['c'])));
    }

    /**
     * Returns an action name from a URL (index action by default)
     * @return string
     */
    private function getAction(Request $request): string
    {
        return (!isset($request->getGet()['a']) || empty(trim(@$request->getGet()['a'])) ? "index" : $request->getGet()['a']);
    }
}
