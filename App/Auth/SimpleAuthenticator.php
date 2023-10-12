<?php
namespace App\Auth;
class SimpleAuthenticator extends DummyAuthenticator {

    public function  login($login, $password): bool
    {
        // this do the trick
        // user is logged in when login equals password
        if ($login == $password) {
            $_SESSION['user'] = $login;
            return true;
        } else {
            return false;
        }
    }

}