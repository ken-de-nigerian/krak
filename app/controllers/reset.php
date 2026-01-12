<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\controllers;

use KenDeNigerian\Krak\core\Controller;

use Exception;
use KenDeNigerian\Krak\helpers\emailhelper;
class reset extends Controller
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
    
    /**
     * This method handles the password reset process.
     *
     * @return array Array containing the rendered view content.
     */
    public function index(): array
    {

        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];

        // unset the sessions and redirect
        $session = $this->library('Session');
        $session->delete('access_token');

        // Load settings and email templates
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();

        // Check if the user is logged in; if yes, redirect to dashboard
        $user = $this->library('User');
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

        // Fetch the email template with id = 3
        $data['email-templates'] = $settingsModel->getEmailTemplate();
        $resetTemplate = $data['email-templates'][3] ?? null;

        // Load user model
        $userModel = $this->model('User');

        // If URL parameters are not set, redirect to the forgot password page
        if (isset($_GET['id']) && isset($_GET['reset'])) {
            // Retrieve and sanitize id and reset from URL parameters
            $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
            $reset = filter_input(INPUT_GET, 'reset', FILTER_SANITIZE_STRING);

            // Store URL parameters in the data array
            $data['url_one'] = $id;
            $data['url_two'] = $reset;
            
            // Check if the token exists
            $hasToken = $userModel->hasToken($reset);

            // If the token doesn't exist, redirect to the forgot password page
            if (!$hasToken) {
                $_SESSION['message'][] = ['error', 'Sorry, your session has expired.'];
                redirect('forgot');
            }

            // Get user details associated with the token
            $userDetails = $userModel->getwithToken($reset);

            // Load an input library
            $input = $this->library('Input');

            // Check if the token is approved (status = 1)
            $hasTokenApproved = $userModel->hasTokenApproved($reset);

            // If the token is approved, show an error message and redirect to the forgot password page
            if ($hasTokenApproved) {
                $_SESSION['message'][] = ['error', 'Sorry, the password reset link has expired.'];
                redirect('forgot');
            } else {

                // If the user submits the reset password form
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                    if ($input->exists()) {
                        // Load validator library
                        $validator = $this->library('Validator');

                        // Validate form inputs
                        $validation = $validator->check($_POST, [
                            'password' => [
                                'required' => true,
                            ],
                            'confirmPassword' => [
                                'required' => true,
                                'match' => 'password'
                            ]
                        ]);

                        // If validation fails, return error messages
                        if ($validation->fails()) {
                            $errors = $validation->errors()->all();
                            $errorMessages = [];

                            foreach ($errors as $err) {
                                foreach ($err as $r) {
                                    $errorMessages[] = $r;
                                }
                            }

                            $response = [
                                'status' => 'error',
                                'message' => $errorMessages
                            ];
                        } else {
                            try {
                                if ($input->get('password') === $input->get('confirmPassword')) {

                                    // Get the user's email address
                                    $email = $userDetails['email'];

                                    /* Hash Password */
                                    $password = password_hash($input->get('password'), PASSWORD_DEFAULT);

                                    // Update the user's password
                                    $update = $userModel->resetPassword($password, $reset, $userDetails['userid']);

                                    // If password update is successful
                                    if ($update == 1) {

                                        // Authenticate user with new password
                                        $auth = $user->login($email, $input->get('password'));

                                        // if logged in
                                        if ($auth) {

                                            // email notification is enabled
                                            if ($data['settings']["email_notification"] == 1) {

                                                // Prepare email content
                                                $siteName = $data['settings']['sitename'];
                                                $siteLogo = $data['settings']['logo'];
                                                $siteUrl = getenv('URL_PATH');
                                                $dateNow = date('Y');

                                                // reset template is enabled
                                                if ($resetTemplate !== null && $resetTemplate['status'] == 1) {

                                                    $resetTemplate['body'] = str_replace(['{FIRSTNAME}', '{LASTNAME}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'], [$userDetails["firstname"], $userDetails["lastname"], $siteName, $siteLogo, $siteUrl, $dateNow], $resetTemplate['body']);

                                                    // Send email with success notification to user
                                                    $recipientEmail = $email;
                                                    $subject = $resetTemplate['subject'];
                                                    $body = $resetTemplate['body'];
                                            
                                                    // If email is sent successfully
                                                    if (emailhelper::sendEmail($data['settings'], $recipientEmail, $subject, $body)) {
                                                        $response = [
                                                            'status' => 'success',
                                                            'redirect' => 'user/dashboard'
                                                        ];
                                                    } else {
                                                        $response = [
                                                            'status' => 'warning',
                                                            'message' => 'The password reset was successful, but we were unable to send you a notification.',
                                                            'redirect' => 'user/dashboard'
                                                        ];
                                                    }
                                                }else{
                                                    $response = [
                                                        'status' => 'success',
                                                        'redirect' => 'user/dashboard'
                                                    ];
                                                }
                                            }else{
                                                $response = [
                                                    'status' => 'success',
                                                    'redirect' => 'user/dashboard'
                                                ];
                                            }
                                        }else{
                                            $response = [
                                                'status' => 'error',
                                                'message' => 'Invalid email or password. Please try again.'
                                            ];
                                        }
                                    } else {
                                        $response = [
                                            'status' => 'error',
                                            'message' => 'There was an error while saving your new password.'
                                        ];
                                    }
                                } else {
                                    $response = [
                                        'status' => 'error',
                                        'message' => 'Your passwords mismatched. Try again.'
                                    ];
                                }
                            } catch (Exception $e) {
                                $response = [
                                    'status' => 'error',
                                    'message' => $e->getMessage()
                                ];
                            }
                        }

                        // Send the JSON response
                        header('Content-Type: application/json');
                        echo json_encode($response);
                        exit;
                    }
                }
            }

            return ['content' => $this->view->render($data, 'auth/reset-password')];
        }else{
            redirect('forgot');
        }

        // return an empty array
        return [];
    }
}
