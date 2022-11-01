<?php

namespace App\Config;

use App\Auth\DummyAuthenticator;

/**
 * Class Configuration
 * Main configuration for the application
 * @package App\Config
 */
class Configuration
{
    public const APP_NAME = 'Vajííčko MVC FW';
    public const FW_VERSION = '2.0';

    public const DB_HOST = 'localhost';
    public const DB_NAME = 'vajiicko_db';
    public const DB_USER = 'root';
    public const DB_PASS = 'dtb456';

    public const LOGIN_URL = '?c=auth&a=login';

    public const ROOT_LAYOUT = 'root.layout.view.php';

    public const DEBUG_QUERY = false;

    public const AUTH_CLASS = DummyAuthenticator::class;
}