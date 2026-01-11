<?php

namespace Fir\Controllers;

use Exception;
use Fir\Helpers\EmailHelper;

class Forgot extends Controller 
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

    // Method to handle forgot password functionality
    public function index()
    {
        // Initialize an empty data array
        $data = [];

        // unset the sessions and redirect
        $session = $this->library('Session');
        $session->delete('access_token');

        // Load the necessary libraries and models
        $user = $this->library('User');
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();

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

        // Get email templates from the settings model
        $data['email-templates'] = $settingsModel->getEmailTemplate();
        $forgotTemplate = $data['email-templates'][2] ?? null;

        // Load an input library
        $input = $this->library('Input');

        // Load user model
        $userModel = $this->model('User');

        // Handle POST request
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $validator = $this->library('Validator');
            // Validate email input
            $validation = $validator->check($_POST, [
                'email' => ['required' => true, 'email' => true]
            ]);

            // If validation fails, return error messages
            if ($validation->fails()) {
                $errors = $validation->errors()->all();
                $errorMessages = [];

                foreach ($errors as $err) {
                    $errorMessages[] = $err;
                }

                $response = [
                    'status' => 'error',
                    'message' => $errorMessages
                ];
            } else {
                try {
                    // Get email from input
                    $email = $input->get('email');

                    // Generate a unique reset code
                    $code = $this->uniqueid();

                    // Get user details associated with email
                    $user = $userModel->getEmail($email);

                    // if user exisits
                    if ($user) {
                        // Store the reset code
                        $update = $userModel->storeResetCode($user['userid'], $code);

                        // update was successful
                        if ($update == 1) {

                            // Encode user ID
                            $id = base64_encode($user["userid"]);
                            
                            // Format reset code
                            $resetCode = sprintf('%06d', $code);

                            // email notification is enabled
                            if ($data['settings']["email_notification"] == 1) {

                                $siteName = $data['settings']['sitename'];
                                $siteLogo = $data['settings']['logo'];
                                $siteUrl = getenv('URL_PATH');
                                $dateNow = date('Y');

                                // forgot template is enabled
                                if ($forgotTemplate !== null && $forgotTemplate['status'] == 1) {

                                    // Replace placeholders in email template with actual values
                                    $forgotTemplate['body'] = str_replace(['{CODE}', '{ID}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'], [$resetCode, $id, $siteName, $siteLogo, $siteUrl, $dateNow], $forgotTemplate['body']);

                                    $recipientEmail = $email;
                                    $body = $forgotTemplate['body'];
                                    $subject = $forgotTemplate['subject'];
                            
                                    // Send email
                                    if (EmailHelper::sendEmail($data['settings'], $recipientEmail, $subject, $body)) {
                                        $response = [
                                            'status' => 'success',
                                            'message' => 'If your email exists in our system, we\'ve just sent you a reset link',
                                            'redirect' => 'forgot'
                                        ];
                                    } else {
                                        $response = [
                                            'status' => 'error',
                                            'message' => 'We were unable to send the password reset link. Please try again.'
                                        ];
                                    }
                                }else{
                                    $response = [
                                        'status' => 'error',
                                        'message' => 'We were unable to send the password reset link. Please try again.'
                                    ];
                                }
                            }else{
                                $response = [
                                    'status' => 'error',
                                    'message' => 'We were unable to send the password reset link. Please try again.'
                                ];
                            }
                        } else {
                            $response = [
                                'status' => 'error',
                                'message' => 'There was an error while saving the data.'
                            ];
                        }
                    }else{
                        // send a success message to prevent hackers from knowing which email exists on the site or not.
                        $response = [
                            'status' => 'success',
                            'message' => 'If your email exists in our system, we\'ve just sent you a reset link',
                            'redirect' => 'forgot'
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

        // Render the view
        return ['content' => $this->view->render($data, 'auth/forgot')];
    }
    
    // Generate unique ID
    function uniqueid(): string
    {
        return substr(number_format(time() * rand(), 0, '', ''), 0, 12);
    }
}
