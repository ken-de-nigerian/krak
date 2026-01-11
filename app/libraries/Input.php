<?php

namespace Fir\Libraries;

use InvalidArgumentException;

class Input {
    
    /**
     * Check if input data exists
     *
     * @param string $type The type of input ('post' or 'get')
     * @return bool
     * @throws InvalidArgumentException If an invalid input type is provided
     */
    public static function exists(string $type = 'post'): bool
    {
        if (!in_array($type, ['post', 'get'])) {
            throw new InvalidArgumentException("Invalid input type specified: $type");
        }

        return match ($type) {
            'post' => !empty($_POST),
            'get' => !empty($_GET),
        };
    }

    /**
     * Sanitize input data
     *
     * @param mixed $data The input data to sanitize
     * @return array|string The sanitized input data
     */
    public static function sanitize_input(mixed $data): array|string
    {
        if (is_array($data)) {
            // If $data is an array, recursively sanitize each element
            return array_map('self::sanitize_input', $data);
        } else {
            // If $data is a string, sanitize it
            $data = trim($data);
            $data = stripslashes($data);
            return htmlspecialchars($data);
        }
    }
    
    /**
     * Retrieve input value by name
     *
     * @param string $item The name of the input field
     * @return array|string The sanitized value of the input field or an empty string if not found
     */
    public static function get(string $item): array|string
    {
        if (isset($_POST[$item])) {
            return self::sanitize_input($_POST[$item]);
        } elseif (isset($_GET[$item])) {
            return self::sanitize_input($_GET[$item]);
        }
        return '';  
    }
    
    /**
     * Get the value of a given parameter from the URL
     *
     * @param string $param The parameter name
     * @return array|false|string The value of the parameter if found, false otherwise
     */
    public static function param(string $param): bool|array|string
    {
        if (isset($_GET['url'])) {
            $url = explode('/', rtrim($_GET['url'], '/'));

            $pId = array_search($param, $url);

            if ($pId !== false && isset($url[$pId + 1])) {
                return self::sanitize_input($url[$pId + 1]);
            }
        }
        return false;
    }

    /**
     * Check if the current request is an AJAX request
     *
     * @return bool
     */
    public static function isAjax(): bool
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
}