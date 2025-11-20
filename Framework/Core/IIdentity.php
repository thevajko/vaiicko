<?php

namespace Framework\Core;

/**
 * Interface IIdentity
 *
 * Represents a user identity with a method to retrieve the user's name.
 * Other methods can be added as needed to extend the identity functionality.
 *
 * @package Framework\Core
 */
interface IIdentity
{
    public function getName(): string;
}

