<?php

declare(strict_types=1);

/**
 * Convert special characters to HTML entities.
 *
 * @param string|null $string The string to be escaped.
 * @return string The escaped string, or an empty string if input is null.
 */
if (!function_exists('e')) {
    function e(?string $string): string
    {
        if ($string === null) {
            return '';
        }
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
}

/**
 * Limit the number of characters in a string and provide an excerpt.
 *
 * @param   string $string The input string.
 * @param   int $start The starting index.
 * @param   int|null $length The maximum length of the substring.
 * @param   array $options Additional options:
 *                         - 'Ellipsis': The string to append if the string is truncated.
 * @return  string The limited string.
 */
if (!function_exists('str_limit')) {
    function str_limit(string $string, int $start = 0, ?int $length = null, array $options = []): string
    {
        // Get the substring
        $substring = mb_substr($string, $start, $length);

        // Check if the string was truncated
        if (mb_strlen($string) > $length && $length !== null) {
            // Append ellipsis if provided
            $ellipsis = $options['ellipsis'] ?? '...';
            $substring .= $ellipsis;
        }

        return $substring;
    }
}

