<?php

/**
 * Global helper functions available in the whole application (mainly in views).
 */

if (!function_exists('e')) {
    /**
     * Escapes a value for safe output in HTML text or in a quoted HTML attribute (protection against XSS).
     *
     * Use it every time you print data in a view: <?= e($value) ?>. Do not use it for HTML that is intentionally
     * prepared by the application (e.g. $contentHTML in layouts).
     *
     * Note: this is not sufficient for other contexts - for URLs from users, validate the scheme (http/https only);
     * for data inserted into <script>, use json_encode() with JSON_HEX_* flags.
     *
     * @param mixed $value Value to escape (null is converted to an empty string).
     * @return string Escaped string.
     */
    function e(mixed $value): string
    {
        return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, 'UTF-8');
    }
}
