<?php

namespace App\Core;

use App\App;
use App\Config\Configuration;
use App\Core\Responses\JsonResponse;
use App\Core\Responses\Response;
use App\Core\Responses\ViewResponse;

class ErrorHandler implements IHandleError
{
    public function handleError(App $app, HTTPException $exception): Response
    {
        // response error in JSON only if client wants to
        if ($app->getRequest()->clientRequestsJSON()) {
            function getExceptionStack(\Throwable $t): array
            {
                $stack = [];
                while ($t != null) {
                    $ar = [];
                    $ar['message'] = $t->getMessage();
                    $ar['trace'] = $t->getTraceAsString();
                    $stack[] = $ar;

                    $t = $t->getPrevious();
                }

                return $stack;
            }

            $data = [
                'code' => $exception->getCode(),
                'status' => $exception->getMessage(),
            ];

            if (Configuration::SHOW_EXCEPTION_DETAILS) {
                $data['stack'] = getExceptionStack($exception);
            }

            return (new JsonResponse($data))
                ->setStatusCode($exception->getCode());
        } else {
            $data = [
                "exception" => $exception,
                "showDetail" => Configuration::SHOW_EXCEPTION_DETAILS
            ];

            return (new ViewResponse($app, "_Error/error", $data))
                ->setStatusCode($exception->getCode());
        }
    }
}
