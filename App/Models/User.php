<?php

namespace App\Models;

use Framework\Core\IIdentity;

/**
 * Simple User value object representing an authenticated user.
 */
class User implements IIdentity
{
    public function __construct(
        public ?int $id = null,
        public string $login = '',
        public string $name = ''
    ) {
    }
}
