<?php

namespace App\Core\Responses;

use App\Config\Configuration;
use App\Core\ControllerContext;

/**
 * Class ViewResponse
 * Response returning view
 * @package App\Core\Responses
 */
class ViewResponse extends Response
{
    private ControllerContext $controllerContext;
    private string $viewName;
    private string $layoutName;
    private mixed $data;

    /**
     * Constructor
     */
    public function __construct(ControllerContext $controllerContext, string $viewName, mixed $data)
    {
        $this->controllerContext = $controllerContext;
        $this->viewName = $viewName;
        $this->data = $data;
    }

    /**
     * Return a rendered view
     * @return mixed|void
     */
    public function generate()
    {
        $layout = Configuration::ROOT_LAYOUT;
        $data = $this->data;
        $auth = $this->controllerContext->getAuth();

        require "App" . DIRECTORY_SEPARATOR . "Views" . DIRECTORY_SEPARATOR . $this->viewName . ".view.php";

        $contentHTML = ob_get_clean();
        $this->setLayoutName($layout);

        require "App" . DIRECTORY_SEPARATOR . "Views" . DIRECTORY_SEPARATOR . $this->layoutName;
    }

    /**
     * Set another root layout if needed.
     * @param mixed $layoutName
     */
    public function setLayoutName($layoutName)
    {
        $this->layoutName = str_ends_with($layoutName, '.layout.view.php') ? $layoutName : $layoutName . '.layout.view.php';
        return $this;
    }
}