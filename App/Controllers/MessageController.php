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

    public function sendMessage(){

        $jsonData = json_decode(file_get_contents('php://input'));



    }

    public function getMessages(){

    }
}