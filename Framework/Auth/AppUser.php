<?php

namespace Framework\Auth;

use Framework\Core\IIdentity;

/**
 * Class AppUser
 *
 * Represents the current application user, providing methods to check login status and retrieve identity information.
 *
 * @mixin IIdentity
 * @package Framework\Auth
 */
class AppUser
{
    private ?IIdentity $identity;

    /**
     * AppUser constructor.
     *
     * @param IIdentity|null $identity The identity of the current user, or null if not logged in.
     */
    public function __construct(?IIdentity $identity = null)
    {
        $this->identity = $identity;
    }

    /**
     * Checks if the user is currently logged in.
     *
     * @return bool True if the user is logged in; false otherwise.
     */
    public function isLoggedIn(): bool
    {
        return $this->identity !== null;
    }

    /**
     * Retrieves the identity of the current user.
     *
     * @return IIdentity|null The identity object if the user is logged in; null otherwise.
     */
    public function getIdentity(): ?IIdentity
    {
        return $this->identity;
    }

    /**
     * Retrieves the name of the current user.
     *
     * @return string|null The name of the user if logged in; null otherwise.
     */
    public function getName(): ?string
    {
        return $this->identity?->getName();
    }

    /**
     * Magic method to forward calls to the identity object if it exists.
     *
     * @param string $name The method name being called.
     * @param array $arguments The arguments passed to the method.
     * @return mixed The result of the method call on the identity object.
     * @throws \BadMethodCallException If the method does not exist on the identity.
     */
    public function __call(string $name, array $arguments)
    {
        if ($this->identity === null) {
            throw new \BadMethodCallException("Cannot call method {$name} when user is not logged in.");
        }
        if (method_exists($this->identity, $name)) {
            return $this->identity->$name(...$arguments);
        }
        throw new \BadMethodCallException("Method {$name} does not exist on current identity.");
    }
}