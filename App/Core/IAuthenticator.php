<?php

namespace App\Core;

/**
 * Interface IAuthenticator
 * Interface for authentication
 * @package App\Core
 */
interface IAuthenticator
{
    /**
     * Perform user login
     * @param $userLogin
     * @param $pass
     * @return bool
     */
    function login($userLogin, $pass): bool;

    /**
     * Perform user login
     * @return void
     */
    function logout(): void;

    /**
     * Return name of a logged user
     * @return string
     */
    function getLoggedUserName(): string;

    /**
     * Return id of a logged user
     * @return mixed
     */
    function getLoggedUserId(): mixed;

    /**
     * Return a context of logged user, e.g. user class instance
     * @return mixed
     */
    function getLoggedUserContext(): mixed;

    /**
     * Return, if a user is logged or not
     * @return bool
     */
    function isLogged(): bool;
}