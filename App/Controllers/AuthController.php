<?php

namespace App\Controllers;

use App\Config\Configuration;
use App\Core\AControllerBase;
use App\Core\HTTPException;
use App\Core\Responses\Response;

/**
 * Class AuthController
 * Controller for authentication actions
 * @package App\Controllers
 */
class AuthController extends AControllerBase
{
    /**
     *
     * @return \App\Core\Responses\RedirectResponse|\App\Core\Responses\Response
     */
    public function index(): Response
    {
        return $this->redirect(Configuration::LOGIN_URL);
    }

    /**
     * Login a user
     * @return \App\Core\Responses\RedirectResponse|\App\Core\Responses\ViewResponse
     */
    public function login(): Response
    {
        $formData = $this->app->getRequest()->getPost();
        $logged = null;

        $isAjax = $this->app->getRequest()->isAjax();

        if (isset($formData['login'])) {
            $logged = $this->app->getAuth()->login($formData['login'], $formData['password']);

            if ($logged) {
                if ($isAjax) {
                    return $this->json(["ok" => "ok"]);
                }
                return $this->redirect('?c=admin');
            }
        }

        if ($this->app->getRequest()->isAjax()) {
                throw new HTTPException(400, 'Zlý login alebo heslo!');
        }

        $data = ($logged === false ? ['message' => 'Zlý login alebo heslo!'] : []);
        return $this->html($data);
    }

    /**
     * Logout a user
     * @return \App\Core\Responses\ViewResponse
     */
    public function logout(): Response
    {
        $this->app->getAuth()->logout();

        if ($this->app->getRequest()->isAjax()) {
            return $this->json(["ok" => "ok"]);
        }

        return $this->html();
    }

    public function status(){
        if ($this->app->getRequest()->isAjax()){
            return $this->json([
                'login' => $this->app->getAuth()->getLoggedUserName()
            ]);
        }
        throw new HTTPException(401);
    }
}