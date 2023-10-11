<?php

namespace App\Core;

use App\Core\Responses\Response;
use App\Core\Responses\ViewResponse;

class ErrorHandler
{

    function handleError($app, HTTPException $exception) : Response {
        $statusCode = 501;

        return (new ViewResponse($app, "_Error/error", ["exception" => $exception]))
                ->setStatusCode($statusCode);
    }

}