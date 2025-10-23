<?php

namespace Framework\Core;

/**
 * Interface IAuthenticator
 *
 * This interface defines the necessary methods for implementing authentication functionality within the application.
 * Classes that implement this interface will provide the capability to log users in and out, as well as retrieve
 * user-specific information such as their identity and context.
 *
 * @property-read \Framework\Core\IIdentity|null $user Associated authenticated identity object (or null if not logged in).
 *
 * @package App\Core
 */
interface IAuthenticator
{
    /**
     * Logs in a user with the specified credentials.
     *
     * This method attempts to authenticate a user using the provided login and password. It returns true if the
     * authentication is successful, and false otherwise.
     *
     * @param string $username The user's login identifier (e.g., username or email).
     * @param string $password The user's password.
     * @return bool True if the login was successful; false otherwise.
     */
    public function login(string $username, string $password): bool;

    /**
     * Logs out the currently authenticated user.
     *
     * This method terminates the user's session or invalidates their authentication token, effectively logging them out
     * from the application.
     *
     * @return void
     */
    public function logout(): void;

    /**
     * Checks if a user is currently logged in.
     *
     * This method returns true if there is an authenticated user, and false otherwise. It provides a straightforward
     * way to determine the authentication state within the application.
     *
     * @return bool True if a user is logged in; false otherwise.
     */
    public function isLogged(): bool;

    /**
     * Returns the associated authenticated user object, if available.
     *
     * @return IIdentity|null The identity object for the logged-in user, or null if not authenticated.
     */
    public function getUser(): ?IIdentity;
}
