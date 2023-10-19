<?php

namespace App\Core;

use App\App;
use App\Config\Configuration;
use App\Core\Responses\JsonResponse;
use App\Core\Responses\Response;
use App\Core\Responses\ViewResponse;

class ErrorHandler implements IHandleError
{

    function handleError(App $app, HTTPException $exception) : Response
    {
        $data = [
            "exception" => $exception,
            "showDetail" => Configuration::DEBUG_EXCEPTION_HANDLER
        ];

        // response error in JSON only if client wants to
        if ($app->getRequest()->clientRequestsJSON()) {
            // to make less mess, this function is used to do recursive crawl down whole exception tree
            function recursiveTrace(\Throwable $t) : array
            {
                return array_merge([$t->getTrace()], $t->getPrevious() ? recursiveTrace($t->getPrevious()) : []);
            }

            return (new JsonResponse([
                'code'   => $exception->getCode(),
                'status' => $exception->getMessage(),
                'stack'  => recursiveTrace($exception)
            ]))
                ->setStatusCode($exception->getCode());
        } else {
            return (new ViewResponse($app, "_Error/error", $data))
                ->setStatusCode($exception->getCode());
        }
    }

}