<?php

namespace App\Controllers;

use App\Core\AControllerBase;
use App\Core\Responses\Response;

/**
 * Class HomeController
 * Example class of a controller
 * @package App\Controllers
 */
class HomeController extends AControllerBase
{
    /**
     * Authorize controller actions
     * @param $action
     * @return bool
     */
    public function authorize($action)
    {
        return true;
    }

    /**
     * Example of an action (authorization needed)
     * @return \App\Core\Responses\Response|\App\Core\Responses\ViewResponse
     */
    public function index(): Response
    {
        return $this->html();
    }

    /**
     * Example of an action accessible without authorization
     * @return \App\Core\Responses\ViewResponse
     */
    public function contact(): Response
    {
        return $this->html();
    }

    public function myPage(): Response
    {
        return $this->html();
    }

    public function hello(): Response
    {
        return $this->html(
            [
                'name' => 'Patrik'
            ]
        );
    }

    public function greeting(): Response
    {
        $name = $this->request()->getGet()['name'];
        return $this->html(
            [
                'name' => $name
            ]
        );
    }

    public function hi(): Response
    {
        return $this->redirect($this->url('hello'));
    }

    public function list(): Response
    {
        return $this->html(
            [
                'list' => ['Peter', 'Zuzana', 'Ján', 'Eduard', 'Petra', 'Jozef', 'Adam', 'Zdena']
            ]
        );
    }
}
