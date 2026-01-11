<?php

namespace Fir\Libraries;

class Session {
    
    /**
     * Check if a session variable exists.
     *
     * @param string $name The name of the session variable.
     * @return bool True if the session variable exists, false otherwise.
     */
    public static function exists(string $name): bool {
        return isset($_SESSION[$name]);
    }
    
    /**
     * Set a session variable.
     *
     * @param string $name The name of the session variable.
     * @param mixed $value The value of the session variable.
     * @return mixed The assigned value.
     */
    public static function put(string $name, mixed $value): mixed
    {
        return $_SESSION[$name] = $value;
    }

    /**
     * Get the value of a session variable.
     *
     * @param string $name The name of the session variable.
     * @return mixed|null The value of the session variable, or null if it doesn't exist.
     */
    public static function get(string $name): mixed
    {
        if (self::exists($name)) {
            return $_SESSION[$name];
        }
        return null;
    }

    /**
     * Delete a session variable.
     *
     * @param string $name The name of the session variable to delete.
     * @return void
     */
    public static function delete(string $name): void
    {
        if (self::exists($name)) {
            unset($_SESSION[$name]);
        }
    }

    /**
     * Set multiple session variables at once.
     *
     * @param array $data An associative array of session key-value pairs.
     * @return void
     */
    public static function putMultiple(array $data): void
    {
        foreach ($data as $key => $value) {
            $_SESSION[$key] = $value;
        }
    }
}
