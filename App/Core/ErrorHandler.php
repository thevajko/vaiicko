<?php

namespace App\Core;

use App\App;
use App\Config\Configuration;
use App\Core\Responses\JsonResponse;
use App\Core\Responses\Response;
use App\Core\Responses\ViewResponse;

class ErrorHandler implements IHandleError
{

    function handleError(App $app, HTTPException $exception) : Response {
        $data = [
            "exception" => $exception,
            "showDetail" => Configuration::DEBUG_EXCEPTION_HANDLER
        ];

        if ($app->getRequest()->isAjax()) {
            return (new JsonResponse([
                'code'   => $exception->getCode(),
                'status' => $exception->getMessage(),
                'stack'  => $exception->getTraceAsString()
            ]))
                ->setStatusCode($exception->getCode());
        } else {
            return (new ViewResponse($app, "_Error/error", $data))
                ->setStatusCode($exception->getCode());
        }
    }

}