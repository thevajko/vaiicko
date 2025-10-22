<?php

namespace Framework\Core;

use App\Configuration;
use Framework\Http\HttpException;
use Framework\Http\Responses\JsonResponse;
use Framework\Http\Responses\Response;
use Framework\Http\Responses\ViewResponse;

/**
 * Class ErrorHandler
 *
 * This class implements the IHandleError interface and is responsible for managing error handling within the
 * application. It processes exceptions thrown during the application's execution and generates appropriate responses
 * based on the type of request and the application's configuration.
 *
 * The ErrorHandler class determines how to present error information to the client. It can either return a JSON
 * response, suitable for API clients or AJAX requests, or a rendered HTML view for standard web pages. The choice
 * between these formats is influenced by the client's expectations, which can be determined by checking request
 * headers.
 *
 * Overall, the ErrorHandler class plays a crucial role in maintaining robust error handling throughout the application.
 * It ensures that users receive appropriate feedback when errors occur, while also providing developers with the
 * necessary information to diagnose issues effectively.
 *
 * @package App\Core
 */
class ErrorHandler implements IHandleError
{
    public function handleError(App $app, HttpException $exception): Response
    {
        // response error in JSON only if client wants to
        if ($app->getRequest()->wantsJson()) {
            function getExceptionStack(\Throwable $throwable): array
            {
                $stack = [];
                while ($throwable != null) {
                    $entry = [];
                    $entry['message'] = $throwable->getMessage();
                    $entry['trace'] = $throwable->getTraceAsString();
                    $stack[] = $entry;

                    $throwable = $throwable->getPrevious();
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
