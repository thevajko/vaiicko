<?php

namespace App;

use App\Config\Configuration;
use App\Core\HTTPException;
use App\Core\IAuthenticator;
use App\Core\DB\Connection;
use App\Core\LinkGenerator;
use App\Core\Request;
use App\Core\Responses\RedirectResponse;
use App\Core\Responses\Response;
use App\Core\Router;

/**
 * Class App
 * Main Application class
 * @package App
 */
class App
{
    /**
     * @var Router
     */
    private $router;
    /**
     * @var Request
     */
    private Request $request;
    private LinkGenerator $linkGenerator;
    private ?IAuthenticator $auth;

    /**
     * App constructor
     */
    public function __construct()
    {
        $this->router = new Router();
        $this->request = new Request();
        $this->linkGenerator = new LinkGenerator($this->request, $this->router);

        // Check if there is an authenticator
        if (defined('\\App\\Config\\Configuration::AUTH_CLASS')) {
            //$authClass = Configuration::AUTH_CLASS;
            $this->auth = new (Configuration::AUTH_CLASS)();
        } else {
            $this->auth = null;
        }
    }

    /**
     * Runs the application
     * @throws \Exception
     */
    public function run()
    {
        ob_start();

        try {
            // get a controller and action from URL
            $this->router->processURL();

            // inject app into Controller
            call_user_func([$this->router->getController(), 'setApp'], $this);

            // try to authorize action
            if ($this->router->getController()->authorize($this->router->getAction())) {
                // call appropriate method of the controller class
                $response = call_user_func([$this->router->getController(), $this->router->getAction()]);

                // return view to user
                if ($response instanceof Response) {
                    $response->send();
                } else {
                    throw new \Exception("Action {$this->router->getFullControllerName()}::{$this->router->getAction()} didn't return an instance of Response.");
                }
            } else {
                if ($this->auth->isLogged() || !defined('\\App\\Config\\Configuration::LOGIN_URL')) {
                    throw new HTTPException(403);
                } else {
                    (new RedirectResponse(Configuration::LOGIN_URL))->send();
                }
            }
        } catch (\Throwable $exception) {
            //Clears partially rendered content
            ob_end_clean();

            // if not HTTP exception wrap it to one
            if (!($exception instanceof HTTPException)) {
                $exception = HTTPException::from($exception);
            }
            // get handler instance
            $errorHandler = new (Configuration::ERROR_HANDLER_CLASS)();
            // handle error and send response
            $errorHandler->handleError($this, $exception)->send();
        }

        // if SQL debugging in configuration is allowed, display all SQL queries
        if (Configuration::SHOW_SQL_QUERY) {
            $queries = array_map(function ($q) {
                $lines = explode("\n", $q);
                $query = "Sent ";
                foreach ($lines as $line) {
                    if (preg_match("/^Sent SQL: \[\d+\]/", $line)) {
                        $query = $line;
                    } else if (preg_match("/^Params:  \d+/", $line)) {
                        break;
                    } else {
                        $query .= $line . "\n";
                    }
                }
                return '<pre>' . trim($query) . '</pre>';
            }, Connection::getQueryLog());
            echo implode(PHP_EOL . PHP_EOL, $queries);
        }
    }

    /**
     * @return Router
     */
    public function getRouter(): Router
    {
        return $this->router;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @return IAuthenticator|null
     */
    public function getAuth(): ?IAuthenticator
    {
        return $this->auth;
    }

    /**
     * @return LinkGenerator
     */
    public function getLinkGenerator(): LinkGenerator
    {
        return $this->linkGenerator;
    }
}
