<?php

namespace Framework\Http\Responses;

use App\Configuration;

/**
 * Class RedirectResponse
 *
 * Represents an HTTP response that redirects the client to a specified URL.
 * This class is typically used in web applications to guide users to a
 * different page or endpoint, often after a form submission or an action
 * that requires a change in the user's location.
 *
 * @package App\Core\Responses
 */
class RedirectResponse extends Response
{
    /**
     * The URL to which the response should redirect the client.
     *
     * @var string
     */
    private string $redirectUrl;

    /**
     * RedirectResponse constructor.
     *
     * Initializes a new instance of RedirectResponse with the specified URL.
     * The HTTP status code is set to 301 (Moved Permanently) by default.
     *
     * @param string $redirectUrl The URL to which the client should be redirected.
     */
    public function __construct(string $redirectUrl)
    {
        $this->redirectUrl = $redirectUrl;
        $this->setStatusCode(301);
    }

    /**
     * Generates the redirect response.
     *
     * This method sets the HTTP Location header to the specified redirect URL,
     * prompting the client to navigate to that URL. If SQL debugging is enabled,
     * the method instead displays a message informing the user that they must
     * manually follow the redirect link, allowing for SQL logs to be observed.
     *
     * @return void
     */
    protected function generate(): void
    {
        // Check if SQL debugging is enabled in the configuration.
        if (!Configuration::SHOW_SQL_QUERY) {
            // If debugging is off, perform the redirect by setting the Location header.
            header('Location: ' . $this->redirectUrl);
        } else {
            // If debugging is on, inform the user to follow the redirect manually.
            echo 'In SQL debug mode you have to <a href="' . $this->redirectUrl . '">follow redirect</a> manually.';
        }
    }
}
