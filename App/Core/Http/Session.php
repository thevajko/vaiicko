<?php

namespace App\Core\Http;

class Session
{
    private array $sessionData;

    public function __construct(&$sessionData)
    {
        $this->sessionData = &$sessionData;
    }

    /**
     * Gets value from session
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
     * Set session value
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set(string $key, mixed $value): void
    {
        $this->sessionData[$key] = $value;
    }

    /**
     * Clears session value, if key is specified, only specific key will be cleared.
     * If key is not specified, whole session will be destroyed.
     * @param string|null $key
     * @return void
     */
    public function clear(?string $key = null): void
    {
        if ($key != null) {
            unset($this->sessionData[$key]);
        } else {
            session_unset();
            session_destroy();
        }
    }
}
