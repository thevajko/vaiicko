<?php

namespace App\Core\Http;

class Session
{
    private ?array $sessionData = null;

    /**
     * Starts a new session or opens the current session
     */
    public function __construct()
    {
        session_start();
        $this->sessionData = &$_SESSION;
    }

    /**
     * Gets value from session variable
     * @param string $key
     * @param mixed|null $default Default value, if session not exists
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed
    {
        if (isset($this->sessionData[$key])) {
            return $this->sessionData[$key];
        }
        return $default;
    }

    /**
     * Set session value if variable exist, if not, create a new variable
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set(string $key, mixed $value): void
    {
        $this->sessionData[$key] = $value;
    }

    /**
     * Removes session variable
     * @param string|null $key
     * @return void
     */
    public function remove(string $key): void
    {
        if (isset($this->sessionData[$key])) {
            unset($this->sessionData[$key]);
        }
    }

    /**
     * Return true, if $key exists in session, otherwise returns false
     * @param string $key
     * @return bool
     */
    public function hasKey(string $key): bool
    {
        return isset($this->sessionData[$key]);
    }

    /**
     * Clears all session variables
     * @return void
     */
    public function clear(): bool
    {
        return session_unset();
    }

    /**
     * Destroys session properly
     * @return bool
     */
    public function destroy(): bool
    {
        session_unset();

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        return session_destroy();
    }
}
