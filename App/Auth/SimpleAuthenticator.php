<?php
namespace App\Auth;
use Framework\Auth\DummyAuthenticator;

class SimpleAuthenticator extends DummyAuthenticator {

    public function login($username, $password): bool
    {
        // user is logged in when login equals password
         if ($username == $password) {
             $_SESSION['user'] = $username;
             return true;
         } else {
             return false;
         }
    }

}