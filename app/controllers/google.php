<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\controllers;

use KenDeNigerian\Krak\core\Controller;

use Exception;
use KenDeNigerian\Krak\helpers\emailhelper;
use Google_Client;
use Google_Service_Oauth2;

class google extends Controller
{
    /**
     * Constructor
     */
    public function __construct($db, $url)
    {
        parent::__construct($db, $url); // Call the parent constructor to initialize the $db property

        // Use the Maintenance model to check if the site is in maintenance mode
        $maintenanceModel = $this->model('Maintenance');
        $underMaintenance = $maintenanceModel->underMaintenance();

        if ($underMaintenance['maintenance_mode'] == 1) {
            redirect('maintenance');
        }
    }

    public function index(): array
    {
        $data = [];

        // Initialize libraries and models
        $user = $this->library('User');
        $session = $this->library('Session');
        $userModel = $this->model('User');

        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();

        // Fetch the email template with id = 18
        $data['email-templates'] = $settingsModel->getEmailTemplate();
        $otpTemplate = $data['email-templates'][18] ?? null;

        try {

            // Redirect to user dashboard if logged in
            if ($user->isLoggedIn()) {
                if ($data['settings']["twofa_status"] == 1) {
                    if ($user->data()["twofactor_flag"] == 1) {
                        redirect('twofa');
                    }else{
                        redirect('user/dashboard');
                    }
                }else{
                    redirect('user/dashboard');
                }
            }

            // Initialize the Google Client
            $client = new Google_Client();
            $client->setClientId(getenv('GOOGLE_CLIENT_ID'));
            $client->setClientSecret(getenv('GOOGLE_CLIENT_SECRET'));
            $client->setRedirectUri(getenv('REDIRECT_URI'));
            $client->addScope('profile');
            $client->addScope('email');

            // Check if the access token is already set
            if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
                $client->setAccessToken($_SESSION['access_token']);
            }

            // If the access token is expired or not set, obtain a new one
            if (!$client->getAccessToken() || $client->isAccessTokenExpired()) {
                // If the user isn't authenticated, redirect them to the authentication page
                if (!isset($_GET['code'])) {
                    $auth_url = $client->createAuthUrl();
                    header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
                    exit; // Important to stop script execution after redirection
                } else {
                    // Handle the authentication callback
                    // If authentication was successful, exchange the authorization code for an access token
                    $accessToken = $client->fetchAccessTokenWithAuthCode($_GET['code']);
                    $_SESSION['access_token'] = $accessToken;

                    // Set the access token on the client
                    $client->setAccessToken($accessToken);
                }
            }

            // Use the access token to fetch user information
            $oauth2 = new Google_Service_Oauth2($client);
            $user_info = $oauth2->userinfo->get();

            $hasEmail = $userModel->hasEmail($user_info->email);

            if ($hasEmail) {
                $userData = $userModel->getEmail($user_info->email);
                $session->put('waveUser', $userData["userid"]);

                // Check if two-factor authentication is enabled
                if ($data['settings']["twofa_status"] == 1) {
                    if ($userData["twofactor_status"] == 1) {

                        // Generate new verification code
                        $code = mt_rand(100000, 999999);

                        // store code
                        $update = $userModel->storeOtp($userData["userid"], $code); 

                        // if stored successfully
                        if ($update === 1) {

                            // check if email notification is enabled
                            if ($data['settings']["email_notification"] == 1) {

                                $siteName = $data['settings']['sitename'];
                                $siteLogo = $data['settings']['logo'];
                                $siteUrl = getenv('URL_PATH');
                                $dateNow = date('Y');
                                                        
                                // otp template is enabled
                                if ($otpTemplate !== null && $otpTemplate['status'] == 1) {

                                    $verifyCode = sprintf('%06d', $code);

                                    $otpTemplate['body'] = str_replace(
                                        ['{VERIFY_CODE}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'],
                                        [$verifyCode, $siteName, $siteLogo, $siteUrl, $dateNow],
                                        $otpTemplate['body']
                                    );

                                    $recipientEmail = $userData["email"];
                                    $subject = $otpTemplate['subject'];
                                    $body = $otpTemplate['body'];
                                
                                    // if email was sent successfully redirect to auth page
                                    if (emailhelper::sendEmail($data['settings'], $recipientEmail, $subject, $body)) {
                                        redirect('twofa');
                                    } else {
                                        $_SESSION['message'][] = ['error', 'We failed to send otp email.'];
                                    }
                                }else{
                                    // email notification is disabled
                                    $_SESSION['message'][] = ['error', 'Can\'t proceed, email notifications are disabled.'];
                                }
                            }else{
                                // email notification is disabled
                                $_SESSION['message'][] = ['error', 'Can\'t proceed, email notifications are disabled.'];
                            }
                        } else {
                            $_SESSION['message'][] = ['error', 'Error sending code. Please try again later.'];
                        }
                    }else{
                        // Redirect to the user dashboard
                        redirect('user/dashboard');
                    }
                }else{
                    // Redirect to the user dashboard
                    redirect('user/dashboard');
                } 
            }

            // Display user information
            $data['user'] = $user_info;

            if ($data['settings']['register_status'] == 2) {
                $_SESSION['message'][] = ['error', 'Sorry, registrations are currently disabled.'];
                redirect('login');
            }

            // Return content for rendering
            return ['content' => $this->view->render($data, 'auth/google')];
        } catch (Exception $e) {
            // Log error
            error_log('Error: ' . $e->getMessage());
            
            // Redirect to error page or display error message
            $_SESSION['message'][] = ['error', 'An error occurred. Please try again later.'];
            redirect('register');
        }

        return [];
    }
}