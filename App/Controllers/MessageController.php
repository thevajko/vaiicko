<?php

namespace App\Controllers;

use App\Core\AControllerBase;
use App\Core\HTTPException;
use App\Core\Responses\Response;

class MessageController extends AControllerBase
{

    public function index(): Response
    {
        throw new HTTPException(501);
    }

    public function getMessages(){

    }
}