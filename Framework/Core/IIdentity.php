<?php

namespace Framework\Core;

/**
 * Marker interface for identity objects returned by the authenticator.
 *
 * Implement this in application user classes (or other identity representations) so
 * the authenticator can type-hint a common return type while remaining flexible.
 */
interface IIdentity
{
    // intentionally empty - serves as a common type for authenticated identities
}

