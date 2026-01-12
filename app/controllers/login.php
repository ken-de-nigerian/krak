<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\controllers;

use KenDeNigerian\Krak\core\Controller;

use Exception;
use KenDeNigerian\Krak\helpers\emailhelper;

class login extends Controller 
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

    public function index()
    {
        $data = [];

        // unset the sessions and redirect
        $session = $this->library('Session');
        $session->delete('access_token');

        $user = $this->library('User');

        $userModel = $this->model('User');

        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();

        // Fetch the email template with id = 18
        $data['email-templates'] = $settingsModel->getEmailTemplate();
        $otpTemplate = $data['email-templates'][18] ?? null;

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

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $validator = $this->library('Validator');
            $validation = $validator->check($_POST, [
                'email' => ['required' => true, 'email' => true],
                'password' => ['required' => true]
            ]);

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
            }else {
                try {

                    $email = $_POST['email'];
                    $password = $_POST['password'];
                    $remember = isset($_POST['remember']) && $_POST['remember'] === 'on';

                    // two factors are enabled perform security checks
                    if ($data['settings']["twofa_status"] == 1) {
                        // authenticate the user
                        $auth = $user->login($email, $password, $remember);
                        if ($auth) {
                            // 2fa auth is enabled for user, perform checks
                            if ($user->data()["twofactor_status"] == 1) {
                                // Generate new verification code
                                $code = mt_rand(100000, 999999);
                                // store code
                                $update = $userModel->storeOtp($user->data()["userid"], $code); 
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

                                            $recipientEmail = $user->data()["email"];
                                            $subject = $otpTemplate['subject'];
                                            $body = $otpTemplate['body'];
                                        
                                            // if email was sent successfully redirect to auth page
                                            if (emailhelper::sendEmail($data['settings'], $recipientEmail, $subject, $body)) {
                                                $response = [
                                                    'status' => 'success',
                                                    'redirect' => 'twofa',
                                                ];
                                            } else {
                                                $response = [
                                                    'status' => 'error',
                                                    'message' => 'We failed to send otp email.'
                                                ];
                                            }
                                        }else{
                                            // email notification is disabled
                                            $response = [
                                                'status' => 'error',
                                                'message' => 'Can\'t proceed, email notifications are disabled.'
                                            ];
                                        }
                                    }else{

                                        // email notification is disabled
                                        $response = [
                                            'status' => 'error',
                                            'message' => 'Can\'t proceed, email notifications are disabled.'
                                        ];
                                    }
                                } else {
                                    $response = [
                                        'status' => 'error',
                                        'message' => 'Error sending code. Please try again later.'
                                    ];
                                }
                            }else{
                                // two factors are disabled for user, proceed with login
                                $response = [
                                    'status' => 'success',
                                    'redirect' => 'user/dashboard'
                                ];
                            }
                        }else{
                            $response = [
                                'status' => 'error',
                                'message' => 'Login failed. Verify your credentials and try again.'
                            ];
                        }
                    }else{
                        // authenticate the user
                        $auth = $user->login($email, $password, $remember);
                        if ($auth) {
                            $response = [
                                'status' => 'success',
                                'redirect' => 'user/dashboard'
                            ];
                        }else{
                            $response = [
                                'status' => 'error',
                                'message' => 'Login failed. Verify your credentials and try again.'
                            ];
                        }
                    }
                }catch (Exception $e) {
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

        return ['content' => $this->view->render($data, 'auth/login')];
    }
}