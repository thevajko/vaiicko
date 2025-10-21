<?php

namespace App\Models;

/**
 * Simple User value object representing an authenticated user.
 */
class User
{
    public ?int $id;
    public string $login;
    public string $name;

    public function __construct(?int $id, string $login, string $name)
    {
        $this->id = $id;
        $this->login = $login;
    }
}

