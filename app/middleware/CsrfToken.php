<?php

namespace Fir\Middleware;

/**
 * Class CsrfToken ensures that all POST requested have a valid CSRF Token
 */
class CsrfToken
{

    public function __construct()
    {
        $this->generateToken();
        $this->validateToken();
    }

    /**
     * Generate and set a random token
     */
    private function generateToken(): void
    {
        // If there isn't any sessions set, or if the session is empty
        if (empty($_SESSION['token_id'])) {
            // Generate a random session token
            $token_id = hash('sha256', substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 10));

            // Store the token in the session
            $_SESSION['token_id'] = $token_id;
        }
    }

    /**
     * Validate the CSRF token
     */
    private function validateToken(): void
    {
        // Check if POST data exists
        if (!empty($_POST)) {
            // Check if the 'token_id' parameter is set and matches the session token
            if (!isset($_POST['token_id']) || $_POST['token_id'] != $_SESSION['token_id']) {
                // If the token doesn't match, return an error response and exit
                $response = [
                    'status' => 'error',
                    'message' => 'CsrfToken validation failed. Please refresh the page and try again.',
                ];

                // Send JSON response
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }
        }
    }
}