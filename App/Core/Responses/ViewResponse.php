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
    private string $viewName;
    private array $data;

    /**
     * Constructor
     * @param App $app
     * @param string $viewName
     * @param array $data
     */
    public function __construct(App $app, string $viewName, array $data)
    {
        $this->app = $app;
        $this->viewName = $viewName;
        $this->data = $data;
    }

    /**
     * Render a view
     */
    protected function generate(): void
    {
        $layout = Configuration::ROOT_LAYOUT;

        //Insert view helpers
        $auth = $this->app->getAuth();
        $link = $this->app->getLinkGenerator();

        //Extract variables from controller
        extract($this->data, EXTR_SKIP);

        ob_start();
        require "App" . DIRECTORY_SEPARATOR . "Views" . DIRECTORY_SEPARATOR . $this->viewName . ".view.php";

        if ($layout != null) {
            $contentHTML = ob_get_clean();
            //Unsets data, because data are not needed to be passed to layout
            foreach (array_keys($this->data) as $array_key) {
                if (!in_array($array_key, ["auth", "link", "layout"])) {
                    unset($$array_key);
                }
            }
            require "App" . DIRECTORY_SEPARATOR . "Views" . DIRECTORY_SEPARATOR . $this->getLayoutFullName($layout);
        } else {
            ob_end_flush();
        }
    }

    /**
     * Finds full path of layout
     * @param string $layoutName
     */
    private function getLayoutFullName($layoutName): string
    {
        return str_ends_with($layoutName, '.layout.view.php') ? $layoutName : $layoutName . '.layout.view.php';
    }
}
