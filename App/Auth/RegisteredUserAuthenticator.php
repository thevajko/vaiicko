<?php

namespace App\Auth;

use App\Auth\DummyAuthenticator;
use App\Models\Login;

class RegisteredUserAuthenticator extends DummyAuthenticator
{
    /**
     * Verify, if the user is in DB and has his password is correct
     * @param $login
     * @param $password
     * @return bool
     * @throws \Exception
     */
    public function login($login, $password): bool
    {
        $loggedUser = Login::getByLogin($login);
        if (is_null($loggedUser))
        {
            return false;
        }
        else if (password_verify($password, $loggedUser->getPassword())) {
            $_SESSION['user'] = $loggedUser->getLogin();
            return true;
        } else {
            return false;
        }
    }
}