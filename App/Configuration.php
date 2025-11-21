<?php

namespace App;

use Framework\Auth\DummyAuthenticator;
use Framework\Core\ErrorHandler;
use Framework\DB\DefaultConventions;

/**
 * Class Configuration
 *
 * This class holds the main configuration settings for the application, including application name, framework version,
 * database connection settings, authentication, error handling, and other runtime configurations.
 *
 * @package App\Config
 */
class Configuration
{
    /**
     * Application name.
     */
    public const APP_NAME = 'Vaííčko MVC FW';

    /**
     * Version of the framework.
     */
    public const FW_VERSION = '3.0.6';

    /**
     * Database connection settings.
     */
    public const DB_HOST = 'db';  // Hostname for the database service (defined in docker/docker-compose.yml)
    public const DB_NAME = 'vaiicko_db'; // Database name (defined in docker/.env)
    public const DB_USER = 'vaiicko_user'; // Username for database access (defined in docker/.env)
    public const DB_PASS = 'dtb456'; // Password for database access (defined in docker/.env)

    /**
     * URL for the login page. Users will be redirected here if authentication is required for an action.
     */
    public const LOGIN_URL = '?c=auth&a=login';

    /**
     * Prefix for the default view files located in the App/Views directory. The view file format is
     * <ROOT_LAYOUT>.layout.view.php.
     */
    public const ROOT_LAYOUT = 'root';

    /**
     * Flag to determine whether to display all SQL queries after the application output for debugging purposes.
     */
    public const SHOW_SQL_QUERY = false;

    /**
     * Class name for the database naming conventions implementation. This should adhere to the IDbConvention interface.
     * The default implementation is DefaultConventions.
     */
    public const DB_CONVENTIONS_CLASS = DefaultConventions::class;

    /**
     * Flag to enable or disable detailed exception stack traces. This feature is intended for development purposes
     * only.
     */
    public const SHOW_EXCEPTION_DETAILS = true;

    /**
     * Class name for the authenticator. This class must implement the IAuthenticator interface. Comment out this line
     * if authentication is not required in the application.
     */
    public const AUTH_CLASS = DummyAuthenticator::class;

    /**
     * Class name for the error handler. This class must implement the IHandleError interface.
     */
    public const ERROR_HANDLER_CLASS = ErrorHandler::class;

    /**
     * Directory for file uploads on the filesystem (uses OS-specific directory separators).
     * Example on Linux:  public/uploads/
     * Example on Windows: public\uploads\
     */
    public const UPLOAD_DIR = 'uploads' . DIRECTORY_SEPARATOR;

    /**
     * Public URL path prefix for uploaded files (always uses forward slashes for web URLs).
     * Example: /uploads/
     */
    public const UPLOAD_URL = '/uploads/';

    // Session key for storing the user identity
    public const IDENTITY_SESSION_KEY = 'fw.session.user.identity';
}
