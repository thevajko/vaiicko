<?php

namespace App\Config;

use App\Auth\DummyAuthenticator;
use App\Auth\SimpleAuthenticator;
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
    public const FW_VERSION = '2.0';

    public const DB_HOST = 'localhost';  // change to db, if docker you use docker
    public const DB_NAME = 'vaiicko_db';
    public const DB_USER = 'root'; // change to vaiicko_user, if docker you use docker
    public const DB_PASS = 'dtb456';
    /**
     * URL where main page loging is. If action needs login, user will be redirected to this url
     */
    public const LOGIN_URL = null; //'?c=auth&a=login';
    /**
     * Prefix of default view in App/Views dir. <ROOT_LAYOUT>.layout.view.php
     */
    public const ROOT_LAYOUT = 'root';
    /**
     * Add all SQL queries after app output
     */
    public const DEBUG_QUERY = false;

    public const DEBUG_EXCEPTION_HANDLER = true; //TODO ? Toz co je totok?
    /**
     * Class used as authenticator. Must implement IAuthenticator
     */
    public const AUTH_CLASS = SimpleAuthenticator::class;
    /**
     * Class used as error handler. Must implement IHandleError
     */
    public const ERROR_HANDLER_CLASS = ErrorHandler::class;
}