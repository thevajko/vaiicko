<?php

namespace Framework\Support;

/**
 * Provides filesystem path helpers.
 */
final class PathResolver
{
    // Prevent instantiation
    private function __construct()
    {
    }

    /**
     * Attempts to resolve the given relative path under the provided base directory
     * in a case-insensitive manner.
     *
     * @param string $baseDir The base directory to start from.
     * @param string $relativePath The relative path to resolve.
     * @return string|null The resolved absolute path if found, or null if not found.
     */
    public static function resolveCaseInsensitive(string $baseDir, string $relativePath): ?string
    {
        // Reject path traversal attempts
        if (str_contains($relativePath, '..')) {
            return null;
        }

        $segments = array_filter(
            explode(DIRECTORY_SEPARATOR, $relativePath),
            static fn($part) => $part !== ''
        );
        $current = $baseDir;

        foreach ($segments as $segment) {
            if (!is_dir($current) || !is_readable($current)) {
                return null;
            }

            $entries = scandir($current);
            if ($entries === false) {
                return null;
            }

            $match = null;
            foreach ($entries as $entry) {
                if (strcasecmp($entry, $segment) === 0) {
                    $match = $entry;
                    break;
                }
            }

            if ($match === null) {
                return null;
            }

            $current .= DIRECTORY_SEPARATOR . $match;
        }

        // Ensure resolved path is within the base directory
        $baseDirReal = realpath($baseDir);
        $currentReal = realpath($current);
        if ($baseDirReal === false || $currentReal === false) {
            return null;
        }

        $baseDirReal = rtrim($baseDirReal, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        if (strpos($currentReal, $baseDirReal) !== 0 && $currentReal !== rtrim($baseDirReal, DIRECTORY_SEPARATOR)) {
            return null;
        }

        return $currentReal;
    }
}

