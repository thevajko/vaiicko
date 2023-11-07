<?php

namespace App\Auth;

use App\Core\App;
use App\Core\Http\HttpException;
use App\Core\IAuthenticator;

/**
 * Class DummyAuthenticator
 * Basic implementation of user authentication
 * @package App\Auth
 */
class DummyAuthenticator implements IAuthenticator
{
    public const LOGIN = "admin";
    public const PASSWORD_HASH = '$2y$10$GRA8D27bvZZw8b85CAwRee9NH5nj4CQA6PDFMc90pN9Wi4VAWq3yq'; // admin
    public const USERNAME = "Admin";
    private App $app;
    private \App\Core\Http\Session $session;

    /**
     * DummyAuthenticator constructor
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->session = $this->app->getSession();
    }

    /**
     * Verifies, if the user is in DB and has his password is correct
     * @param $login
     * @param $password
     * @return bool
     * @throws \Exception
     */
    public function login($login, $password): bool
    {
        if ($login == self::LOGIN && password_verify($password, self::PASSWORD_HASH)) {
            $this->session->set('user', self::USERNAME);
            return true;
        } else {
            return false;
        }
    }

    /**
     * Logout the user
     */
    public function logout(): void
    {
        $this->session->destroy();
    }

    /**
     * Gets the name of the logged-in user
     * @return string
     */
    public function getLoggedUserName(): string
    {
        return $this->session->hasKey('user') ? $this->session->get('user') :
            throw new \Exception("User not logged in");
    }

    /**
     * Gets the context of the logged-in user
     * @return string
     */
    public function getLoggedUserContext(): mixed
    {
        return null;
    }

    /**
     * Returns if the user is authenticated or not
     * @return bool
     */
    public function isLogged(): bool
    {
        return $this->session->hasKey('user');
    }

    /**
     * Returns the id of the logged-in user, in this case null
     * @return mixed
     */
    public function getLoggedUserId(): mixed
    {
        return null;
    }
}
