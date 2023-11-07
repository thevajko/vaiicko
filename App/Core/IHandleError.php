<?php

namespace App\Core;

use App\Core\Http\HttpException;
use App\Core\Responses\Response;

/**
 * Must be implemented in custom error handler
 */
interface IHandleError
{
    public function handleError(App $app, HttpException $exception): Response;
}
