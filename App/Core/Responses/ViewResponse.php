<?php

namespace App\Core\Responses;

use App\Config\Configuration;
use App\Core\App;

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

        //create view helpers
        $viewHelpers = [
            'auth' => $this->app->getAuth(),
            'link' => $this->app->getLinkGenerator(),
        ];

        ob_start();
        //Renders view file
        $this->renderView($layout, $viewHelpers + $this->data, $this->viewName . ".view.php");

        //Renders layout
        if ($layout != null) {
            $contentHTML = ob_get_clean();
            $layoutData = $viewHelpers + ['contentHTML' => $contentHTML];
            $this->renderView($layout, $layoutData, $this->getLayoutFullName($layout));
        } else {
            ob_end_flush();
        }
    }

    private function renderView(string &$layout, array $data, $viewPath): void
    {
        //Extract variables from controller
        extract($data, EXTR_SKIP);

        //Include view file
        require "App" . DIRECTORY_SEPARATOR . "Views" . DIRECTORY_SEPARATOR . $viewPath;
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
