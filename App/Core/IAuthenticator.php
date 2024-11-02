<?php

namespace App\Core;

/**
 * Interface IAuthenticator
 *
 * This interface defines the necessary methods for implementing authentication functionality within the application.
 * Classes that implement this interface will provide the capability to log users in and out, as well as retrieve
 * user-specific information such as their identity and context.
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
     * Retrieves the name of the currently logged-in user.
     *
     * This method returns the username or identifier of the authenticated user. If no user is currently logged in,
     * it may return an empty string or null, depending on the implementation.
     *
     * @return string The name of the logged-in user.
     */
    public function getLoggedUserName(): string;

    /**
     * Retrieves the ID of the currently logged-in user.
     *
     * This method returns a unique identifier for the user who is authenticated. The return type may vary based on the
     * implementation (e.g., integer, string).
     *
     * @return mixed The ID of the logged-in user.
     */
    public function getLoggedUserId(): mixed;

    /**
     * Retrieves the context of the currently logged-in user.
     *
     * This method returns an object or data structure representing the user's context, such as a user profile or
     * permissions. The return type will depend on the specific implementation details.
     *
     * @return mixed The context of the logged-in user (e.g., a user class instance).
     */
    public function getLoggedUserContext(): mixed;

    /**
     * Checks if a user is currently logged in.
     *
     * This method returns true if there is an authenticated user, and false otherwise. It provides a straightforward
     * way to determine the authentication state within the application.
     *
     * @return bool True if a user is logged in; false otherwise.
     */
    public function isLogged(): bool;
}
