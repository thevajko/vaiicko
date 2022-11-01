<?php

namespace App\Core;

/**
 * Class AAuthenticator
 * Abstract class for authentication
 * @package App\Core
 */
abstract class AAuthenticator
{
    /**
     * Perform user login
     * @param $userLogin
     * @param $pass
     * @return bool
     */
    abstract function login($userLogin, $pass) : bool;

    /**
     * Perform user login
     * @return mixed
     */
    abstract function logout();

    /**
     * Return name of a logged user
     * @return string
     */
    abstract function getLoggedUserName(): string;

    /**
     * Return a context of logged user, e.g. user class instance
     * @return mixed
     */
    abstract function getLoggedUserContext(): mixed;

    /**
     * Return, if a user is logged or not
     * @return bool
     */
    abstract function isLogged() : bool;
}