<?php

namespace App\Controllers;

use App\Core\AControllerBase;
use App\Core\Responses\JsonResponse;

/**
 * Class HomeController
 * Example of simple controller
 * @package App\Controllers
 */
class HomeController extends AControllerBase
{

    public function index()
    {
        return $this->html(
            [
                'meno' => 'študent'
            ]);
    }

    public function contact()
    {
        return $this->html(
            []
        );
    }

    /**
     * @return mixed
     */
    public function data()
    {
        return $this->json(
            ['data' => 'aaa']
        );
    }
}