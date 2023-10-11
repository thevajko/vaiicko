<?php

namespace App\Core;

use App\Config\Configuration;
use App\Core\Responses\Response;
use App\Core\Responses\ViewResponse;

class ErrorHandler
{

    function handleError($app, HTTPException $exception) : Response {
        $data = [
            "exception" => $exception,
            "showDetail" => Configuration::DEBUG_EXCEPTION_HANDLER
        ];
        return (new ViewResponse($app, "_Error/error", $data))
            ->setStatusCode($exception->getCode());
    }

}