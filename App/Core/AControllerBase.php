<?php

namespace App\Core;

use App\App;

/**
 * Class AControllerBase
 * Basic controller class, predecessor of all controllers
 * @package App\Core
 */
abstract class AControllerBase
{
    /**
     * Returns controller name (without Controller prefix)
     * @return string
     */
    public function getName()
    {
        return str_replace("Controller", "", $this->getClassName());
    }

    /**
     * Return full class name
     * @return string
     */
    public function getClassName()
    {
        $arr = explode("\\", get_class($this));
        return end($arr);
    }

    /**
     * Every controller should implement the method for index action at least
     * @return mixed
     */
    public abstract function index();
}