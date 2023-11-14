<?php

namespace App\Controllers;

use App\Core\ControllerBase;
use App\Core\Http\Responses\Response;

/**
 * Class HomeController
 * Example class of a controller
 * @package App\Controllers
 */
class AdminController extends ControllerBase
{
    /**
     * Authorize controller actions
     * @param $action
     * @return bool
     */
    public function authorize($action): bool
    {
        return $this->app->getAuth()->isLogged();
    }

    /**
     * Example of an action (authorization needed)
     * @return \App\Core\Http\Responses\Response|\App\Core\Http\Responses\ViewResponse
     */
    public function index(): Response
    {
        return $this->html();
    }
}
