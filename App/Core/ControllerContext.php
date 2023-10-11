<?php

namespace App\Core;

use App\Core;

class ControllerContext
{
    private Request $request;
    private Route $route;
    private IAuthenticator $auth;

    /**
     * @param Request $request
     * @param Core\Route $route
     * @param mixed|null $auth
     */
    public function __construct(\App\Core\Request $request, Route $route, IAuthenticator $auth)
    {
        $this->request = $request;
        $this->route = $route;
        $this->auth = $auth;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getRoute(): Route
    {
        return $this->route;
    }

    public function getAuth(): IAuthenticator
    {
        return $this->auth;
    }

}