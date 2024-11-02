<?php

namespace Framework\Auth;

use Exception;
use Framework\Core\App;
use Framework\Core\IAuthenticator;
use Framework\Http\Session;

/**
 * Class DummyAuthenticator
 * A basic implementation of user authentication using hardcoded credentials.
 * @package App\Auth
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
            $this->session->set('user', self::USERNAME); // Store the username in the session
            return true; // Authentication successful
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
        $this->session->destroy(); // Destroy the session to log out the user
    }

    /**
     * Retrieves the name of the currently logged-in user.
     *
     * @return string The name of the logged-in user.
     * @throws Exception If no user is logged in.
     */
    public function getLoggedUserName(): string
    {
        // Check if the user key exists in the session
        return $this->session->hasKey('user') ?
            $this->session->get('user') :
            throw new Exception("User not logged in"); // Throw an exception if no user is logged in
    }

    /**
     * Retrieves additional context for the logged-in user (currently returns null).
     *
     * @return mixed Contextual information about the logged-in user.
     */
    public function getLoggedUserContext(): mixed
    {
        return null; // No additional context is provided in this implementation
    }

    /**
     * Checks if the user is currently authenticated.
     *
     * @return bool Returns true if the user is logged in; false otherwise.
     */
    public function isLogged(): bool
    {
        return $this->session->hasKey('user'); // Return true if user key exists in session
    }

    /**
     * Retrieves the ID of the currently logged-in user (always returns null in this implementation).
     *
     * @return mixed Returns null, as no user ID is tracked in this implementation.
     */
    public function getLoggedUserId(): mixed
    {
        return null; // No user ID is available in this implementation
    }
}
