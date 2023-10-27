<?php

namespace App\Core\Responses;

use App\Config\Configuration;

/**
 * Class RedirectResponse
 * Response to redirect a request to another URL
 * @package App\Core\Responses
 */
class RedirectResponse extends Response
{
    private string $redirectUrl;

    /**
     * RedirectResponse constructor
     * @param string $redirectUrl
     */
    public function __construct(string $redirectUrl)
    {
        $this->redirectUrl = $redirectUrl;
        $this->setStatusCode(301);
    }

    /**
     * Redirect the request. If debugging is true, the request is not redirected (to allow see SQL log)
     */
    protected function generate(): void
    {
        if (!Configuration::SHOW_SQL_QUERY) {
            header('Location: ' . $this->redirectUrl);
        } else {
            echo 'In SQL debug mode you have to <a href="' . $this->redirectUrl . '">follow redirect</a> manually.';
        }
    }
}
