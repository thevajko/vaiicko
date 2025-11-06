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
        public string $username = '',
        public string $name = ''
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
