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

    /**
     * ViewResponse constructor
     * @param $viewName
     * @param $data
     */
    public function __construct($viewName, $data)
    {
        $this->viewName = $viewName;
        $this->data = $data;
    }

    /**
     * Generates view with data
     */
    public function generate() {
        $data = $this->data;

        // render view
        require "App" . DIRECTORY_SEPARATOR . "Views" . DIRECTORY_SEPARATOR . $this->viewName . ".view.php";

        // gets current buffer content (a rendered view) and stores it into $contentHTML
        $contentHTML = ob_get_clean();

        require "App" . DIRECTORY_SEPARATOR . "Views" . DIRECTORY_SEPARATOR . $this->layoutName;

    }

    /**
     * Changes default layout
     * @param mixed $layoutName
     */
    public function setLayoutName($layoutName)
    {
        $this->layoutName = $layoutName;
        return $this;
    }
}