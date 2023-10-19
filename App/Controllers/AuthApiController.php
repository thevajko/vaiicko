<?php

namespace App\Controllers;

use App\Core\AControllerBase;
use App\Core\HTTPException;
use App\Core\Responses\Response;
use App\Models\Login;

class AuthApiController extends AControllerBase {

    /**
     * @throws HTTPException
     */
    public function index(): Response
    {
        throw new HTTPException(501);
    }
    public function login(): Response
    {
        $jsonData = $this->app->getRequest()->getRawBodyJSON();
       if (
           isset($jsonData->login) &&  isset($jsonData->password)
           && $this->app->getAuth()->login($jsonData->login, $jsonData->password)
       ) {

           $logged =  Login::OneByName($jsonData->login);

           if (empty($logged)) {
                $newLogin = new Login();
                $newLogin->setUsername($jsonData->login);
                $newLogin->save();
           }

           return $this->json([]);
       } else {
           throw new HTTPException(400, 'Bad credencials.');
       }
    }

    public function logout(): Response
    {

        $logged =  Login::OneByName($this->app->getAuth()->getLoggedUserName());
        $this->app->getAuth()->logout();

        if (!empty($logged)) {
            $logged->delete();
        }

        return $this->json([])->setStatusCode(204);
    }


    public function status() {
        return $this->json([
            'login' => $this->app->getAuth()->getLoggedUserName()
        ]);
    }
}