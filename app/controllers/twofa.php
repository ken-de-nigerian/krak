<?php

namespace Fir\Controllers;

use Exception;
use Fir\Helpers\EmailHelper;

class Twofa extends Controller 
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
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];

        /* Use User Library */
        $user = $this->library('User');
        $data['user'] = $user->data();

        if ($user->data()["twofactor_flag"] == 2) {
            redirect('login');
        }

        return ['content' => $this->view->render($data, 'auth/two-factor-authentication')];
    }

    /**
     * resendcode
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function resendcode(): void
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];
    
        /* Use User Library */
        $user = $this->library('User');
        $data['user'] = $user->data();
    
        /* Validate user data */
        if (!isset($data['user']) || !is_array($data['user']) || !isset($data['user']['userid'])) {
            $response = [
                'status' => 'error',
                'message' => 'User not authenticated or invalid user data.'
            ];
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }
    
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();
    
        /* Use User Model */
        $userModel = $this->model('User');
    
        // Fetch the email template with id = 18
        $data['email-templates'] = $settingsModel->getEmailTemplate();
        $otpTemplate = $data['email-templates'][18] ?? null;
    
        // Generate new verification code
        $code = mt_rand(100000, 999999);
    
        // Store the new verification code in the database
        $Update = $userModel->storeOtp($data['user']['userid'], $code);
    
        if ($Update === 1) {
            // Email notification is enabled
            if ($data['settings']["email_notification"] == 1) {
                if ($otpTemplate !== null && $otpTemplate['status'] == 1) {
                    $siteName = $data['settings']['sitename'];
                    $siteLogo = $data['settings']['logo'];
                    $siteUrl = getenv('URL_PATH');
                    $dateNow = date('Y');
    
                    // Replace the {VERIFY_CODE} placeholder with the actual reset code
                    $verifyCode = sprintf('%06d', $code); // Pad the code with leading zeros if needed
                    $otpTemplate['body'] = str_replace(
                        ['{VERIFY_CODE}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'],
                        [$verifyCode, $siteName, $siteLogo, $siteUrl, $dateNow],
                        $otpTemplate['body']
                    );
    
                    // Send email with code to user
                    $recipientEmail = $data['user']['email'];
                    $subject = $otpTemplate['subject'];
                    $body = $otpTemplate['body'];
    
                    // If email was sent successfully
                    if (EmailHelper::sendEmail($data['settings'], $recipientEmail, $subject, $body)) {
                        $response = [
                            'status' => 'success',
                            'message' => 'OTP code resent successfully.'
                        ];
                    } else {
                        $response = [
                            'status' => 'error',
                            'message' => 'Failed to resend OTP code.'
                        ];
                    }
                } else {
                    $response = [
                        'status' => 'error',
                        'message' => 'Cannot proceed, email template is disabled or unavailable.'
                    ];
                }
            } else {
                $response = [
                    'status' => 'warning',
                    'message' => 'This action cannot be processed at the moment. Email notifications disabled.'
                ];
            }
        } else {
            $response = [
                'status' => 'warning',
                'message' => 'Error storing OTP. Please try again later.'
            ];
        }
    
        // Send the JSON response
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    /**
     * verify
     */
    public function verify(): void
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];

        /*Use User Library*/
        $user = $this->library('User');
        $data['user'] = $user->data();

        /* Use User Model */
        $userModel = $this->model('User');

        /* Use Input Library */
        $input = $this->library('Input');

        // Process form submission if the request method is POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $validator = $this->library('Validator');
            $validationRules = [
                'code' => [
                    'required' => true,
                    'digit' => true,
                    'maxlength' => 6
                ]
            ];

            $validation = $validator->check($_POST, $validationRules);

            // If validation fails, gather error messages
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
                    
                    if ($data['user']['twofactor_code'] === $input->get('code')) {

                        $update = $userModel->updateTwofactor($data['user']['userid']);

                        if ($update == 1) {
                            $response = [
                                'status' => 'success',
                                'redirect' => 'login'
                            ]; 
                        } else {
                            $response = [
                                'status' => 'error',
                                'message' => 'Error encountered. Please try again later.'
                            ];
                        }
                    } else {
                        $response = [
                            'status' => 'error',
                            'message' => 'The OTP code you entered is invalid.'
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
        } else {
            // If the request is not a POST request, redirect to the login page
            redirect('login');
        }
    }
}