<?php

namespace Framework\Core;

use App\Configuration;
use Framework\Auth\AppUser;
use Framework\DB\Connection;
use Framework\Http\HttpException;
use Framework\Http\Request;
use Framework\Http\Responses\RedirectResponse;
use Framework\Http\Responses\Response;
use Framework\Http\Session;
use Framework\Support\LinkGenerator;

/**
 * Class App
 *
 * The main application class that serves as the core of the framework. This class is responsible for initializing and
 * managing various components of the application, including routing, request handling, authentication, and response
 * generation.
 *
 * The App class functions as the entry point for processing incoming HTTP requests. It orchestrates the flow of data
 * between different parts of the application by coordinating the router, managing the request object, handling user
 * authentication, and generating responses. It is designed to be flexible, allowing for easy integration of additional
 * features or modifications to existing behaviors.
 * *
 * @package App
 */
class App
{
    /**
     * @var Router The router responsible for mapping URLs to controller actions.
     */
    private Router $router;

    /**
     * @var Request The HTTP request object that contains request data and methods.
     */
    private Request $request;

    /**
     * @var LinkGenerator The link generator used to create URLs for application routes.
     */
    private LinkGenerator $linkGenerator;

    /**
     * @var IAuthenticator|null The authenticator instance for handling user authentication, or null if authentication
     *                          is not configured.
     */
    private ?IAuthenticator $auth;

    /**
     * @var \Framework\Http\Session|null The session instance for managing session data, or null if not yet created.
     */
    private ?Session $session;

    /**
     * App constructor.
     *
     * Initializes the application by creating instances of the router, request, and link generator. If authentication
     * is configured, it initializes the authenticator.
     */
    public function __construct()
    {
        $this->router = new Router();
        $this->request = new Request();
        $this->linkGenerator = new LinkGenerator($this->request, $this->router);

        // Register error and shutdown handlers for unified error processing
        $this->registerErrorHandler();
        $this->registerShutdownHandler();

        // Check if there is an authenticator defined in the configuration.
        if (defined('\\App\\Configuration::AUTH_CLASS')) {
            $this->auth = new (Configuration::AUTH_CLASS)($this);
        } else {
            $this->auth = null;
        }
    }


    /**
     * Runs the application, processing the incoming request and generating a response.
     *
     * This method handles routing, controller actions, authorization, and error management.
     *
     * @throws \Exception If an error occurs that is not an HttpException.
     */
    public function run(): void
    {
        ob_start();

        try {
            // Process the incoming URL to determine the appropriate controller and action.
            $this->router->processURL();

            // Inject the current application instance into the controller.
            call_user_func([$this->router->getController(), 'setApp'], $this);

            // Attempt to authorize the requested action.
            if ($this->router->getController()->authorize($this->request, $this->router->getAction())) {
                // Call the specified action method on the controller with Request as required parameter (no reflection)
                $response = call_user_func([$this->router->getController(), $this->router->getAction()], $this->request);

                // If the response is valid, send it to the client.
                if ($response instanceof Response) {
                    $response->send();
                } else {
                    throw new \Exception("Action " . $this->router->getFullControllerName() . "." .
                        $this->router->getAction() . "didn't return an instance of Response.");
                }
            } else {
                // If authorization fails, check if the user is logged in or redirect to the login page.
                if (($this->auth?->getUser()?->isLoggedIn()) || !defined('\\App\\Configuration::LOGIN_URL')) {
                    throw new HttpException(403); // Forbidden access
                } else {
                    (new RedirectResponse(Configuration::LOGIN_URL))->send();
                }
            }
        } catch (\Throwable $exception) {
            // Clear any partially rendered output.
            ob_end_clean();

            // Wrap non-HTTP exceptions in an HttpException.
            if (!($exception instanceof HttpException)) {
                $exception = HttpException::from($exception);
            }

            // Get the configured error handler and process the error.
            $errorHandler = new (Configuration::ERROR_HANDLER_CLASS)();
            $errorHandler->handleError($this, $exception)->send();
        }

        // If SQL debugging is enabled, display executed SQL queries.
        if (Configuration::SHOW_SQL_QUERY) {
            $queries = array_map(function ($q) {
                $lines = explode("\n", $q);
                $query = "Sent ";
                foreach ($lines as $line) {
                    if (preg_match("/^Sent SQL: \[\d+]/", $line)) {
                        $query = $line;
                    } elseif (preg_match("/^Params: \d+/", $line)) {
                        break;
                    } else {
                        $query .= $line . "\n";
                    }
                }
                return '<pre>' . trim($query) . '</pre>'; // Format the query for display.
            }, Connection::getQueryLog());
            echo implode(PHP_EOL . PHP_EOL, $queries);
        }
    }

    /**
     * Gets the router instance.
     *
     * @return Router The router instance.
     */
    public function getRouter(): Router
    {
        return $this->router;
    }

    /**
     * Gets the HTTP request instance.
     *
     * @return Request The current request object.
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * Gets the current authenticator instance.
     *
     * @return IAuthenticator|null The authenticator instance, or null if not set.
     */
    public function getAuthenticator(): ?IAuthenticator
    {
        return $this->auth;
    }

    /**
     * Gets the instance of LinkGenerator.
     *
     * @return LinkGenerator The link generator instance.
     */
    public function getLinkGenerator(): LinkGenerator
    {
        return $this->linkGenerator;
    }

    /**
     * Gets the session instance, creating a new session if it doesn't exist.
     *
     * @return \Framework\Http\Session The current session instance.
     */
    public function getSession(): Session
    {
        if (!isset($this->session)) {
            return $this->session = new Session(); // Create a new session if not already created.
        } else {
            return $this->session; // Return the existing session.
        }
    }

    /**
     * Gets the current application user.
     *
     * @return AppUser The current application user.
     */
    public function getAppUser(): AppUser
    {
        return $this->auth?->getUser() ?? new AppUser();
    }

    /**
     * Register a global PHP error handler that throws ErrorException for all non-suppressed errors.
     */
    private function registerErrorHandler(): void
    {
        set_error_handler(static function (int $severity, string $message, string $file = '', int $line = 0) {
            // Respect error suppression and current error_reporting level
            if (!(error_reporting() & $severity)) {
                return false; // allow normal PHP error handling (e.g., for @ operator)
            }

            throw new \ErrorException($message, 0, $severity, $file, $line);
        });
    }

    /**
     * Register a shutdown handler to convert fatal errors into HttpException processed by the framework.
     */
    private function registerShutdownHandler(): void
    {
        $app = $this;
        register_shutdown_function(static function () use ($app) {
            $last = error_get_last();
            if ($last === null) {
                return;
            }

            $fatalTypes = [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR];
            if (!in_array($last['type'], $fatalTypes, true)) {
                return;
            }

            // Try to clean any partial output to avoid mixing with error page
            $prevLevel = ob_get_level();
            while ($prevLevel > 0) {
                ob_end_clean();
                $currLevel = ob_get_level();
                if ($currLevel >= $prevLevel) {
                    // Buffer level did not decrease, break to avoid infinite loop
                    break;
                }
                $prevLevel = $currLevel;
            }

            $errorEx = new \ErrorException($last['message'] ?? 'Fatal error', 0, $last['type'] ?? E_ERROR, $last['file'] ?? 'unknown', $last['line'] ?? 0);
            $httpEx = HttpException::from($errorEx, 500);

            try {
                $handler = new (Configuration::ERROR_HANDLER_CLASS)();
                $handler->handleError($app, $httpEx)->send();
            } catch (\Throwable $e) {
                // Last-resort fallback if even the handler fails
                if (!headers_sent()) {
                    http_response_code(500);
                    header('Content-Type: text/plain; charset=utf-8');
                }
                echo 'Internal Server Error';
            }
        });
    }
}
