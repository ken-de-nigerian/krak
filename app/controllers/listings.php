<?php

namespace Fir\Controllers;

use Fir\Helpers\EmailHelper;

class Listings extends Controller
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
     * Index
     */
    public function index(): array
    {
        // Initialize an empty data array
        $data = [];
        
        /*Use User Library*/
        $user = $this->library('User');
        $data['user'] = $user->data();
        $data['user_isloggedin'] = $user->isLoggedIn();

        // Use Models
        $userModel = $this->model('User');

        // Retrieve plans and time settings
        $data['plans'] = $userModel->plans();
        $data['times'] = $userModel->times();

        // get the referral settings
        $data['referral-settings'] = $userModel->referralSettings();

        // listings
        $data['listings'] = $userModel->listings();

        return ['content' => $this->view->render($data, 'home/listings')];
    }

    /**
     * Index
     */
    public function details(): array
    {
        // Initialize an empty data array
        $data = [];
        
        /*Use User Library*/
        $user = $this->library('User');
        $data['user'] = $user->data();
        $data['user_isloggedin'] = $user->isLoggedIn();

        /* Use Input Library */
        $input = $this->library('Input');

        // Use Models
        $userModel = $this->model('User');

        if (!isset($this->url[2]) || !intval($this->url[2])|| !$userModel->hasPropertyId($this->url[2])) {
            $_SESSION['message'][] = ['error', 'Failed to fetch property details. Please try again later.'];
            redirect('listings');
        }

        $data['property-details'] = $userModel->getProperty($this->url[2]);

        /* Use Models */
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();

        // Fetch the email template with id = 19
        $data['email-templates'] = $settingsModel->getEmailTemplate();
        $houseTemplate = $data['email-templates'][34] ?? null;

        // Check if the request method is POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Check if input exists
            if ($input->exists()) {

                $validator = $this->library('Validator');

                // Validate input data
                $validation = $validator->check($_POST, [
                    'amount' => [
                        'required' => true
                    ],
                    'email' => [
                        'required' => true
                    ],
                    'phone' => [
                        'required' => true
                    ],
                    'location' => [
                        'required' => true
                    ],
                    'message' => [
                        'required' => true
                    ]
                ]);

                if (!$validation->fails()) {
                    try {

                        // store variables
                        $amount = $input->get('amount');
                        $email = $input->get('email');
                        $phone = $input->get('phone');
                        $location = $input->get('location');
                        $message = $input->get('message');

                        // email notification is enabled
                        if ($data['settings']["email_notification"] == 1) {

                            $siteName = $data['settings']['sitename'];
                            $siteLogo = $data['settings']['logo'];
                            $siteUrl = getenv('URL_PATH');
                            $dateNow = date('Y');

                            // house template is enabled
                            if ($houseTemplate !== null && $houseTemplate['status'] == 1) {

                                $houseTemplate['body'] = str_replace(
                                    ['{AMOUNT}', '{EMAIL}', '{PHONE}', '{LOCATION}', '{MESSAGE}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'], [$amount, $email, $phone, $location, $message, $siteName, $siteLogo, $siteUrl, $dateNow],
                                    $houseTemplate['body']
                                );

                                // Send email with notification to the user
                                $recipientEmail = $data['settings']['email_address'];
                                $body = $houseTemplate['body'];
                                $subject = "New Property Listing Inquiry Alert: Check Out Your Latest Message!";

                                if (EmailHelper::sendEmail($data['settings'], $recipientEmail, $subject, $body)) {
                                    // Email sent successfully
                                    $response = [
                                        'status' => 'success',
                                        'message' => 'Your email has been sent successfully',
                                        'redirect' => 'listings'
                                    ];
                                } else {
                                    // Failed to send email
                                    $response = [
                                        'status' => 'error',
                                        'message' => 'We failed to send your email. Please try again.'
                                    ];
                                }
                            }else{
                                // Failed to send email
                                $response = [
                                    'status' => 'error',
                                    'message' => 'We failed to send your email. Please try again.'
                                ];
                            }
                        }else{
                            // email notification is disabled
                            $response = [
                                'status' => 'error',
                                'message' => 'Email notification is currently disabled.'
                            ]; 
                        }
                    } catch (Exception $e) {
                        // Error response if an exception occurs during profile update
                        $response = [
                            'status' => 'error',
                            'message' => $e->getMessage()
                        ];
                    }
                } else {
                    // If validation fails, gather error messages and send response
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
        }

        return ['content' => $this->view->render($data, 'home/listings-details')];
    }
}
