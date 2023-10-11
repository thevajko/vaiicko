<?php

namespace App\Core;

use App\Core\Responses\JsonResponse;
use App\Core\Responses\RedirectResponse;
use App\Core\Responses\Response;
use App\Core\Responses\ViewResponse;

/**
 * Class AControllerBase
 * Basic controller class, predecessor of all controllers
 * @package App\Core
 */
abstract class AControllerBase
{
    /**
     * Reference to controller context
     */
    private ControllerContext $context;

    /**
     * Returns controller name (without Controller prefix)
     */
    public function getName()  : string
    {
        return str_replace("Controller", "", $this->getClassName());
    }

    /**
     * Return full class name
     */
    public function getClassName() : string
    {
        $arr = explode("\\", get_class($this));
        return end($arr);
    }

    /**
     * Method for injecting controller context
     */
    public final function setContext(ControllerContext $context) : void
    {
        $this->context = $context;
    }

    /**
     * Helper method for returning response type ViewResponse
     * @param mixed|null $data
     * @param string|null $viewName
     * @return ViewResponse
     */
    protected function html(mixed $data = null, string $viewName = null) : ViewResponse
    {
        if ($viewName == null) {
            $viewName = $this->getName(). DIRECTORY_SEPARATOR . $this->context->getRoute()->getAction();
        } else {
            $viewName = is_string($viewName) ? ($this->getName() . DIRECTORY_SEPARATOR . $viewName) : ($viewName['0'] . DIRECTORY_SEPARATOR . $viewName['1']);
        }
        return new ViewResponse($this->context, $viewName, $data);
    }

    /**
     * Helper method for returning response type JsonResponse
     * @param $data
     * @return JsonResponse
     */
    public function json($data) : JsonResponse
    {
        return new JsonResponse($data);
    }

    /**
     * Helper method for redirect request to another URL
     * @param string $redirectUrl
     * @return RedirectResponse
     */
    public function redirect(string $redirectUrl) : RedirectResponse
    {
        return new RedirectResponse($redirectUrl);
    }

    /**
     * Helper method for request
     */
    protected function getRequest() : Request
    {
        return $this->context->getRequest();
    }

    /**
     * Helper method for auth
     */
    protected function getAuth() : IAuthenticator | null
    {
        return $this->context->getAuth();
    }

    /**
     * Authorize action
     * @param string $action
     * @return bool
     */
    public function authorize(string $action)
    {
        return true;
    }

    /**
     * Every controller should implement the method for index action at least
     * @return Response
     */
    public abstract function index(): Response;
}