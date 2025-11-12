<?php

namespace Framework\Core;

use Framework\Auth\AppUser;

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
     * @return bool True if authentication is successful; false otherwise.
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
     * Retrieves the current user.
     */
    public function getUser(): AppUser;
}
