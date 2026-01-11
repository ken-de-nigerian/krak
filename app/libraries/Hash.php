<?php

namespace Fir\Libraries;

use RuntimeException;

/**
 * Class Hash
 */
class Hash {

    public static function make($string, $salt = ''): string
    {
        return hash('sha256', $string . $salt);
    }

    public static function salt($length): string
    {
        // Generate a random string of bytes
        $bytes = openssl_random_pseudo_bytes($length, $crypto_strong);

        if ($crypto_strong === false) {
            throw new RuntimeException('Failed to generate random bytes.');
        }

        // Convert the binary data into hexadecimal representation
        return bin2hex($bytes);
    }

    public static function unique(): string
    {
        return self::make(uniqid());
    }
}
