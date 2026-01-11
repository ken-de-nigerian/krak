<?php

namespace Fir\Controllers;

use Exception;
use Fir\Helpers\EmailHelper;
use Fir\Helpers\QrHelper;

/**
 * Controller class for handling user registration.
 */
class Register extends Controller 
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

        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();

        if ($underMaintenance['maintenance_mode'] == 1) {
            redirect('maintenance');
        }

        if ($data['settings']['register_status'] == 2) {
            $_SESSION['message'][] = ['error', 'Sorry, registrations are currently disabled.'];
            redirect('login');
        }
    }

    /**
     * Display the registration form or process the registration.
     *
     * @return array An array containing the rendered view or JSON response.
     */
    public function index(): array
    {
        $data = [];

        // unset the sessions and redirect
        $session = $this->library('Session');
        $session->delete('access_token');

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

        // Use Input Library
        $input = $this->library('Input');

        $userModel = $this->model('User');

        // Fetch the email template with id = 1 & 4
        $data['email-templates'] = $settingsModel->getEmailTemplate();
        $referralTemplate = $data['email-templates'][1] ?? null;
        $welcomeTemplate = $data['email-templates'][4] ?? null;

        // Handle POST request (registration form submission)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $validator = $this->library('Validator');
            $validation = $validator->check($_POST, [
                'firstname' => [
                    'required' => true
                ],
                'lastname' => [
                    'required' => true
                ],
                'email' => [
                    'required' => true, 
                    'email' => true
                ],
                'formattedPhone' => [
                    'required' => true
                ],
                'country' => [
                    'required' => true
                ],
                'password' => [
                    'required' => true
                ],
                'confirmPassword' => [
                    'required' => true, 
                    'match' => 'password'
                ]
            ]);

            // If validation passes
            if (!$validation->fails()) {
                try {

                    // Check if admin registered email exisits
                    $hasAdminEmail = $userModel->hasAdminEmail($input->get('email'));

                    if ($hasAdminEmail) {
                        $response = [
                            'status' => 'error',
                            'message' => 'This email is registered to another user.',
                        ];
                    }else{

                        // Check if the email is already registered
                        $hasEmail = $userModel->hasEmail($input->get('email'));

                        if (!$hasEmail) {

                            // Check if the phone number is already registered
                            $has_phone = $userModel->hasPhone($input->get('formattedPhone'));

                            if (!$has_phone) {

                                // Check if passwords match
                                if ($input->get('password') === $input->get('confirmPassword')) {

                                    // Hash password
                                    $password = password_hash($input->get('password'), PASSWORD_DEFAULT);

                                    // Generate unique user ID
                                    $userid = $this->uniqueid();

                                    // Check referral ID validity
                                    $isValidReferral = $userModel->isValidReferral($input->get('referralId'));

                                    if ($isValidReferral) {

                                        // Get referrer's data
                                        $data['referral'] = $userModel->getRef($input->get('referralId'));
                                        $referrerFirstName = $data['referral']['firstname'];
                                        $referrerLastName = $data['referral']['lastname'];

                                        // Register user with referral
                                        $insert = $userModel->register(
                                            $userid,
                                            $password,
                                            $input->get('referralId'),
                                            $input->get('email'),
                                            $input->get('firstname'),
                                            $input->get('lastname'),
                                            $input->get('formattedPhone'),
                                            $input->get('country')
                                        );

                                        // Registeration was successful
                                        if ($insert == 1) {

                                            // Attempt to authenticate the user
                                            $auth = $user->login($input->get('email'), $input->get('password'));

                                            // if authentication works
                                            if ($auth) {

                                                // Generate QR code for the user
                                                $qrCodeContent = getenv('URL_PATH') . '/register/?ref=' . $userid;

                                                if (QrHelper::createQR($qrCodeContent, $userid)) {

                                                    $qr_image = $userid . '_qrcode.png';

                                                    // update user's account with qr code
                                                    $userModel->add_account($userid, $qr_image);

                                                    // Check if signup bonus is active
                                                    if ($data['settings']['signup_bonus_control'] == 1) {

                                                        // Calculate new balance
                                                        $bonus = $user->data()['interest_wallet'] + $data['settings']['signup_bonus_amount'];
                                                        
                                                        // Give user a signup bonus
                                                        $userModel->add_bonus($userid, $bonus);
                                                    }

                                                    // Initialize variables for email notifications
                                                    $referralEmailSent = false;
                                                    $welcomeEmailSent = false;

                                                    // email notification is enabled
                                                    if ($data['settings']["email_notification"] == 1) {

                                                        $siteName = $data['settings']['sitename'];
                                                        $siteLogo = $data['settings']['logo'];
                                                        $siteUrl = getenv('URL_PATH');
                                                        $dateNow = date('Y');

                                                        // referral template is enabled & not null
                                                        if ($referralTemplate !== null && $referralTemplate['status'] == 1) {

                                                            // Replace placeholders in referral email body
                                                            $referralTemplate['body'] = str_replace(['{FIRSTNAME}', '{LASTNAME}', '{REFFIRSTNAME}', '{REFLASTNAME}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'], [$input->get('firstname'), $input->get('lastname'), $referrerFirstName, $referrerLastName, $siteName, $siteLogo, $siteUrl, $dateNow], $referralTemplate['body']);

                                                            $referralEmail = $data['referral']['email'];
                                                            $referralSubject = $referralTemplate['subject'];
                                                            $referralBody = $referralTemplate['body'];

                                                            // Send referral email
                                                            $referralEmailSent = EmailHelper::sendEmail($data['settings'], $referralEmail, $referralSubject, $referralBody);
                                                        }

                                                        // welcome template is enabled & not null
                                                        if ($welcomeTemplate !== null && $welcomeTemplate['status'] == 1) {

                                                            // Replace placeholders in email body
                                                            $welcomeTemplate['body'] = str_replace(
                                                                ['{FIRSTNAME}', '{LASTNAME}', '{SITENAME}', '{URL}', '{DATENOW}'],
                                                                [$input->get('firstname'), $input->get('lastname'), $siteName, $siteUrl, $dateNow],
                                                                $welcomeTemplate['body']
                                                            );

                                                            $recipientEmail = $input->get('email');
                                                            $welcomeSubject = $welcomeTemplate['subject'];
                                                            $welcomeBody = $welcomeTemplate['body'];
                                                        
                                                            // Send welcome email
                                                            $welcomeEmailSent = EmailHelper::sendEmail($data['settings'], $recipientEmail, $welcomeSubject, $welcomeBody);
                                                        }

                                                        if ($referralEmailSent && $welcomeEmailSent) {
                                                            $response = [
                                                                'status' => 'success',
                                                                'redirect' => 'user/dashboard'
                                                            ];
                                                        } else {
                                                            $response = [
                                                                'status' => 'error',
                                                                'message' => 'Registered, but failed to send a notification',
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
                                                        'message' => 'We were unable to create a QR code for this profile.',
                                                    ];
                                                }
                                            }else{
                                                // Account authentication failed error
                                                $response = [
                                                    'status' => 'error',
                                                    'message' => 'Account authentication failed. Please try again.',
                                                ];
                                            }
                                        } else {
                                            // Error occurred while saving data
                                            $response = [
                                                'status' => 'error',
                                                'message' => 'Error occurred while saving data., try again.',
                                            ];
                                        }
                                    }else{
                                        // Register user without a referral
                                        $insert = $userModel->register(
                                            $userid,
                                            $password,
                                            null,
                                            $input->get('email'),
                                            $input->get('firstname'),
                                            $input->get('lastname'),
                                            $input->get('formattedPhone'),
                                            $input->get('country')
                                        );

                                        // Registeration was successful
                                        if ($insert == 1) {

                                            // Attempt to authenticate the user
                                            $auth = $user->login($input->get('email'), $input->get('password'));

                                            // If authentication works
                                            if ($auth) {

                                                // Generate QR code for the user
                                                $qrCodeContent = getenv('URL_PATH') . '/register/?ref=' . $userid;

                                                if (QrHelper::createQR($qrCodeContent, $userid)) {

                                                    $qr_image = $userid . '_qrcode.png';
                                                    
                                                    // update user's account with qr code
                                                    $update = $userModel->add_account($userid, $qr_image);

                                                    // Send welcome email if account update is successful
                                                    if ($update == 1) {

                                                        // Check if signup bonus is active
                                                        if ($data['settings']['signup_bonus_control'] == 1) {
                                                            // Calculate new balance
                                                            $bonus = $user->data()['interest_wallet'] + $data['settings']['signup_bonus_amount'];
                                                            
                                                            // Give user a signup bonus
                                                            $userModel->add_bonus($userid, $bonus);
                                                        }

                                                        // email notification is enabled
                                                        if ($data['settings']["email_notification"] == 1) {

                                                            $siteName = $data['settings']['sitename'];
                                                            $siteLogo = $data['settings']['logo'];
                                                            $siteUrl = getenv('URL_PATH');
                                                            $dateNow = date('Y');

                                                            // welcome template is enabled
                                                            if ($welcomeTemplate !== null && $welcomeTemplate['status'] == 1) {

                                                                // Replace placeholders in email body
                                                                $welcomeTemplate['body'] = str_replace(
                                                                    ['{FIRSTNAME}', '{LASTNAME}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'],
                                                                    [$input->get('firstname'), $input->get('lastname'), $siteName, $siteLogo, $siteUrl, $dateNow],
                                                                    $welcomeTemplate['body']
                                                                );

                                                                $recipientEmail = $input->get('email');
                                                                $welcomeSubject = $welcomeTemplate['subject'];
                                                                $welcomeBody = $welcomeTemplate['body'];

                                                                if (EmailHelper::sendEmail($data['settings'], $recipientEmail, $welcomeSubject, $welcomeBody)) {
                                                                    $response = [
                                                                        'status' => 'success',
                                                                        'redirect' => 'user/dashboard'
                                                                    ];
                                                                } else {
                                                                    $response = [
                                                                        'status' => 'error',
                                                                        'message' => 'Registered, but failed to send notification emails.',
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
                                                            'message' => 'Error occurred while saving data.',
                                                        ];
                                                    }
                                                }else{
                                                    $response = [
                                                        'status' => 'error',
                                                        'message' => 'We were unable to create a QR code for this profile.',
                                                    ];
                                                }
                                            } else {
                                                // Account authentication failed error
                                                $response = [
                                                    'status' => 'error',
                                                    'message' => 'Account authentication failed. Please try again.',
                                                ];
                                            }
                                        } else {
                                            $response = [
                                                'status' => 'error',
                                                'message' => 'Error occurred while saving data., try again.',
                                            ];
                                        }
                                    }
                                }else{
                                    // Passwords mismatch
                                    $response = [
                                        'status' => 'error',
                                        'message' => 'Your passwords do not match. Please try again.'
                                    ];
                                }
                            }else{
                                // Phone number already registered
                                $response = [
                                    'status' => 'error',
                                    'message' => 'This phone number is registered to another user.',
                                ];
                            }
                        }else{
                            // Email already registered
                            $response = [
                                'status' => 'error',
                                'message' => 'This email is registered to another user.',
                            ];
                        }
                    }
                } catch (Exception $e) {
                    // General exception error
                    $response = [
                        'status' => 'error',
                        'message' => $e->getMessage()
                    ];
                }
            } else {
                // Validation errors
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
            }

            // Send the JSON response
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }

        return ['content' => $this->view->render($data, 'auth/register')];
    }

    /**
     * Generate a unique ID.
     *
     * @return string A unique ID.
     */
    protected function uniqueid(): string
    {
        return substr(number_format(time() * rand(), 0, '', ''), 0, 12);
    }
}
