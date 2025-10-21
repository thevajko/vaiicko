<?php

namespace App\Models;

/**
 * Simple User value object representing an authenticated user.
 */
class User
{
    public function __construct(
        public ?int $id = null,
        public string $login = '',
        public string $name = ''
    ) {
    }
}
