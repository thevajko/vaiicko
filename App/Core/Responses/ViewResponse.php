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
     * Render a view
     */
    protected function generate() : void
    {
        $layout = Configuration::ROOT_LAYOUT;
        $data = $this->data;

        //Insert view helpers
        $auth = $this->app->getAuth();
        $url = function (string|array $destination, array $parameters = [], bool $absolute = false, bool $appendParameters = false) : string
        {
            return $this->app->getLinkGenerator()->url($destination, $parameters, $absolute, $appendParameters);
        };

        require "App" . DIRECTORY_SEPARATOR . "Views" . DIRECTORY_SEPARATOR . $this->viewName . ".view.php";

        if ($layout != null) {
            $contentHTML = ob_get_clean();
            unset($data); //Unsets data, because data are not needed to be passed to layout
            require "App" . DIRECTORY_SEPARATOR . "Views" . DIRECTORY_SEPARATOR . $this->getLayoutFullName($layout);
        }
    }

    /**
     * Finds full path of layout
     * @param string $layoutName
     */
    private function getLayoutFullName($layoutName) : string
    {
        return str_ends_with($layoutName, '.layout.view.php') ? $layoutName : $layoutName . '.layout.view.php';
    }
}