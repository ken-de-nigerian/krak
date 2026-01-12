<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\libraries;

/**
 * Cookie handling class
 */
class Cookie {
    
    public static function exists($name): bool {
        return isset($_COOKIE[$name]);
    }
    
    public static function get($name) {
        return $_COOKIE[$name];
    }
    
    public static function put($name, $value, $expiry): bool {
        // Set domain to empty string (current domain) and secure to true for HTTPS
        if (setcookie($name, $value, time() + $expiry, '/', '', true, true)) {
            return true;
        }
        return false;
    }
    
    public static function delete($name): void {
        self::put($name, '', time() - 1);
    }
}