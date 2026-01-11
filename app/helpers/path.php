<?php

/**
 * @param null $path
 * @return string
 */
function public_path($path = null): string
{
    return __DIR__ . '/../../' . PUBLIC_PATH . $path;
}

/**
 * @param null $path
 * @return string
 */
function uploads_path($path = null): string
{
    return public_path() . '/' . UPLOADS_PATH . $path;
}