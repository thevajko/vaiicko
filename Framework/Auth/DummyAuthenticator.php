<?php

namespace Framework\Auth;

use Exception;
use Framework\Core\App;
use Framework\Core\IAuthenticator;
use Framework\Core\IIdentity;
use Framework\Http\Session;
use App\Models\User;

/**
 * Class DummyAuthenticator
 * A basic implementation of user authentication using hardcoded credentials.
 *
 * @package App\Auth
 * @property-read User|null $user Associated authenticated user object (or null if not logged in).
 */
class DummyAuthenticator implements IAuthenticator
{
    // Hardcoded username for authentication
    public const LOGIN = "admin";
    // Hash of the password "admin"
    public const PASSWORD_HASH = '$2y$10$GRA8D27bvZZw8b85CAwRee9NH5nj4CQA6PDFMc90pN9Wi4VAWq3yq';
    // Display name for the logged-in user
    public const USERNAME = "Admin";
    // Application instance
    private App $app;
    // Session management instance
    private Session $session;
    // Cached authenticated user instance (nullable when not logged in)
    private ?IIdentity $user = null;

    /**
     * DummyAuthenticator constructor.
     *
     * @param App $app Instance of the application for accessing session and other services.
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->session = $this->app->getSession();
    }

    /**
     * Authenticates a user based on hardcoded login and password.
     *
     * @param string $username User's login attempt.
     * @param string $password User's password attempt.
     * @return bool Returns true if authentication is successful; false otherwise.
     */
    public function login(string $username, string $password): bool
    {
        // Check if the provided login and password match the hardcoded credentials
        if ($username == self::LOGIN && password_verify($password, self::PASSWORD_HASH)) {
            $this->user = new User(id: null, username: self::LOGIN, name: self::USERNAME);
            // Store the entire User object in the session
            $this->session->set('user', $this->user);
            return true;
        }
        return false; // Authentication failed
    }

    /**
     * Logs out the user by destroying the session.
     *
     * @return void
     */
    public function logout(): void
    {
        $this->user = null;
        $this->session->destroy(); // Destroy the session to log out the user
    }

    /**
     * Checks if the user is currently authenticated.
     */
    public function isLogged(): bool
    {
        return $this->getUser() instanceof IIdentity;
    }

    /**
     * Returns the associated authenticated user object, if available.
     *
     * @return IIdentity|null The user object for the logged-in user, or null if not authenticated.
     * @throws Exception
     */
    public function getUser(): ?IIdentity
    {
        if ($this->user instanceof IIdentity) {
            return $this->user;
        }

        $sessionValue = $this->session->get('user');

        // Upgrade legacy string session value to a User object
        if (is_string($sessionValue) && $sessionValue !== '') {
            $this->user = new User(id: null, username: self::LOGIN, name: $sessionValue);
            $this->session->set('user', $this->user);
            return $this->user;
        }

        if ($sessionValue instanceof User || $sessionValue instanceof IIdentity) {
            $this->user = $sessionValue;
            return $this->user;
        }

        return null;
    }

    /**
     * Magic getter to support property-style access `$auth->user`.
     *
     * @param string $name The property name being accessed.
     * @return mixed The value of the requested property.
     * @throws Exception
     */
    public function __get(string $name): mixed
    {
        if ($name === 'user') {
            return $this->getUser();
        }
        return null;
    }
}
