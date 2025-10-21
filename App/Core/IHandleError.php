<?php

namespace Framework\Core;

use Framework\Http\HttpException;
use Framework\Http\Responses\Response;

/**
 * Interface IHandleError
 *
 * This interface defines the contract for custom error handling classes within the application. Any class that
 * implements this interface must provide an implementation for handling errors represented by the HttpException
 * class.
 *
 * The primary responsibility of implementing classes is to manage error scenarios gracefully, ensuring that users
 * receive meaningful feedback when exceptions occur during application execution. This interface allows for
 * flexibility in error handling strategies, accommodating different response formats based on the needs of the
 * application.
 *
 * Implementing the handleError method allows developers to define custom logic for processing exceptions, including
 * how errors are logged, how user-facing messages are generated, and how responses are structured (e.g., JSON
 * for APIs or HTML for web pages).
 *
 * @package App\Core
 */
interface IHandleError
{
    /**
     * Handle an HttpException and return an appropriate Response.
     *
     * @param App $app The current application instance, providing access to its components, such as the request and
     *                 configuration.
     * @param \Framework\Http\HttpException $exception The exception to be handled, containing details about the error that occurred.
     *
     * @return Response A Response object that encapsulates the result of handling the exception, ready to be sent
     *                  to the client.
     */
    public function handleError(App $app, HttpException $exception): Response;
}
