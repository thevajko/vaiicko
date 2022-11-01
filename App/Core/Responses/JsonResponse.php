<?php

namespace App\Core\Responses;

/**
 * Class JsonResponse
 * Response returning data as JSON
 * @package App\Core\Responses
 */
class JsonResponse extends Response
{
    private $data;

    /**
     * JsonResponse constructor
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Render response for JSON data
     */
    public function generate()
    {
        header('Content-Type: application/json');
        echo json_encode($this->data);
    }
}