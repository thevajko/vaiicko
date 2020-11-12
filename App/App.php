<?php

namespace App;

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
     * @var Config\Configuration|null
     */
    private static $config;

    /**
     * App constructor
     */
    public function __construct()
    {
        self::$config = Config\Configuration::getInstance();
        $this->router = new Router();
    }

    /**
     * Runs the application
     * @throws \Exception
     */
    public function run()
    {
        ob_start();

        $route = $this->router->processURL();

        $data = call_user_func([$route['controller'], $route['action']]);

        require "App" . DIRECTORY_SEPARATOR . "Views" . DIRECTORY_SEPARATOR . $route['controller']->getName() . DIRECTORY_SEPARATOR . $route['action'] . ".view.php";

        $contentHTML = ob_get_clean();

        require "App" . DIRECTORY_SEPARATOR . "Views" . DIRECTORY_SEPARATOR . "root.layout.view.php";
    }

    /**
     * @return Config\Configuration|null
     */
    public static function getConfig(): ?Config\Configuration
    {
        return self::$config;
    }

}