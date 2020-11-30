<?php

namespace App\Core\Responses;

use App\Config\Configuration;

/**
 * Class ViewResponse
 * Response returning view
 * @package App\Core\Responses
 */
class ViewResponse extends Response
{
    private $viewName;
    private $layoutName = Configuration::ROOT_LAYOUT;
    private $data;

    public function __construct($viewName, $data)
    {
        $this->viewName = $viewName;
        $this->data = $data;
    }

    public function generate() {
        $data = $this->data;

        require "App" . DIRECTORY_SEPARATOR . "Views" . DIRECTORY_SEPARATOR . $this->viewName . ".view.php";

        $contentHTML = ob_get_clean();

        require "App" . DIRECTORY_SEPARATOR . "Views" . DIRECTORY_SEPARATOR . $this->layoutName;

    }

    /**
     * @param mixed $layoutName
     */
    public function setLayoutName($layoutName)
    {
        $this->layoutName = $layoutName;
        return $this;
    }

}