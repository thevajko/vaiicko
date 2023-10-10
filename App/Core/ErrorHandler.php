<?php

namespace App\Core;

use App\Core\Responses\Response;

class ErrorHandler
{
    function handleError(\Exception $exception) : Response {
        return $this->html("ddd");
    }

}