<?php

namespace App\Config;

use App\Auth\DummyAuthenticator;
use App\Core\ErrorHandler;

/**
 * Class Configuration
 * Main configuration for the application
 * @package App\Config
 */
class Configuration
{
    /**
     * App name
     */
    public const APP_NAME = 'Vaííčko MVC FW';
    public const FW_VERSION = '2.2';

    /**
     * DB settings
     */
    public const DB_HOST = 'db';  // see docker/docker-compose.yml
    public const DB_NAME = 'vaiicko_db'; // see docker/.env
    public const DB_USER = 'vaiicko_user'; // see docker/.env
    public const DB_PASS = 'dtb456'; // see docker/.env

    /**
     * URL where main page logging is. If action needs login, user will be redirected to this url
     */
    public const LOGIN_URL = '?c=auth&a=login';
    /**
     * Prefix of default view in App/Views dir. <ROOT_LAYOUT>.layout.view.php
     */
    public const ROOT_LAYOUT = 'root';
    /**
     * Add all SQL queries after app output
     */
    public const SHOW_SQL_QUERY = false;

    /**
     * Show detailed stacktrace using default exception handler. Should be used only for development.
     */
    public const SHOW_EXCEPTION_DETAILS = true;
    /**
     * Class used as authenticator. Must implement IAuthenticator
     */
    public const AUTH_CLASS = DummyAuthenticator::class;
    /**
     * Class used as error handler. Must implement IHandleError
     */
    public const ERROR_HANDLER_CLASS = ErrorHandler::class;
}
