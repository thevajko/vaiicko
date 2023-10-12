<?php

namespace App\Core;

use App\App;
use App\Core\Responses\Response;

/**
 * Must be implemented in custom error handler
 */
interface IHandleError
{
    function handleError(App $app, HTTPException $exception) : Response;
}