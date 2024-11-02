<?php

namespace Framework\Http\Responses;

/**
 * Class EmptyResponse
 *
 * Represents an HTTP response with no content. This class is specifically designed to indicate a successful request
 * that does not return any body content, corresponding to the HTTP status code 204 (No Content).
 *
 * The EmptyResponse class can be utilized in various scenarios, such as:
 * - Successfully processing a DELETE request where no further information needs to be returned to the client.
 * - A response indicating that a request was valid, but the server has no content to provide.
 *
 * @package App\Core\Responses
 */
class EmptyResponse extends Response
{
    /**
     * EmptyResponse constructor.
     *
     * Initializes the response with a status code of 204 (No Content). This indicates that the request was successfully
     * processed but there is no content to return.
     */
    public function __construct()
    {
        $this->setStatusCode(204);
    }

    /**
     * Generates the response output.
     *
     * This method is inherited from the Response class. In the case of EmptyResponse, this method does not output any
     * content as the response is intentionally left empty.
     *
     * @return void
     */
    protected function generate(): void
    {
    }
}
