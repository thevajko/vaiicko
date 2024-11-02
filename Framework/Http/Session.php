<?php

namespace Framework\Http;

/**
 * Class Session
 *
 * This class provides a simple interface for managing user session data in a PHP application. It abstracts the use
 * of PHP's built-in session management functions, allowing for easy storage, retrieval, and manipulation of session
 * variables throughout the user's session lifecycle.
 *
 * Upon instantiation, the class starts a new session or resumes the existing session, providing a reference to the
 * global `$_SESSION` array. It offers methods to set, get, remove, and check for session variables, making it
 * straightforward to manage user data such as authentication status, user preferences, and any other information
 * that should persist across requests.
 *
 * @package App\Core\Http
 */
class Session
{
    private ?array $sessionData = null;

    /**
     * Initializes a session.
     *
     * This constructor starts a new session or resumes the current one by calling `session_start()`. It also creates
     * a reference to the global `$_SESSION` array, allowing for easy access and manipulation of session data.
     */
    public function __construct()
    {
        session_start();
        $this->sessionData = &$_SESSION;
    }

    /**
     * Retrieves a value from the session.
     *
     * This method checks for the specified key in the session data. If the key exists, its value is returned; if not,
     * the provided default value is returned.
     *
     * @param string $key The key of the session variable to retrieve.
     * @param mixed|null $default The value to return if the session key does not exist.
     * @return mixed The session variable's value or the default value.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->sessionData[$key] ?? $default;
    }

    /**
     * Sets a session variable.
     *
     * This method assigns a value to the specified session key. If the key does not exist, it will be created; if it
     * does, the existing value will be updated.
     *
     * @param string $key The key for the session variable.
     * @param mixed $value The value to set for the session variable.
     * @return void
     */
    public function set(string $key, mixed $value): void
    {
        $this->sessionData[$key] = $value;
    }

    /**
     * Removes a session variable.
     *
     * This method deletes the specified session variable from the session data. If the key does not exist, no action
     * is taken.
     *
     * @param string|null $key The key of the session variable to remove.
     * @return void
     */
    public function remove(string $key): void
    {
        unset($this->sessionData[$key]);
    }

    /**
     * Checks if a session variable exists.
     *
     * This method checks whether the specified key is present in the session data, returning true if it exists and
     * false otherwise.
     *
     * @param string $key The session variable key to check for existence.
     * @return bool True if the session variable exists; false otherwise.
     */
    public function hasKey(string $key): bool
    {
        return isset($this->sessionData[$key]);
    }

    /**
     * Clears all session variables.
     *
     * This method removes all session variables without destroying the session itself.
     *
     * @return bool True on success; false on failure.
     */
    public function clear(): bool
    {
        return session_unset();
    }

    /**
     * Destroys the current session.
     *
     * This method unsets all session variables, deletes the session cookie (if it exists), and then destroys
     * the session on the server side to free up resources.
     *
     * @return bool True on success; false on failure.
     */
    public function destroy(): bool
    {
        session_unset();

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000, // Expire the cookie
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        return session_destroy();
    }
}
