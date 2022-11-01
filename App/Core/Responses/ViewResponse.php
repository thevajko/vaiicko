<?php

namespace App\Core\Responses;

use App\App;
use App\Config\Configuration;

/**
 * Class ViewResponse
 * Response returning view
 * @package App\Core\Responses
 */
class ViewResponse extends Response
{
    private App $app;
    private $viewName;
    private $layoutName = Configuration::ROOT_LAYOUT;
    private $data;

    /**
     * Constructor
     * @param $app
     * @param $viewName
     * @param $data
     */
    public function __construct($app, $viewName, $data)
    {
        $this->app = $app;
        $this->viewName = $viewName;
        $this->data = $data;
    }

    /**
     * Return a rendered view
     * @return mixed|void
     */
    public function generate()
    {

        $data = $this->data;
        $auth = $this->app->getAuth();

        require "App" . DIRECTORY_SEPARATOR . "Views" . DIRECTORY_SEPARATOR . $this->viewName . ".view.php";

        $contentHTML = ob_get_clean();

        require "App" . DIRECTORY_SEPARATOR . "Views" . DIRECTORY_SEPARATOR . $this->layoutName;
    }

    /**
     * Set another root layout if needed.
     * @param mixed $layoutName
     */
    public function setLayoutName($layoutName)
    {
        $this->layoutName = $layoutName;
        return $this;
    }
}