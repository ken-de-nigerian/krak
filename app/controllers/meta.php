<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\controllers;

use KenDeNigerian\Krak\core\Controller;

use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Exception;
use KenDeNigerian\Krak\helpers\emailhelper;
class meta extends Controller
{
    /**
     * Constructor
     */
    public function __construct($db, $url)
    {
        parent::__construct($db, $url); // Call the parent constructor to initialize the $db property

        // Check if the site is in maintenance mode
        $underMaintenance = $this->model('Maintenance')->underMaintenance();

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

        // Fetch settings
        $data['settings'] = $settingsModel->get();
        $otpTemplate = $settingsModel->getEmailTemplate()[18] ?? null;

        try {
            // Redirect to user dashboard if logged in
            if ($user->isLoggedIn()) {
                if ($data['settings']["twofa_status"] == 1) {
                    if ($user->data()["twofactor_flag"] == 1) {
                        redirect('twofa');
                    } else {
                        redirect('user/dashboard');
                    }
                } else {
                    redirect('user/dashboard');
                }
            }

            // Initialize Facebook SDK
            $fb = new Facebook([
                'app_id' => getenv('FACEBOOK_APP_ID'),
                'app_secret' => getenv('FACEBOOK_APP_SECRET'),
                'default_graph_version' => 'v18.0',
            ]);

            $helper = $fb->getRedirectLoginHelper();

            // Set state param if exists
            $state = $_GET['state'] ?? null;
            if ($state !== null) {
                $helper->getPersistentDataHandler()->set('state', $state);
            }

            // Check if the access token is already set
            $accessToken = null;
            if (isset($_SESSION['access_token'])) {
                $accessToken = $_SESSION['access_token'];
            }

            // If the access token is expired or not set, obtain a new one
            if ($accessToken === null || !$accessToken->isLongLived()) {
                $accessToken = $helper->getAccessToken();
                $_SESSION['access_token'] = $accessToken;
            }

            // If the user isn't authenticated, redirect them to the authentication page
            if ($accessToken === null) {
                $loginUrl = $helper->getLoginUrl(getenv('FACEBOOK_REDIRECT_URI'), ['email']);
                header('Location: ' . filter_var($loginUrl, FILTER_SANITIZE_URL));
                exit; // Important to stop script execution after redirection
            }else{

                // Set the access token on the client
                $fb->setDefaultAccessToken($accessToken);

                // Get user data from Facebook
                $response = $fb->get('/me?fields=name,email,picture');
                $userData = $response->getGraphUser();
                
                // Get full name
                $fullName = $userData->getName();

                // Split full name into first name and last name
                $nameParts = explode(" ", $fullName);
                $firstName = $nameParts[0]; // First name
                $lastName = $nameParts[1] ?? ''; // Last name (if available)

                // Set firstname, lastname and email
                $data['user']['firstname'] = $firstName;
                $data['user']['lastname'] = $lastName;
                $data['user']['email'] = $userData->getEmail();

                // Check if user email exists
                $hasEmail = $userModel->hasEmail($data['user']['email']);

                if ($hasEmail) {
                    $user = $userModel->getEmail($data['user']['email']);
                    $session->put('waveUser', $user["userid"]);

                    if ($data['settings']["twofa_status"] == 1 && $user["twofactor_status"] == 1) {
                        $code = mt_rand(100000, 999999);
                        $update = $userModel->storeOtp($user["userid"], $code);

                        if ($update === 1) {
                            if ($data['settings']["email_notification"] == 1) {
                                if ($otpTemplate !== null && $otpTemplate['status'] == 1) {
                                    $verifyCode = sprintf('%06d', $code);
                                    $body = str_replace(
                                        ['{VERIFY_CODE}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'],
                                        [$verifyCode, $data['settings']['sitename'], $siteLogo = $data['settings']['logo'], getenv('URL_PATH'), date('Y')],
                                        $otpTemplate['body']
                                    );
                                    $subject = $otpTemplate['subject'];
                                    $recipientEmail = $user["email"];

                                    if (emailhelper::sendEmail($data['settings'], $recipientEmail, $subject, $body)) {
                                        redirect('twofa');
                                    } else {
                                        $_SESSION['message'][] = ['error', 'Failed to send OTP email.'];
                                    }
                                } else {
                                    $_SESSION['message'][] = ['error', 'Can\'t proceed, email notifications are disabled.'];
                                }
                            } else {
                                $_SESSION['message'][] = ['error', 'Can\'t proceed, email notifications are disabled.'];
                            }
                        } else {
                            $_SESSION['message'][] = ['error', 'Error sending code. Please try again later.'];
                        }
                    } else {
                        redirect('user/dashboard');
                    }
                }

                if ($data['settings']['register_status'] == 2) {
                    $_SESSION['message'][] = ['error', 'Registrations are currently disabled.'];
                    redirect('login');
                }

                // Return content for rendering
                return ['content' => $this->view->render($data, 'auth/facebook')];
            }
        } catch (FacebookResponseException | FacebookSDKException | Exception $e) {
            // Log error
            error_log('Facebook SDK error: ' . $e->getMessage());
            // Redirect to error page or display error message
            $_SESSION['message'][] = ['error', 'An error occurred. Please try again later.'];
            redirect('register');
        }

        return [];
    }
}