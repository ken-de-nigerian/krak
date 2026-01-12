<?php

declare(strict_types=1);

/**
 * Redirect to an internal route
 *
 * @param string|null $path   The internal path to redirect
 */
function redirect(string $path = null): void
{
    header('Location: ' . URL_PATH . '/' . $path);

    /**
     * Exit is required to stop executing any extra code after the redirect call
     * It also allows passing on session variables to the redirected page by preventing extra code to be executed
     */
    exit;
}