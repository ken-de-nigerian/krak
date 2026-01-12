<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\controllers;

use Exception;
use KenDeNigerian\Krak\core\Controller;
use KenDeNigerian\Krak\helpers\emailhelper;
use KenDeNigerian\Krak\helpers\qrhelper;

class admin extends Controller {

    /**
     * index
     */
    public function index(): void
    {
        redirect('admin/login');
    }

    /**
     * login
     */
    public function login(): array
    {

        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];

        /* Use Admin Library */
        $admin = $this->library('Admin');

        // If admin is already logged in, redirect to dashboard
        if($admin->isLoggedIn() === true):
            redirect('admin/dashboard');
        endif;

        /* Use Settings Model */
        $settingsModel = $this->model('Settings');

        // Get settings data
        $data['settings'] = $settingsModel->get();

        // If the user tries to log in
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Load Validator library
            $validator = $this->library('Validator');

            // Perform validation on $_POST data
            $validation = $validator->check($_POST, [
                'email' => [
                    'required' => true, 
                    'email' => true
                ],
                'password' => [
                    'required' => true
                ]
            ]);

            // If validation fails
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

                    $email = $_POST['email'];
                    $password = $_POST['password'];
                    $remember = null;

                    if(isset($_POST['remember'])) {
                        $remember = $_POST['remember'] === 'on';
                    }

                    // Attempt to authenticate the admin
                    $auth = $admin->login($email, $password, $remember);

                    // If the user has been logged in successfully
                    if($auth) {
                        $response = [
                            'status' => 'success',
                            'redirect' =>'admin/dashboard'
                        ];
                    } else {
                        // If authentication fails
                        $response = [
                            'status' => 'error',
                            'message' => 'These credentials do not match our records.'
                        ];
                    }
                } catch (Exception $e) {
                    // If any exception occurs during a login attempt
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

        // Render the login view with data
        return ['content' => $this->view->render($data, 'admin/auth/login')];
    }

    /**
     * dashboard
     */
    public function dashboard(): array
    {

        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];

        /* Use Admin Library */
        $admin = $this->library('Admin');
        $data['admin'] = $admin->data();

        // If not logged in, redirect to login page
        if(!$admin->isLoggedIn()):
            redirect('admin/login');
        endif;

        // Get settings from the model
        $settingsModel = $this->model('Settings');
        $adminModel = $this->model('Admin');
        $settings = $settingsModel->get();

        $data['get-gateway'] = $settingsModel->getGateways();

        $data['all-users-count'] = $adminModel->AllUsersCount();
        $data['active-users-count'] = $adminModel->ActiveUsersCount();
        $data['banned-users-count'] = $adminModel->BannedUsersCount();
        $data['kyc-unverified-count'] = $adminModel->KYCUnverifiedCount();
        $data['kyc-pending-count'] = $adminModel->KYCPendingCount();

        $data['newly-registered'] = $adminModel->newlyRegistered();
        $data['users'] = $adminModel->Users();
        $data['recent-transactions'] = $adminModel->recentTransactions();

        // Render the dashboard view with data
        return ['content' => $this->view->render($data, 'admin/dashboard')];
    }

    /**
     * This method handles the AJAX request to fetch cryptocurrency amounts.
     *
     * @return void JSON response containing amount and market cap
     */
    public function fetch(): void
    {
        $response = [];
        
        // Your CoinMarketCap API key
        $apiKey = 'a69214e8-6b65-42fc-9407-a2f83126d507';
        
        // Determine source currency based on user currency
        $sourceCurrency = "USD";
        
        // Determine target cryptocurrency from request parameters
        $targetCrypto = $_GET['abbreviation'] ?? '';
        
        // Ensure target cryptocurrency is provided
        if (empty($targetCrypto)) {
            $response = [
                'status' => 'error',
                'message' => 'Invalid cryptocurrency abbreviation'
            ];
        } else {
            // Prepare CoinMarketCap API URL
            $apiUrl = "https://pro-api.coinmarketcap.com/v1/cryptocurrency/quotes/latest?symbol=" . urlencode($targetCrypto) . "&convert=" . urlencode($sourceCurrency);
            
            // Initialize cURL
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'X-CMC_PRO_API_KEY: ' . $apiKey
            ]);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            
            // Execute cURL request
            $apiResponse = curl_exec($ch);
            $curlError = curl_error($ch);
            curl_close($ch);
            
            if ($apiResponse !== false) {
                // Decode JSON response
                $data = json_decode($apiResponse, true);
                
                // Check if the target cryptocurrency exists in the response
                if (isset($data['data'][$targetCrypto]['quote'][$sourceCurrency]['price'])) {
                    // Get the amount of the target cryptocurrency in the user's currency
                    $amount = $data['data'][$targetCrypto]['quote'][$sourceCurrency]['price'];
                    
                    // Format amount
                    $amountFormatted = number_format($amount, 2);
                    
                    $response = [
                        'status' => 'success',
                        'converted' => $amountFormatted
                    ];
                } else {
                    $response = [
                        'status' => 'error',
                        'message' => 'Invalid cryptocurrency or currency'
                    ];
                }
            } else {
                // Construct error message
                $errorMessage = $curlError ?: "Failed to fetch cryptocurrency amount";
                
                $response = [
                    'status' => 'error',
                    'message' => $errorMessage
                ];
            }
        }
        
        // Set response headers and output JSON response
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
    
    /**
     * edit profile
     */
    public function profile(): array
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];

        /* Use Admin Library */
        $admin = $this->library('Admin');
        $data['admin'] = $admin->data();

        // If not logged in, redirect to login page
        if(!$admin->isLoggedIn()):
            redirect('admin/login');
        endif;

        /* Use Input Library */
        $input = $this->library('Input');
        
        /* Use Admin Model */
        $adminModel = $this->model('Admin');

        // Process edit profile form
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $response = []; // Initialize the $response variable

            // Check if input exists
            if ($input->exists()) {

                // Load Validator library
                $validator = $this->library('Validator');

                // Perform validation on $_POST data
                $validation = $validator->check($_POST, [
                    'fullname' => [
                        'required' => true
                    ],
                    'email' => [
                        'required' => true,
                        'email' => true
                    ]
                ]);

                // If validation passes
                if (!$validation->fails()) {
                    try {

                        // file formats
                        $validFormats = ["jpg", "jpeg", "png"];

                        // Retrieve file details
                        $name = $_FILES['photoimg']['name'];
                        $size = $_FILES['photoimg']['size'];

                        if (!empty($name)) {

                            $fileFormat = pathinfo($_FILES['photoimg']['name'], PATHINFO_EXTENSION);

                            if ($_FILES['photoimg']['error'] === UPLOAD_ERR_OK) {
                                if (in_array($fileFormat, $validFormats)) {

                                    // Check file size
                                    if ($size <= 2097152) { // 2MB in bytes

                                        $fileName = $this->rando() . '.' . $fileFormat;

                                        // Get the image type
                                        $image_info = getimagesize($_FILES['photoimg']['tmp_name']);
                                        $image_type = $image_info[2];

                                        // image
                                        $image = '';

                                        // Create image based on an image type
                                        switch ($image_type) {
                                            case IMAGETYPE_JPEG:
                                                $image = imagecreatefromjpeg($_FILES['photoimg']['tmp_name']);
                                                break;
                                            case IMAGETYPE_PNG:
                                                $image = imagecreatefrompng($_FILES['photoimg']['tmp_name']);
                                                break;
                                            // Add more cases as needed for other image types
                                            default:
                                                break;
                                        }

                                        // Resize the image to 800x800
                                        $resized_image = imagescale($image, 800, 800);

                                        // Path to upload directory
                                        $path = sprintf('%s/../../%s/%s/staff/', __DIR__, PUBLIC_PATH, UPLOADS_PATH);

                                        // Save the resized image to a file
                                        if (imagejpeg($resized_image, $path . $fileName)) {

                                            // Update profile with new image
                                            $update = $adminModel->updateAdminProfile($fileName, $input->get('fullname'), $input->get('email'), $data['admin']['adminid']);
                                            
                                            // Check if the profile was updated
                                            if ($update == 1) {
                                                $response = [
                                                    'status' => 'success',
                                                    'redirect' => 'admin/profile',
                                                    'message' => 'Your profile has been updated successfully.'
                                                ];
                                            } else {
                                                $response = [
                                                    'status' => 'error',
                                                    'message' => 'No changes have been made to your profile.'
                                                ];
                                            }
                                        } else {
                                            $response = [
                                                'status' => 'error',
                                                'message' => 'Unable to upload the image. Please try again.'
                                            ];
                                        }

                                        // Free up memory
                                        imagedestroy($image);
                                        imagedestroy($resized_image);
                                    } else {
                                        $response = [
                                            'status' => 'warning',
                                            'message' => 'File size exceeds the maximum limit of 2MB.'
                                        ];
                                    }
                                } else {
                                    $response = [
                                        'status' => 'warning',
                                        'message' => 'Allowed file extensions: jpg, jpeg, png'
                                    ];
                                }
                            } else {
                                $response = [
                                    'status' => 'warning',
                                    'message' => 'Unable to upload the document, please try again.'
                                ];
                            }
                        } else {

                            // If no image selected
                            $update = $adminModel->NoImage($input->get('fullname'), $input->get('email'), $data['admin']['adminid']);
                                    
                            // Check if the profile was updated
                            if ($update == 1) {
                                $response = [
                                    'status' => 'success',
                                    'redirect' => 'admin/profile',
                                    'message' => 'Your profile has been updated successfully.'
                                ];
                            } else {
                                $response = [
                                    'status' => 'error',
                                    'message' => 'No changes have been made to your profile.'
                                ];
                            }
                        }
                    } catch (Exception $e) {
                        // If an exception occurs
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
            }

            // Send the JSON response
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }

        // Render the edit profile view with data
        return ['content' => $this->view->render($data, 'admin/profile')];
    }

    /**
     * password
     */
    public function password(): void
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];

        /* Use Admin Library */
        $admin = $this->library('Admin');
        $data['admin'] = $admin->data();

        // If not logged in, redirect to login page
        if (!$admin->isLoggedIn()):
            redirect('admin/login');
        endif;

        /* Use Input Library */
        $input = $this->library('Input');
        $adminModel = $this->model('Admin');

        //Edit Security Details
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if ($input->exists()) {

                $validator = $this->library('Validator');

                $validation = $validator->check($_POST, [
                    'oldPassword' => [
                        'required' => true,
                    ],
                    'password' => [
                        'required' => true,
                    ],
                    'confirmPassword' => [
                        'required' => true,
                        'match' => 'password'
                    ]
                ]);

                if (!$validation->fails()) {
                    try {
                        // Check if the new password matches the confirmed password
                        if ($input->get('password') === $input->get('confirmPassword')) {
                            // Verify old password
                            if (password_verify($input->get('oldPassword'), $data['admin']['password'])) {

                                /* Hash New Password */
                                $password = password_hash($input->get('password'), PASSWORD_DEFAULT);

                                // Update password
                                $update = $adminModel->password($password, $data['admin']['adminid']);

                                if ($update == 1) {
                                    $response = [
                                        'status' => 'success',
                                        'message' => 'Your password has been successfully changed.',
                                        'redirect' => 'admin/profile'
                                    ];
                                } else {
                                    $response = [
                                        'status' => 'error',
                                        'message' => 'No changes were made to your account.'
                                    ];
                                }
                            } else {
                                $response = [
                                    'status' => 'error',
                                    'message' => 'Your current password does not match our records.'
                                ];
                            }
                        } else {
                            $response = [
                                'status' => 'error',
                                'message' => 'Your passwords mismatched. Try again.'
                            ];
                        }
                    }catch (Exception $e) {
                        $response = [
                            'status' => 'error',
                            'message' => $e->getMessage()
                        ];
                    }
                } else {
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
        }else{
            redirect ('admin/profile');
        }
    }

    /**
     * settings
     */
    public function settings(): array
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];

        /* Use Admin Library */
        $admin = $this->library('Admin');
        $data['admin'] = $admin->data();

        /* Is logged-in */
        if(!$admin->isLoggedIn()):
            redirect('admin/login');
        endif;

        /* Use Input Library */
        $input = $this->library('Input');
        
        /* Use Admin Model */
        $adminModel = $this->model('Admin');

        /* Use Settings Model */
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();

        // get the referral settings
        $data['referral-settings'] = $adminModel->referralSettings();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $validator = $this->library('Validator');
            
            $validation = $validator->check($_POST, [
                'sitename' => ['required' => true],
                'timezone' => ['required' => true],
                'invest_commission' => ['required' => true],
                'signup_bonus_control' => ['required' => true],
                'signup_bonus_amount' => ['required' => true],
                'b_transfer' => ['required' => true],
                'b_request' => ['required' => true],
                'user_ranking' => ['required' => true],
                'twofa_status' => ['required' => true],
                'register_status' => ['required' => true],
                'kyc_status' => ['required' => true]
            ]);

            if (!$validation->fails()) {
                try {

                    $update = $adminModel->updateSite(
                        $input->get('sitename'),
                        $input->get('timezone'),
                        $input->get('invest_commission'),
                        $input->get('signup_bonus_amount'),
                        $input->get('signup_bonus_control'),
                        $input->get('b_transfer'),
                        $input->get('b_request'),
                        $input->get('user_ranking'),
                        $input->get('twofa_status'),
                        $input->get('register_status'),
                        $input->get('kyc_status')
                    );

                    if ($update == 1) {
                        $response = [
                            'status' => 'success',
                            'message' => 'Site settings has been updated successfully',
                            'redirect' => 'admin/settings'
                        ];
                    } else {
                        $response = [
                            'status' => 'error',
                            'message' => 'No changes have been made to the settings.',
                        ];
                    }
                }catch (Exception $e) {
                    $response = [
                        'status' => 'error',
                        'message' => $e->getMessage()
                    ];
                }
            } else {
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

        return ['content' => $this->view->render($data, 'admin/site/settings')];
    }

    /**
     * email
     */
    public function email(): array
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];
        
        /* Use Admin Library */
        $admin = $this->library('Admin');
        $data['admin'] = $admin->data();

        // If not logged in, redirect to login page
        if(!$admin->isLoggedIn()):
            redirect('admin/login');
        endif;

        /* Use Input Library */
        $input = $this->library('Input');
        
        /* Use Admin Model */
        $adminModel = $this->model('Admin');
        
        /* Use Settings Model */
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();

        // Fetch the email template with id = 21
        $data['email-templates'] = $settingsModel->getEmailTemplate();
        $testTemplate = $data['email-templates'][21] ?? null;

        if (isset($this->url[2]) && $this->url[2] == 'mailjet') {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $validator = $this->library('Validator');

                $validation = $validator->check($_POST, [
                    'mailjet_api_key' => ['required' => true],
                    'mailjet_api_secret' => ['required' => true],
                    'email_notification' => ['required' => true],
                    'email_provider' => ['required' => true]
                ]);

                if (!$validation->fails()) {
                    try {
                        $update = $adminModel->updateMailjet(
                            $input->get('mailjet_api_key'),
                            $input->get('mailjet_api_secret'),
                            $input->get('email_notification'),
                            $input->get('email_provider')
                        );

                        if ($update == 1) {
                            $response = [
                                'status' => 'success',
                                'message' => 'Mailjet settings has been updated successfully',
                                'redirect' => 'admin/email'
                            ];
                        } else {
                            $response = [
                                'status' => 'error',
                                'message' => 'No changes have been made to the settings.',
                            ];
                        }
                    }catch (Exception $e) {
                        $response = [
                            'status' => 'error',
                            'message' => $e->getMessage()
                        ];
                    }
                } else {
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
        }elseif (isset($this->url[2]) && $this->url[2] == 'test-email') {

            // Check if the form is submitted via POST method
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                // Validate form input
                $validator = $this->library('Validator');
                $validationRules = [
                    'email' => [
                        'required' => true, 
                        'email' => true
                    ]
                ];

                $validation = $validator->check($_POST, $validationRules);

                // If validation fails, prepare error messages
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

                        // email notification is enabled
                        if ($data['settings']["email_notification"] == "1") {

                            $siteName = $data['settings']['sitename'];
                            $siteLogo = $data['settings']['logo'];
                            $siteUrl = getenv('URL_PATH');
                            $dateNow = date('Y');

                            // identity template is enabled
                            if ($testTemplate !== null && $testTemplate['status'] == 1) {

                                $testTemplate['body'] = str_replace(
                                    ['{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'], [$siteName, $siteLogo, $siteUrl, $dateNow],
                                    $testTemplate['body']
                                );

                                // Send email with notification to the user
                                $recipientEmail = $input->get('email');
                                $subject = $testTemplate['subject'];
                                $body = $testTemplate['body'];

                                // Send email
                                if (emailhelper::sendEmail($data['settings'], $recipientEmail, $subject, $body)) {
                                    // Email sent successfully
                                    $response = [
                                        'status' => 'success',
                                        'message' => 'Test email notification sent successfully'
                                    ];
                                } else {
                                    // Failed to send email
                                    $response = [
                                        'status' => 'error',
                                        'message' => 'We failed to send you a notification. Please try again.'
                                    ];
                                }
                            }else{
                                // test template is disabled or doesn't exist
                                $response = [
                                    'status' => 'error',
                                    'message' => 'This email template is currently disabled or does not exist'
                                ]; 
                            }
                        }else{
                            // email notification is disabled
                            $response = [
                                'status' => 'error',
                                'message' => 'Email notification is currently disabled. Please activate it.'
                            ]; 
                        }
                    } catch (Exception $e) {
                        // Error occurred while sending email
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
            }else{
                redirect ('admin/email');
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $validator = $this->library('Validator');

            $validation = $validator->check($_POST, [
                'smtp_host' => ['required' => true],
                'smtp_username' => ['required' => true, 'email' => true],
                'smtp_password' => ['required' => true],
                'smtp_encryption' => ['required' => true],
                'smtp_port' => ['required' => true],
                'email_notification' => ['required' => true],
                'email_provider' => ['required' => true]
            ]);

            if (!$validation->fails()) {
                try {
                    $update = $adminModel->updateSmtp(
                        $input->get('smtp_host'),
                        $input->get('smtp_username'),
                        $input->get('smtp_password'),
                        $input->get('smtp_encryption'),
                        $input->get('smtp_port'),
                        $input->get('email_notification'),
                        $input->get('email_provider')
                    );

                    if ($update == 1) {
                        $response = [
                            'status' => 'success',
                            'message' => 'Smtp settings has been updated successfully',
                            'redirect' => 'admin/email'
                        ];
                    } else {
                        $response = [
                            'status' => 'error',
                            'message' => 'No changes have been made to the settings.',
                        ];
                    }
                }catch (Exception $e) {
                    $response = [
                        'status' => 'error',
                        'message' => $e->getMessage()
                    ];
                }
            } else {
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

        return ['content' => $this->view->render($data, 'admin/email/email-settings')];
    }

    /**
     * templates
     */
    public function templates(): array
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];
        
        /* Use Admin Library */
        $admin = $this->library('Admin');
        $data['admin'] = $admin->data();

        // If not logged in, redirect to login page
        if(!$admin->isLoggedIn()):
            redirect('admin/login');
        endif;

        /* Use Input Library */
        $input = $this->library('Input');
        
        /* Use Admin Model */
        $adminModel = $this->model('Admin');
        
        /* Use Settings Model */
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();

        if ($input->isAjax() && $input->get('page')) {
            $page = $input->get('page');
            $templates = $adminModel->getEmailTemplatesWithPagination($page);

            // Return the JSON response
            header('Content-Type: application/json');
            echo json_encode(['templates' => $templates]);
            exit();
        } else {

            $data['templates'] = $adminModel->getEmailTemplates();
        }

        if (isset($this->url[2]) && $this->url[2] == 'edit-template') {

            // check if the url is set and the email template exisits
            if (!isset($this->url[3])  || !intval($this->url[3]) || !$adminModel->hasEmailTemplate($this->url[3])) {
                $_SESSION['message'][] = ['error', 'Failed to fetch email details. Please try again later.'];
                redirect('admin/templates');
            }

            // If email exists, get its details
            $data["get-email-details"] = $adminModel->getTemplateDetails($this->url[3]);

            // Check if the form is submitted via POST method
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                // Validate form input
                $validator = $this->library('Validator');

                $validation = $validator->check($_POST, [
                    'name' => [
                        'required' => true
                    ],
                    'subject' => [
                        'required' => true
                    ],
                    'email_status' => [
                        'required' => true
                    ]
                ]);

                // If validation passes, update email details
                if (!$validation->fails()) {
                    try {
                        $update = $adminModel->editTemplate(
                            $input->get('id'),
                            $input->get('name'),
                            $input->get('subject'),
                            $input->get('email_status')
                        );

                        if ($update == 1) {
                            // Email details updated successfully
                            $response = [
                                'status' => 'success',
                                'message' => 'Email details updated successfully.',
                            ];
                        } else {
                            // No changes were made to email details
                            $response = [
                                'status' => 'error',
                                'message' => 'No changes were made to this email details.',
                            ];
                        }
                    } catch (Exception $e) {
                        // Error occurred while updating email details
                        $response = [
                            'status' => 'error',
                            'message' => $e->getMessage()
                        ];
                    }
                } else {
                    // Validation fails, prepare error messages
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

                // Send JSON response
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }

            return ['content' => $this->view->render($data, 'admin/email/edit-template')];
        }

        // render email templates
        return ['content' => $this->view->render($data, 'admin/email/templates')];
    }

    /**
     * logo
     */
    public function logo(): array
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];
        
        /* Use Admin Library */
        $admin = $this->library('Admin');
        // Get admin data
        $data['admin'] = $admin->data();

        /* Is logged-in */
        // If not logged in, redirect to login page
        if(!$admin->isLoggedIn()):
            redirect('admin/login');
        endif;

        /* Use Input Library */
        $input = $this->library('Input');
        
        /* Use Admin Model */
        $adminModel = $this->model('Admin');
        
        /* Use Settings Model */
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();

        // Process logo update form
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $response = []; // Initialize the $response variable

            // Check if input exists
            if ($input->exists()) {

                try {
                    // file formats
                    $validFormats = ["jpg", "jpeg", "png"];

                    // Retrieve file details
                    $name = $_FILES['photoimg']['name'];
                    $size = $_FILES['photoimg']['size'];

                    if (!empty($name)) {

                        $fileFormat = pathinfo($_FILES['photoimg']['name'], PATHINFO_EXTENSION);

                        if ($_FILES['photoimg']['error'] === UPLOAD_ERR_OK) {
                            if (in_array($fileFormat, $validFormats)) {

                                // Check file size
                                if ($size <= 2097152) { // 2MB in bytes

                                    $fileName = $this->rando() . '.' . $fileFormat;

                                    // Path to upload directory
                                    $path = sprintf('%s/../../%s/%s/logo/', __DIR__, PUBLIC_PATH, UPLOADS_PATH);

                                    // Move uploaded file to destination
                                    if (move_uploaded_file($_FILES['photoimg']['tmp_name'], $path . $fileName)) {

                                        // Update logo with new image
                                        $update = $adminModel->updatelogo($fileName);
                                        
                                        // Check if the logo was updated
                                        if ($update == 1) {
                                            $response = [
                                                'status' => 'success',
                                                'redirect' => 'admin/logo',
                                                'message' => 'Logo has been updated successfully.'
                                            ];
                                        } else {
                                            $response = [
                                                'status' => 'error',
                                                'message' => 'Failed to update the site logo. Please try again.'
                                            ];
                                        }
                                    } else {
                                        $response = [
                                            'status' => 'error',
                                            'message' => 'Unable to upload the image. Please try again.'
                                        ];
                                    }
                                } else {
                                    $response = [
                                        'status' => 'warning',
                                        'message' => 'File size exceeds the maximum limit of 2MB.'
                                    ];
                                }
                            } else {
                                $response = [
                                    'status' => 'warning',
                                    'message' => 'Allowed file extensions: jpg, jpeg, png'
                                ];
                            }
                        } else {
                            $response = [
                                'status' => 'warning',
                                'message' => 'Unable to upload the document, please try again.'
                            ];
                        }
                    } else {
                        // No image selected
                        $response = [
                            'status' => 'error',
                            'message' => 'No image was selected. Please try again.'
                        ];
                    }
                } catch (Exception $e) {
                    // If an exception occurs
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

        // Render the logo settings view with data
        return ['content' => $this->view->render($data, 'admin/site/logo')];
    }

    /**
     * favicon
     */
    public function favicon(): array 
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];
        
        /* Use Admin Library */
        $admin = $this->library('Admin');
        // Get admin data
        $data['admin'] = $admin->data();

        /* Is logged-in */
        // If not logged in, redirect to login page
        if(!$admin->isLoggedIn()):
            redirect('admin/login');
        endif;

        /* Use Input Library */
        $input = $this->library('Input');
        
        /* Use Admin Model */
        $adminModel = $this->model('Admin');
        
        /* Use Settings Model */
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();

        // Process logo update form
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $response = []; // Initialize the $response variable

            // Check if input exists
            if ($input->exists()) {

                try {
                    // file formats
                    $validFormats = ["jpg", "jpeg", "png"];

                    // Retrieve file details
                    $name = $_FILES['photoimg']['name'];
                    $size = $_FILES['photoimg']['size'];

                    if (!empty($name)) {

                        $fileFormat = pathinfo($_FILES['photoimg']['name'], PATHINFO_EXTENSION);

                        if ($_FILES['photoimg']['error'] === UPLOAD_ERR_OK) {
                            if (in_array($fileFormat, $validFormats)) {

                                // Check file size
                                if ($size <= 2097152) { // 2MB in bytes

                                    $fileName = $this->rando() . '.' . $fileFormat;

                                    // Path to upload directory
                                    $path = sprintf('%s/../../%s/%s/logo/', __DIR__, PUBLIC_PATH, UPLOADS_PATH);

                                    // Move uploaded file to destination
                                    if (move_uploaded_file($_FILES['photoimg']['tmp_name'], $path . $fileName)) {

                                        // Update logo with new image
                                        $update = $adminModel->updatefavicon($fileName);
                                        
                                        // Check if the logo was updated
                                        if ($update == 1) {
                                            $response = [
                                                'status' => 'success',
                                                'redirect' => 'admin/logo',
                                                'message' => 'Favicon has been updated successfully.'
                                            ];
                                        } else {
                                            $response = [
                                                'status' => 'error',
                                                'message' => 'Failed to update the site favicon. Please try again.'
                                            ];
                                        }
                                    } else {
                                        $response = [
                                            'status' => 'error',
                                            'message' => 'Unable to upload the image. Please try again.'
                                        ];
                                    }
                                } else {
                                    $response = [
                                        'status' => 'warning',
                                        'message' => 'File size exceeds the maximum limit of 2MB.'
                                    ];
                                }
                            } else {
                                $response = [
                                    'status' => 'warning',
                                    'message' => 'Allowed file extensions: jpg, jpeg, png'
                                ];
                            }
                        } else {
                            $response = [
                                'status' => 'warning',
                                'message' => 'Unable to upload the document, please try again.'
                            ];
                        }
                    } else {
                        // No image selected
                        $response = [
                            'status' => 'error',
                            'message' => 'No image was selected. Please try again.'
                        ];
                    }
                } catch (Exception $e) {
                    // If an exception occurs
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

        // Render the logo settings view with data
        return ['content' => $this->view->render($data, 'admin/site/logo')];
    }

    /**
     * extensions
     */
    public function extensions(): array
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];
        
        /* Use Admin Library */
        $admin = $this->library('Admin');
        $data['admin'] = $admin->data();

        // If not logged in, redirect to login page
        if(!$admin->isLoggedIn()):
            redirect('admin/login');
        endif;

        /* Use Input Library */
        $input = $this->library('Input');
        
        /* Use Admin Model */
        $adminModel = $this->model('Admin');
        
        /* Use Settings Model */
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();

        $data['extensions'] = $adminModel->getExtensions();

        if (isset($this->url[2]) && $this->url[2] == 'edit-extension') {

            // check if the url is set and the extension exisit
            if (!isset($this->url[3]) || !intval($this->url[3]) || !$adminModel->hasExtensions($this->url[3])) {
                $_SESSION['message'][] = ['error', 'Failed to fetch extension details. Please try again later.'];
                redirect('admin/extensions');
            }

            // If extensions exist, get its details
            $data["get-extension"] = $adminModel->getExtensionsDetails($this->url[3]);

            // Check if the form is submitted via POST method
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                // Validate form input
                $validator = $this->library('Validator');

                $validation = $validator->check($_POST, [
                    'name' => [
                        'required' => true
                    ],
                    'script' => [
                        'required' => true
                    ],
                    'status' => [
                        'required' => true
                    ]
                ]);

                // If validation passes, update extensions details
                if (!$validation->fails()) {
                    try {
                        $update = $adminModel->editExtensions(
                            $input->get('id'),
                            $input->get('name'),
                            $input->get('script'),
                            $input->get('status')
                        );

                        if ($update == 1) {
                            // extensions details updated successfully
                            $response = [
                                'status' => 'success',
                                'message' => 'Extensions details updated successfully.',
                            ];
                        } else {
                            // No changes were made to extension details
                            $response = [
                                'status' => 'error',
                                'message' => 'No changes were made to this extension details.',
                            ];
                        }
                    } catch (Exception $e) {
                        // Error occurred while updating extension details
                        $response = [
                            'status' => 'error',
                            'message' => $e->getMessage()
                        ];
                    }
                } else {
                    // Validation fails, prepare error messages
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

                // Send JSON response
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }

            return ['content' => $this->view->render($data, 'admin/extensions/edit-extension')];
        }

        // render email extensions
        return ['content' => $this->view->render($data, 'admin/extensions/get-extensions')];
    }

    /**
     * maintenance
     */
    public function maintenance(): array
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];
        
        /* Use Admin Library */
        $admin = $this->library('Admin');
        $data['admin'] = $admin->data();

        // If not logged in, redirect to login page
        if(!$admin->isLoggedIn()):
            redirect('admin/login');
        endif;

        /* Use Input Library */
        $input = $this->library('Input');
        
        /* Use Admin Model */
        $adminModel = $this->model('Admin');
        
        /* Use Settings Model */
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();

        $data['maintenance'] = $adminModel->getMaintenanceDetails();

        // Check if the form is submitted via POST method
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Validate form input
            $validator = $this->library('Validator');

            $validation = $validator->check($_POST, [
                'details' => [
                    'required' => true
                ],
                'maintenance_mode' => [
                    'required' => true
                ]
            ]);

            // If validation passes, update maintenance mode
            if (!$validation->fails()) {
                try {
                    $update = $adminModel->setMaintenanceMode(
                        $input->get('details'),
                        $input->get('maintenance_mode')
                    );

                    if ($update == 1) {
                        // maintenance mode updated successfully
                        $response = [
                            'status' => 'success',
                            'message' => 'Maintenance Mode has been set successfully.',
                        ];
                    } else {
                        // No changes were made to maintenance mode
                        $response = [
                            'status' => 'error',
                            'message' => 'No changes were made to the site settings.',
                        ];
                    }
                } catch (Exception $e) {
                    // Error occurred while updating extension details
                    $response = [
                        'status' => 'error',
                        'message' => $e->getMessage()
                    ];
                }
            } else {
                // Validation fails, prepare error messages
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

            // Send JSON response
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }

        // render maintenance
        return ['content' => $this->view->render($data, 'admin/site/maintenance')];
    }

    /**
     * seo
     */
    public function seo(): array
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];

        /* Use Admin Library */
        $admin = $this->library('Admin');
        $data['admin'] = $admin->data();

        // If not logged in, redirect to login page
        if(!$admin->isLoggedIn()):
            redirect('admin/login');
        endif;

        /* Use Input Library */
        $input = $this->library('Input');
        
        /* Use Admin Model */
        $adminModel = $this->model('Admin');

        /* Use Settings Model */
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();

        // Process edit profile form
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $response = []; // Initialize the $response variable

            // Check if input exists
            if ($input->exists()) {

                // Load Validator library
                $validator = $this->library('Validator');

                // Perform validation on $_POST data
                $validation = $validator->check($_POST, [
                    'title' => [
                        'required' => true
                    ],
                    'keywords' => [
                        'required' => true
                    ],
                    'description' => [
                        'required' => true
                    ]
                ]);

                // If validation passes
                if (!$validation->fails()) {
                    try {

                        // file formats
                        $validFormats = ["jpg", "jpeg", "png"];

                        // Retrieve file details
                        $name = $_FILES['photoimg']['name'];
                        $size = $_FILES['photoimg']['size'];

                        if (!empty($name)) {

                            $fileFormat = pathinfo($_FILES['photoimg']['name'], PATHINFO_EXTENSION);

                            if ($_FILES['photoimg']['error'] === UPLOAD_ERR_OK) {
                                if (in_array($fileFormat, $validFormats)) {

                                    // Check file size
                                    if ($size <= 2097152) { // 2MB in bytes

                                        $fileName = $this->rando() . '.' . $fileFormat;

                                        // Get the image type
                                        $image_info = getimagesize($_FILES['photoimg']['tmp_name']);
                                        $image_type = $image_info[2];

                                        // image
                                        $image = '';

                                        // Create image based on an image type
                                        switch ($image_type) {
                                            case IMAGETYPE_JPEG:
                                                $image = imagecreatefromjpeg($_FILES['photoimg']['tmp_name']);
                                                break;
                                            case IMAGETYPE_PNG:
                                                $image = imagecreatefrompng($_FILES['photoimg']['tmp_name']);
                                                break;
                                            // Add more cases as needed for other image types
                                            default:
                                                break;
                                        }

                                        // Resize the image to 800x800
                                        $resized_image = imagescale($image, 800, 800);

                                        // Path to upload directory
                                        $path = sprintf('%s/../../%s/%s/seo/', __DIR__, PUBLIC_PATH, UPLOADS_PATH);

                                        // Save the resized image to a file
                                        if (imagejpeg($resized_image, $path . $fileName)) {

                                            // Update seo with new image
                                            $update = $adminModel->UpdateSeo($fileName, $input->get('title'), $input->get('keywords'), $input->get('description'));
                                            
                                            // Check if the profile was updated
                                            if ($update == 1) {
                                                $response = [
                                                    'status' => 'success',
                                                    'redirect' => 'admin/seo',
                                                    'message' => 'Seo settings has been updated successfully'
                                                ];
                                            } else {
                                                $response = [
                                                    'status' => 'error',
                                                    'message' => 'No changes have been made to the seo settings.'
                                                ];
                                            }
                                        } else {
                                            $response = [
                                                'status' => 'error',
                                                'message' => 'Unable to upload the seo image. Please try again.'
                                            ];
                                        }

                                        // Free up memory
                                        imagedestroy($image);
                                        imagedestroy($resized_image);
                                    } else {
                                        $response = [
                                            'status' => 'warning',
                                            'message' => 'File size exceeds the maximum limit of 2MB.'
                                        ];
                                    }
                                } else {
                                    $response = [
                                        'status' => 'warning',
                                        'message' => 'Allowed file extensions: jpg, jpeg, png'
                                    ];
                                }
                            } else {
                                $response = [
                                    'status' => 'warning',
                                    'message' => 'Unable to upload the document, please try again.'
                                ];
                            }
                        } else {
                            // No image selected
                            $update = $adminModel->UpdateSeoNoImage($input->get('title'), $input->get('keywords'), $input->get('description'));
                                            
                            // Check if the profile was updated
                            if ($update == 1) {
                                $response = [
                                    'status' => 'success',
                                    'redirect' => 'admin/seo',
                                    'message' => 'Seo settings has been updated successfully'
                                ];
                            } else {
                                $response = [
                                    'status' => 'error',
                                    'message' => 'No changes have been made to the seo settings.'
                                ];
                            } 
                        }
                    } catch (Exception $e) {
                        // If an exception occurs
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
            }

            // Send the JSON response
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }

        // Render the edit profile view with data
        return ['content' => $this->view->render($data, 'admin/site/seo')];
    }

    /**
     * cron
     */
    public function cron(): array
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];
        
        /* Use Admin Library */
        $admin = $this->library('Admin');
        $data['admin'] = $admin->data();

        // If not logged in, redirect to login page
        if(!$admin->isLoggedIn()):
            redirect('admin/login');
        endif;

        /* Use Settings Model */
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();

        // render cron templates
        return ['content' => $this->view->render($data, 'admin/cron')];
    }

    /**
     * ranking
     */
    public function ranking(): array
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];
        
        /* Use Admin Library */
        $admin = $this->library('Admin');
        $data['admin'] = $admin->data();

        // If not logged in, redirect to login page
        if(!$admin->isLoggedIn()):
            redirect('admin/login');
        endif;

        /* Use Input Library */
        $input = $this->library('Input');

        /* Use Referral Model */
        $referralModel = $this->model('Referral');

        if ($input->isAjax() && $input->get('page')) {
            $page = $input->get('page');
            $ranks = $referralModel->getRankingWithPagination($page);

            // Return the JSON response
            header('Content-Type: application/json');
            echo json_encode(['ranks' => $ranks]);
            exit();
        } else {
            $data['ranks'] = $referralModel->getRanks();
        }

        if (isset($this->url[2]) && $this->url[2] == 'add-ranking') {
            // Check if the form is submitted via POST method
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                // Validate form input
                $validator = $this->library('Validator');

                $validation = $validator->check($_POST, [
                    'name' => [
                        'required' => true
                    ],
                    'min_invest' => [
                        'required' => true
                    ],
                    'min_referral' => [
                        'required' => true
                    ],
                    'bonus' => [
                        'required' => true
                    ],
                    'status' => [
                        'required' => true
                    ]
                ]);

                // If validation passes, update rank details
                if (!$validation->fails()) {
                    try {
                        // file formats
                        $validFormats = ["jpg", "jpeg", "png"];

                        // Retrieve file details
                        $name = $_FILES['photoimg']['name'];
                        $size = $_FILES['photoimg']['size'];

                        if (!empty($name)) {

                            $fileFormat = pathinfo($_FILES['photoimg']['name'], PATHINFO_EXTENSION);

                            if ($_FILES['photoimg']['error'] === UPLOAD_ERR_OK) {
                                if (in_array($fileFormat, $validFormats)) {

                                    // Check file size
                                    if ($size <= 2097152) { // 2MB in bytes

                                        $fileName = $this->rando() . '.' . $fileFormat;
                                        $rankingId = $this->uniqueid();

                                        // Path to upload directory
                                        $path = sprintf('%s/../../%s/%s/ranks/', __DIR__, PUBLIC_PATH, UPLOADS_PATH);

                                        // Move uploaded file to destination
                                        if (move_uploaded_file($_FILES['photoimg']['tmp_name'], $path . $fileName)) {

                                            // insert rank with image
                                            $insert = $referralModel->addRank(
                                                $rankingId,
                                                $fileName,
                                                $input->get('name'),
                                                $input->get('min_invest'),
                                                $input->get('min_referral'),
                                                $input->get('bonus'),
                                                $input->get('status')
                                            );
                                            
                                            // Check if the rank was updated
                                            if ($insert == 1) {
                                                // rank details rank successfully
                                                $response = [
                                                    'status' => 'success',
                                                    'redirect' => 'admin/ranking',
                                                    'message' => 'Ranking has been added successfully.',
                                                ];
                                            } else {
                                                // No changes were made to rank details
                                                $response = [
                                                    'status' => 'error',
                                                    'message' => 'An error occurred while saving rank details..'
                                                ];
                                            }
                                        } else {
                                            $response = [
                                                'status' => 'error',
                                                'message' => 'Unable to upload the image. Please try again.'
                                            ];
                                        }
                                    } else {
                                        $response = [
                                            'status' => 'warning',
                                            'message' => 'File size exceeds the maximum limit of 2MB.'
                                        ];
                                    }
                                } else {
                                    $response = [
                                        'status' => 'warning',
                                        'message' => 'Allowed file extensions: jpg, jpeg, png'
                                    ];
                                }
                            } else {
                                $response = [
                                    'status' => 'warning',
                                    'message' => 'Unable to upload the document, please try again.'
                                ];
                            }
                        } else {
                            // No image selected
                            $response = [
                                'status' => 'error',
                                'message' => 'No image was selected. Please try again.'
                            ];
                        }
                    } catch (Exception $e) {
                        // If an exception occurs
                        $response = [
                            'status' => 'error',
                            'message' => $e->getMessage()
                        ];
                    }
                } else {
                    // Validation fails, prepare error messages
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

                // Send JSON response
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }
        }elseif (isset($this->url[2]) && $this->url[2] == 'edit-ranking') {

            // check if the url is set and the extension exisit
            if (!isset($this->url[3]) || !intval($this->url[3]) || !$referralModel->hasRank($this->url[3])) {
                $_SESSION['message'][] = ['error', 'Failed to fetch ranking details. Please try again later.'];
                redirect('admin/ranking');
            }

            $rankingId = $this->url[3];

            // If the ranking exists, get its details
            $data["get-rank"] = $referralModel->getRankDetails($this->url[3]);

            // Check if the form is submitted via POST method
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                // Validate form input
                $validator = $this->library('Validator');

                $validation = $validator->check($_POST, [
                    'name' => [
                        'required' => true
                    ],
                    'min_invest' => [
                        'required' => true
                    ],
                    'min_referral' => [
                        'required' => true
                    ],
                    'bonus' => [
                        'required' => true
                    ],
                    'status' => [
                        'required' => true
                    ]
                ]);

                // If validation passes, update rank details
                if (!$validation->fails()) {
                    try {
                        // file formats
                        $validFormats = ["jpg", "jpeg", "png"];

                        // Retrieve file details
                        $name = $_FILES['photoimg']['name'];
                        $size = $_FILES['photoimg']['size'];

                        if (!empty($name)) {

                            $fileFormat = pathinfo($_FILES['photoimg']['name'], PATHINFO_EXTENSION);

                            if ($_FILES['photoimg']['error'] === UPLOAD_ERR_OK) {
                                if (in_array($fileFormat, $validFormats)) {

                                    // Check file size
                                    if ($size <= 2097152) { // 2MB in bytes

                                        $fileName = $this->rando() . '.' . $fileFormat;

                                        // Path to upload directory
                                        $path = sprintf('%s/../../%s/%s/ranks/', __DIR__, PUBLIC_PATH, UPLOADS_PATH);

                                        // Move uploaded file to destination
                                        if (move_uploaded_file($_FILES['photoimg']['tmp_name'], $path . $fileName)) {

                                            // Update rank with new image
                                            $update = $referralModel->editRank(
                                                $rankingId,
                                                $fileName,
                                                $input->get('name'),
                                                $input->get('min_invest'),
                                                $input->get('min_referral'),
                                                $input->get('bonus'),
                                                $input->get('status')
                                            );
                                            
                                            // Check if the rank was updated
                                            if ($update == 1) {
                                                // rank details rank successfully
                                                $response = [
                                                    'status' => 'success',
                                                    'redirect' => 'admin/ranking/edit-ranking/' . $rankingId,
                                                    'message' => 'Ranking details updated successfully.',
                                                ];
                                            } else {
                                                // No changes were made to rank details
                                                $response = [
                                                    'status' => 'error',
                                                    'message' => 'No changes were made to this rank.'
                                                ];
                                            }
                                        } else {
                                            $response = [
                                                'status' => 'error',
                                                'message' => 'Unable to upload the image. Please try again.'
                                            ];
                                        }
                                    } else {
                                        $response = [
                                            'status' => 'warning',
                                            'message' => 'File size exceeds the maximum limit of 2MB.'
                                        ];
                                    }
                                } else {
                                    $response = [
                                        'status' => 'warning',
                                        'message' => 'Allowed file extensions: jpg, jpeg, png'
                                    ];
                                }
                            } else {
                                $response = [
                                    'status' => 'warning',
                                    'message' => 'Unable to upload the document, please try again.'
                                ];
                            }
                        } else {
                            // No image selected
                            $update = $referralModel->editRankNoImage(
                                $rankingId,
                                $input->get('name'),
                                $input->get('min_invest'),
                                $input->get('min_referral'),
                                $input->get('bonus'),
                                $input->get('status')
                            );
                            
                            // Check if the rank was updated
                            if ($update == 1) {
                                // rank details rank successfully
                                $response = [
                                    'status' => 'success',
                                    'redirect' => 'admin/ranking/edit-ranking/' . $rankingId,
                                    'message' => 'Ranking details updated successfully.',
                                ];
                            } else {
                                // No changes were made to rank details
                                $response = [
                                    'status' => 'error',
                                    'message' => 'No changes were made to this rank.'
                                ];
                            }
                        }
                    } catch (Exception $e) {
                        // If an exception occurs
                        $response = [
                            'status' => 'error',
                            'message' => $e->getMessage()
                        ];
                    }
                } else {
                    // Validation fails, prepare error messages
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

                // Send JSON response
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }

            return ['content' => $this->view->render($data, 'admin/user-ranking/edit-ranking')];
        }

        // render user-ranking templates
        return ['content' => $this->view->render($data, 'admin/user-ranking/ranking')];
    }

    /**
     * users
     */
    public function users(): array
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];

        /* Use Admin Library */
        $admin = $this->library('Admin');
        $data['admin'] = $admin->data();

        // If not logged in, redirect to login page
        if(!$admin->isLoggedIn()):
            redirect('admin/login');
        endif;

        /* Use Input Library */
        $input = $this->library('Input');

        /* Use Admin Model */
        $adminModel = $this->model('Admin');
        $referralModel = $this->model('Referral');

        // User Models
        $userModel = $this->model('User');

        $session = $this->library('Session');

        /* Use Settings Model */
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();

        $data['gateways'] = $settingsModel->getAllDepositMethod(); 
        $data['withdrawal-gateways'] = $settingsModel->getAllWithdrawMethods();
        $data['plans'] = $adminModel->plans();
        $data['ranks'] = $referralModel->getRanks();

        // Fetch the email template with id = 3, 4, 22 & 23, 24, 26, 27, 28, 29
        $data['email-templates'] = $settingsModel->getEmailTemplate();
        $resetTemplate = $data['email-templates'][3] ?? null;
        $welcomeTemplate = $data['email-templates'][4] ?? null;
        $addMoneyTemplate = $data['email-templates'][22] ?? null;
        $removeMoneyTemplate = $data['email-templates'][23] ?? null;
        $notificationTemplate = $data['email-templates'][24] ?? null;
        $approveAddressTemplate = $data['email-templates'][26] ?? null;
        $rejectAddressTemplate = $data['email-templates'][27] ?? null;
        $approveIdentityTemplate = $data['email-templates'][28] ?? null;
        $rejectIdentityTemplate = $data['email-templates'][29] ?? null;

        $response = [];

        // GET request to fetch search results.
        if (isset($_GET['search'])) {
            $searchTerm = strtolower(filter_input(INPUT_GET, 'search', FILTER_SANITIZE_STRING));

            // Perform the user search using $searchTerm
            $data['users'] = $adminModel->findUsers($searchTerm);

            // render template
            return ['content' => $this->view->render($data, 'admin/users/all-users')];
        }

        // Ajax request to load more users
        if ($input->isAjax() && $input->get('page')) {
            $page = $input->get('page');
            $users = $adminModel->getUsersWithPagination($page);

            // Return the JSON response
            header('Content-Type: application/json');
            echo json_encode(['users' => $users]);
            exit();
        } else {
            $data['users'] = $adminModel->getUsers();
        }

        if (isset($this->url[2]) && $this->url[2] == 'add-user') {

            // Check if the form is submitted via POST method
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                // Validate form input
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

                // If validation passes, insert user details
                if (!$validation->fails()) {
                    try {
                        // Check if admin registered email exisits
                        $hasAdminEmail = $adminModel->hasAdminEmail($input->get('email'));

                        if ($hasAdminEmail) {
                            $response = [
                                'status' => 'warning',
                                'message' => 'This email is registered to another user.',
                            ];
                        }else{

                            // Check if the email is already registered
                            $hasEmail = $adminModel->hasEmail($input->get('email'));

                            if (!$hasEmail) {

                                // Check if the phone number is already registered
                                $has_phone = $adminModel->hasPhone($input->get('formattedPhone'));

                                if (!$has_phone) {
                                    // Check if passwords match
                                    if ($input->get('password') === $input->get('confirmPassword')) {

                                        // Hash password
                                        $password = password_hash($input->get('password'), PASSWORD_DEFAULT);

                                        // Generate unique user ID
                                        $userid = $this->uniqueid();

                                        // Register user
                                        $insert = $adminModel->registerUser(
                                            $userid,
                                            $password,
                                            $input->get('email'),
                                            $input->get('firstname'),
                                            $input->get('lastname'),
                                            $input->get('formattedPhone'),
                                            $input->get('country')
                                        );

                                        // Registeration was successful
                                        if ($insert == 1) {

                                            // Generate QR code for the user
                                            $qrCodeContent = getenv('URL_PATH') . '/register/?ref=' . $userid;

                                            if (qrhelper::createQR($qrCodeContent, $userid)) {

                                                $qr_image = $userid . '_qrcode.png';
                                                
                                                // update user's account with qr code
                                                $update = $adminModel->add_account($userid, $qr_image);

                                                // Send welcome email if account update is successful
                                                if ($update == 1) {

                                                    $data["user"] = $adminModel->getUserDetails($userid);

                                                    // Check if signup bonus is active
                                                    if ($data['settings']['signup_bonus_control'] == 1) {
                                                        // Calculate new balance
                                                        $bonus = $data["user"]['interest_wallet'] + $data['settings']['signup_bonus_amount'];
                                                        
                                                        // Give user a signup bonus
                                                        $adminModel->add_bonus($userid, $bonus);
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

                                                            if (emailhelper::sendEmail($data['settings'], $recipientEmail, $welcomeSubject, $welcomeBody)) {
                                                                $response = [
                                                                    'status' => 'success',
                                                                    'message' => 'This user account has been registered successfully.',
                                                                    'redirect' => 'admin/users'
                                                                ];
                                                            } else {
                                                                $response = [
                                                                    'status' => 'error',
                                                                    'message' => 'Registered, but failed to send notification emails.',
                                                                    'redirect' => 'admin/users'
                                                                ];
                                                            }
                                                        }else{
                                                            $response = [
                                                                'status' => 'success',
                                                                'message' => 'This user account has been registered successfully.',
                                                                'redirect' => 'admin/users'
                                                            ];
                                                        }
                                                    }else{
                                                        $response = [
                                                            'status' => 'success',
                                                            'message' => 'This user account has been registered successfully.',
                                                            'redirect' => 'admin/users'
                                                        ];
                                                    }
                                                }else{
                                                    $response = [
                                                        'status' => 'warning',
                                                        'message' => 'Error occurred while saving data.',
                                                    ];
                                                }
                                            }else{
                                                $response = [
                                                    'status' => 'warning',
                                                    'message' => 'We were unable to create a QR code for this profile.',
                                                ];
                                            }
                                        } else {
                                            $response = [
                                                'status' => 'warning',
                                                'message' => 'Error occurred while saving data., try again.',
                                            ];
                                        }
                                    }else{
                                        // Passwords mismatch
                                        $response = [
                                            'status' => 'warning',
                                            'message' => 'Your passwords do not match. Please try again.'
                                        ];
                                    }
                                }else{
                                    // Phone number already registered
                                    $response = [
                                        'status' => 'warning',
                                        'message' => 'This phone number is registered to another user.',
                                    ];
                                }
                            }else{
                                // Email already registered
                                $response = [
                                    'status' => 'warning',
                                    'message' => 'This email is registered to another user.',
                                ];
                            }
                        }
                    } catch (Exception $e) {
                        // Error occurred while updating extension details
                        $response = [
                            'status' => 'warning',
                            'message' => $e->getMessage()
                        ];
                    }
                } else {
                    // Validation fails, prepare error messages
                    $errors = $validation->errors()->all();
                    $errorMessages = [];

                    foreach ($errors as $err) {
                        foreach ($err as $r) {
                            $errorMessages[] = $r;
                        }
                    }

                    $response = [
                        'status' => 'warning',
                        'message' => $errorMessages
                    ];
                }

                // Send JSON response
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }

            return ['content' => $this->view->render($data, 'admin/users/add-user')];
        }elseif (isset($this->url[2]) && $this->url[2] == 'add-funds') {
            // Check if the request method is POST
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Load the Validator library
                $validator = $this->library('Validator');

                // Validate the POST data
                $validation = $validator->check($_POST, [
                    'amount' => [
                        'required' => true,
                        'float' => true
                    ],
                    'userid' => [
                        'required' => true,
                        'digit' => true
                    ]
                ]);

                // Check if validation fails
                if ($validation->fails()) {
                    // Gather error messages
                    $errors = $validation->errors()->all();
                    $errorMessages = [];

                    // Flatten the error messages array
                    foreach ($errors as $err) {
                        foreach ($err as $r) {
                            $errorMessages[] = $r;
                        }
                    }

                    // Prepare error response
                    $response = [
                        'status' => 'error',
                        'message' => $errorMessages
                    ];
                } else {
                    // Validation passed, proceed with adding funds
                    try {

                        // Get user details
                        $user = $adminModel->getUserDetails($input->get('userid'));

                        // user is banned
                        if ($user['status'] == 2) {
                            $response = [
                                'status' => 'error',
                                'message' => 'This user is currently suspended.',
                            ];
                        }else{

                            // Add funds to the user's account
                            $update = $adminModel->addFunds($input->get('userid'), $input->get('amount'));

                            // Check if funds were successfully added
                            if ($update == 1) {
                                // email notification is enabled
                                if ($data['settings']["email_notification"] == 1) {

                                    $siteName = $data['settings']['sitename'];
                                    $siteLogo = $data['settings']['logo'];
                                    $siteUrl = getenv('URL_PATH');
                                    $dateNow = date('Y');

                                    // add money template is enabled
                                    if ($addMoneyTemplate !== null && $addMoneyTemplate['status'] == 1) {

                                        // Replace placeholders in email body
                                        $addMoneyTemplate['body'] = str_replace(
                                            ['{FIRSTNAME}', '{LASTNAME}', '{CURRENCY}', '{AMOUNT}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'],
                                            [$user['firstname'], $user['lastname'], $user['currency'], $input->get('amount'), $siteName, $siteLogo, $siteUrl, $dateNow],
                                            $addMoneyTemplate['body']
                                        );

                                        $recipientEmail = $user['email'];
                                        $addMoneySubject = $addMoneyTemplate['subject'];
                                        $addMoneyBody = $addMoneyTemplate['body'];

                                        if (emailhelper::sendEmail($data['settings'], $recipientEmail, $addMoneySubject, $addMoneyBody)) {
                                            $response = [
                                                'status' => 'success',
                                                'message' => 'The funds have been successfully added to the user account.'
                                            ];
                                        } else {
                                            $response = [
                                                'status' => 'warning',
                                                'message' => 'Funds added, but failed to send notification emails.'
                                            ];
                                        }
                                    }else{
                                        $response = [
                                            'status' => 'success',
                                            'message' => 'The funds have been successfully added to the user account.'
                                        ];
                                    }
                                }else{
                                    $response = [
                                        'status' => 'success',
                                        'message' => 'The funds have been successfully added to the user account.'
                                    ];
                                }
                            } else {
                                // Funds could not be added, prepare error response
                                $response = [
                                    'status' => 'error',
                                    'message' => 'An error occurred while adding funds to the user account.',
                                ];
                            }
                        }
                    } catch (Exception $e) {
                        // Error occurred during transaction, prepare error response
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
                // Redirect if request method is not POST
                redirect('admin/users');
            }
        }elseif (isset($this->url[2]) && $this->url[2] == 'remove-funds') {
            // Check if the request method is POST
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Load the Validator library
                $validator = $this->library('Validator');

                // Validate the POST data
                $validation = $validator->check($_POST, [
                    'amount' => [
                        'required' => true,
                        'float' => true
                    ],
                    'userid' => [
                        'required' => true,
                        'digit' => true
                    ]
                ]);

                // Check if validation fails
                if ($validation->fails()) {
                    // Gather error messages
                    $errors = $validation->errors()->all();
                    $errorMessages = [];

                    // Flatten the error messages array
                    foreach ($errors as $err) {
                        foreach ($err as $r) {
                            $errorMessages[] = $r;
                        }
                    }

                    // Prepare error response
                    $response = [
                        'status' => 'error',
                        'message' => $errorMessages
                    ];
                } else {
                    // Validation passed, proceed with removing funds
                    try {
                        // Get user details
                        $user = $adminModel->getUserDetails($input->get('userid'));

                        // user is banned
                        if ($user['status'] == 2) {
                            $response = [
                                'status' => 'error',
                                'message' => 'This user is currently suspended.',
                            ];
                        }else{
                            // Check if the user's account is already empty
                            if ($user['interest_wallet'] == 0) {
                                $response = [
                                    'status' => 'error',
                                    'message' => 'This user\'s account is already empty.'
                                ];
                            } else {
                                // Check if there is sufficient balance in the user's wallet
                                if ($user['interest_wallet'] < $input->get('amount')) {
                                    $response = [
                                        'status' => 'error',
                                        'message' => 'Insufficient balance. The amount exceeds the user\'s balance.'
                                    ];
                                } else {
                                    // Calculate new balance after removing funds
                                    $newBalance = $user['interest_wallet'] - $input->get('amount');

                                    // Remove funds from the user's account
                                    $update = $adminModel->removeFunds($input->get('userid'), $input->get('amount'), $newBalance);

                                    // Check if funds were successfully removed
                                    if ($update == 1) {
                                        // email notification is enabled
                                        if ($data['settings']["email_notification"] == 1) {

                                            $siteName = $data['settings']['sitename'];
                                            $siteLogo = $data['settings']['logo'];
                                            $siteUrl = getenv('URL_PATH');
                                            $dateNow = date('Y');

                                            // remove money template is enabled
                                            if ($removeMoneyTemplate !== null && $removeMoneyTemplate['status'] == 1) {

                                                // Replace placeholders in email body
                                                $removeMoneyTemplate['body'] = str_replace(
                                                    ['{FIRSTNAME}', '{LASTNAME}', '{CURRENCY}', '{AMOUNT}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'],
                                                    [$user['firstname'], $user['lastname'], $user['currency'], $input->get('amount'), $siteName, $siteLogo, $siteUrl, $dateNow],
                                                    $removeMoneyTemplate['body']
                                                );

                                                $recipientEmail = $user['email'];
                                                $removeMoneySubject = $removeMoneyTemplate['subject'];
                                                $removeMoneyBody = $removeMoneyTemplate['body'];

                                                if (emailhelper::sendEmail($data['settings'], $recipientEmail, $removeMoneySubject, $removeMoneyBody)) {
                                                    $response = [
                                                        'status' => 'success',
                                                        'message' => 'Funds have been successfully removed from the user\'s account.'
                                                    ];
                                                } else {
                                                    $response = [
                                                        'status' => 'warning',
                                                        'message' => 'Funds removed, but failed to send notification emails.'
                                                    ];
                                                }
                                            }else{
                                                $response = [
                                                    'status' => 'success',
                                                    'message' => 'Funds have been successfully removed from the user\'s account.'
                                                ];
                                            }
                                        }else{
                                            $response = [
                                                'status' => 'success',
                                                'message' => 'Funds have been successfully removed from the user\'s account.'
                                            ];
                                        }
                                    } else {
                                        // Funds could not be removed, prepare error response
                                        $response = [
                                            'status' => 'error',
                                            'message' => 'An error occurred while removing funds from the user\'s account.',
                                        ];
                                    }
                                }
                            }
                        }
                    } catch (Exception $e) {
                        // Error occurred during transaction, prepare error response
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
                // Redirect if request method is not POST
                redirect('admin/users');
            }
        }elseif (isset($this->url[2]) && $this->url[2] == 'send-email') {
            // Check if the form is submitted via POST method
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Validate form input
                $validator = $this->library('Validator');
                $validationRules = [
                    'email' => [
                        'required' => true, 
                        'email' => true
                    ],
                    'subject' => [
                        'required' => true
                    ],
                    'description' => [
                        'required' => true
                    ]
                ];

                $validation = $validator->check($_POST, $validationRules);

                // If validation fails, prepare error messages
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

                        $description = $_POST['description'];

                        $hasEmail = $adminModel->hasEmail($input->get('email'));

                        if ($hasEmail) {

                            $userData = $adminModel->getEmail($input->get('email'));

                            // user is banned
                            if ($userData['status'] == 2) {
                                $response = [
                                    'status' => 'error',
                                    'message' => 'This user is currently suspended.',
                                ];
                            }else{
                                // Check if email notification is enabled
                                if ($data['settings']["email_notification"] == "1") {

                                    $siteName = $data['settings']['sitename'];
                                    $siteLogo = $data['settings']['logo'];
                                    $siteUrl = getenv('URL_PATH');
                                    $dateNow = date('Y');
                                    
                                    // Check if the notification template is enabled
                                    if ($notificationTemplate !== null && $notificationTemplate['status'] == 1) {

                                        $notificationTemplate['body'] = str_replace(
                                            ['{FIRSTNAME}', '{LASTNAME}', '{MESSAGE}', '{SUBJECT}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'],
                                            [$userData['firstname'], $userData['lastname'], $description, $input->get('subject'), $siteName, $siteLogo, $siteUrl, $dateNow],
                                            $notificationTemplate['body']
                                        );

                                        // Send email with notification to the user
                                        $recipientEmail = $input->get('email');
                                        $subject = $input->get('subject');
                                        $body = $notificationTemplate['body'];

                                        if (emailhelper::sendEmail($data['settings'], $recipientEmail, $subject, $body)) {
                                            // Email sent successfully
                                            $response = [
                                                'status' => 'success',
                                                'message' => 'Email has been sent successfully'
                                            ];
                                        } else {
                                            // Failed to send email
                                            $response = [
                                                'status' => 'error',
                                                'message' => 'We failed to send you a notification. Please try again.'
                                            ];
                                        }
                                    } else {
                                        $response = [
                                            'status' => 'error',
                                            'message' => 'Email template is currently disabled. Please activate it.'
                                        ]; 
                                    }
                                } else {
                                    // Email notification is disabled
                                    $response = [
                                        'status' => 'error',
                                        'message' => 'Email notification is currently disabled. Please activate it.'
                                    ]; 
                                }
                            }
                        }else{
                            $response = [
                                'status' => 'error',
                                'message' => 'Sorry, this user email was not found.'
                            ]; 
                        }
                    } catch (Exception $e) {
                        // Error occurred while sending email
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
                // Redirect if request method is not POST
                redirect('admin/users');
            }
        }elseif (isset($this->url[2]) && $this->url[2] == 'reset-password') {

            // Check if the form is submitted via POST method
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Validate form input
                $validator = $this->library('Validator');
                $validationRules = [
                    'password' => [
                        'required' => true
                    ]
                ];

                $validation = $validator->check($_POST, $validationRules);

                // If validation fails, prepare error messages
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

                        // Get user details
                        $user = $adminModel->getUserDetails($input->get('userid'));

                        // user is banned
                        if ($user['status'] == 2) {
                            $response = [
                                'status' => 'error',
                                'message' => 'This user is currently suspended.',
                            ];
                        }else{

                            /* Hash Password */
                            $password = password_hash($input->get('password'), PASSWORD_DEFAULT);

                            // Reset user password
                            $update = $adminModel->resetUser(
                                $input->get('userid'),
                                $password
                            );

                            if ($update == 1) {

                                // email notification is enabled
                                if ($data['settings']["email_notification"] == 1) {

                                    $siteName = $data['settings']['sitename'];
                                    $siteLogo = $data['settings']['logo'];
                                    $siteUrl = getenv('URL_PATH');
                                    $dateNow = date('Y');

                                    // reset template is enabled
                                    if ($resetTemplate !== null && $resetTemplate['status'] == 1) {

                                        $resetTemplate['body'] = str_replace(['{FIRSTNAME}', '{LASTNAME}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'], [$user["firstname"], $user["lastname"], $siteName, $siteLogo, $siteUrl, $dateNow], $resetTemplate['body']);

                                        // Send email with success notification to user
                                        $recipientEmail = $user["email"];
                                        $subject = $resetTemplate['subject'];
                                        $body = $resetTemplate['body'];

                                        // If email is sent successfully
                                        if (emailhelper::sendEmail($data['settings'], $recipientEmail, $subject, $body)) {
                                            $response = [
                                                'status' => 'success',
                                                'message' => 'Password has been reset successfully.',
                                            ];
                                        } else {
                                            $response = [
                                                'status' => 'warning',
                                                'message' => 'The password reset was successful, but we were unable to send a notification.',
                                                'redirect' => 'user/dashboard'
                                            ];
                                        }
                                    }else{
                                        $response = [
                                            'status' => 'success',
                                            'message' => 'Password has been reset successfully.',
                                        ];
                                    }
                                }else{
                                    $response = [
                                        'status' => 'success',
                                        'message' => 'Password has been reset successfully.',
                                    ];
                                }
                            } else {
                                // Failed to update user password (likely because it matches the old password)
                                $response = [
                                    'status' => 'error',
                                    'message' => 'Password reset failed, please try again.',
                                ];
                            }
                        }
                    } catch (Exception $e) {
                        // Error occurred while sending email
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
                // Redirect if request method is not POST
                redirect('admin/users');
            }
        }elseif (isset($this->url[2]) && $this->url[2] == 'login-user') {
            // Check if the request method is POST
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $validator = $this->library('Validator');
                $validation = $validator->check($_POST, [
                    'email' => [
                        'required' => true, 
                        'email' => true
                    ]
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
                        $hasEmail = $adminModel->hasEmail($email);

                        if ($hasEmail) {

                            $userData = $adminModel->getEmail($email);
                            $session->put('waveUser', $userData["userid"]);

                            $response = [
                                'status' => 'success',
                                'redirect' => 'user/dashboard'
                            ];
                        }else{
                            $response = [
                                'status' => 'error',
                                'message' => 'Login failed, try again later.'
                            ];
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
            }else{
                // Redirect if request method is not POST
                redirect('admin/users');
            }
        }elseif (isset($this->url[2]) && $this->url[2] == 'block-user') {
            // Check if the request method is POST
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $validator = $this->library('Validator');
                $validation = $validator->check($_POST, [
                    'userid' => [
                        'required' => true
                    ]
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

                        // block a user account
                        $update = $adminModel->blockUser($input->get('userid'));

                        if ($update == 1) {
                            $response = [
                                'status' => 'success',
                                'message' => 'This user\'s account has been blocked'
                            ];
                        }else{
                            $response = [
                                'status' => 'error',
                                'message' => 'An error occurred while blocking user account, try again later.'
                            ];
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
            }else{
                // Redirect if request method is not POST
                redirect('admin/users');
            }
        }elseif (isset($this->url[2]) && $this->url[2] == 'activate-user') {
            // Check if the request method is POST
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $validator = $this->library('Validator');
                $validation = $validator->check($_POST, [
                    'userid' => [
                        'required' => true
                    ]
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
                        
                        // block a user account
                        $update = $adminModel->activateUser($input->get('userid'));

                        if ($update == 1) {
                            $response = [
                                'status' => 'success',
                                'message' => 'This user\'s account has been activated'
                            ];
                        }else{
                            $response = [
                                'status' => 'error',
                                'message' => 'An error occurred while activating user account, try again later.'
                            ];
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
            }else{
                // Redirect if request method is not POST
                redirect('admin/users');
            }
        }elseif (isset($this->url[2]) && $this->url[2] == 'add-deposit-record') {
            // Check if the request method is POST
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Load the Validator library
                $validator = $this->library('Validator');

                // Validate the POST data
                $validation = $validator->check($_POST, [
                    'amount' => [
                        'required' => true,
                        'float' => true
                    ],
                    'method_code' => [
                        'required' => true,
                        'digit' => true
                    ],
                    'userid' => [
                        'required' => true,
                        'digit' => true
                    ]
                ]);

                // Check if validation fails
                if ($validation->fails()) {
                    // Gather error messages
                    $errors = $validation->errors()->all();
                    $errorMessages = [];

                    // Flatten the error messages array
                    foreach ($errors as $err) {
                        foreach ($err as $r) {
                            $errorMessages[] = $r;
                        }
                    }

                    // Prepare error response
                    $response = [
                        'status' => 'error',
                        'message' => $errorMessages
                    ];
                } else {
                    // Validation passed, proceed with adding funds
                    try {
                        // Get input data
                        $userid = $input->get('userid');
                        $amount = $input->get('amount');
                        $method_code = $input->get('method_code');

                        // Get user details
                        $user = $adminModel->getUserDetails($userid);

                        // user is banned
                        if ($user['status'] == 2) {
                            $response = [
                                'status' => 'error',
                                'message' => 'This user is currently suspended.',
                            ];
                        }else{

                            // Check if payment method exists
                            $has = $userModel->hasMethod($method_code);

                            if (!$has) {
                                // Payment method does not exist warning
                                $response = [
                                    'status' => 'warning',
                                    'message' => 'This deposit method does not exist. Please try again.'
                                ];
                            } else {

                                $data['payment-method'] = $userModel->getMethod($method_code);

                                if ($amount < $data['payment-method']['min_amount']) {
                                    // Below minimum amount warning
                                    $response = [
                                        'status' => 'warning',
                                        'message' => 'Please follow the minimum deposit limit, and try again.'
                                    ];
                                } elseif ($amount > $data['payment-method']['max_amount']) {
                                    // Exceeds maximum amount warning
                                    $response = [
                                        'status' => 'warning',
                                        'message' => 'Please follow the maximum deposit limit, and try again.'
                                    ];
                                } else {

                                    /* Unique ID */
                                    $depositId = $this->uniqueid();

                                    // Generate a unique transaction ID
                                    $trx = $this->generateTransactionID();

                                    // Calculate new balance
                                    $newBalance = $user['interest_wallet'] + $amount;

                                    // Insert deposit details
                                    $insert = $adminModel->addUserDeposit($depositId, $userid, $method_code, $amount, $trx, $newBalance, $data['payment-method']['name']);

                                    if ($insert === 1) {
                                        // Success response
                                        $response = [
                                            'status' => 'success',
                                            'message' => 'The deposit record have been added successfully.'
                                        ];
                                    }else{
                                        // Deposit could not be added, prepare error response
                                        $response = [
                                            'status' => 'error',
                                            'message' => 'An error occurred while adding deposit record to the user account.',
                                        ];
                                    }
                                }
                            }
                        }
                    } catch (Exception $e) {
                        // Error occurred during transaction, prepare error response
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
                // Redirect if request method is not POST
                redirect('admin/users');
            }
        }elseif (isset($this->url[2]) && $this->url[2] == 'add-withdrawal-record') {
            // Check if the request method is POST
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Load the Validator library
                $validator = $this->library('Validator');

                // Validate the POST data
                $validation = $validator->check($_POST, [
                    'amount' => [
                        'required' => true,
                        'float' => true
                    ],
                    'withdraw_code' => [
                        'required' => true,
                        'digit' => true
                    ],
                    'wallet' => [
                        'required' => true
                    ],
                    'userid' => [
                        'required' => true,
                        'digit' => true
                    ]
                ]);

                // Check if validation fails
                if ($validation->fails()) {
                    // Gather error messages
                    $errors = $validation->errors()->all();
                    $errorMessages = [];

                    // Flatten the error messages array
                    foreach ($errors as $err) {
                        foreach ($err as $r) {
                            $errorMessages[] = $r;
                        }
                    }

                    // Prepare error response
                    $response = [
                        'status' => 'error',
                        'message' => $errorMessages
                    ];
                } else {
                    // Validation passed, proceed with adding funds
                    try {
                        // Get input data
                        $userid = $input->get('userid');
                        $amount = $input->get('amount');
                        $wallet = $input->get('wallet');
                        $withdraw_code = $input->get('withdraw_code');

                        // Get user details
                        $user = $adminModel->getUserDetails($userid);

                        // user is banned
                        if ($user['status'] == 2) {
                            $response = [
                                'status' => 'error',
                                'message' => 'This user is currently suspended.',
                            ];
                        }else{

                            // Check if withdraw method exists
                            $has = $userModel->hasWithdrawMethod($withdraw_code);

                            if (!$has) {
                                // Withdraw method does not exist warning
                                $response = [
                                    'status' => 'warning',
                                    'message' => 'This withdrawal method does not exist. Please try again.'
                                ];
                            } else {
                                // Check if user has sufficient funds
                                if ($user['interest_wallet'] == 0.00) {
                                    // Empty balance warning
                                    $response = [
                                        'status' => 'warning',
                                        'message' => 'You can\'t withdraw from an empty balance.'
                                    ];
                                } else {
                                    if ($amount > $user['interest_wallet']) {
                                        // Insufficient funds warning
                                        $response = [
                                            'status' => 'warning',
                                            'message' => 'This user has insufficient funds to withdraw.'
                                        ];
                                    } else {
                                        // Check if the amount is within withdrawal limits
                                        $data['withdraw-method'] = $userModel->getWithdrawMethod($withdraw_code);

                                        if ($amount < $data['withdraw-method']['min_amount']) {
                                            // Below minimum amount warning
                                            $response = [
                                                'status' => 'warning',
                                                'message' => 'Please follow the minimum withdrawal limit, and try again.'
                                            ];
                                        } elseif ($amount > $data['withdraw-method']['max_amount']) {
                                            // Exceeds maximum amount warning
                                            $response = [
                                                'status' => 'warning',
                                                'message' => 'Please follow the maximum withdrawal limit, and try again.'
                                            ];
                                        } else {

                                            /* Unique ID */
                                            $withdrawId = $this->uniqueid();

                                            // Generate a unique transaction ID
                                            $trx = $this->generateTransactionID();

                                            // Calculate new balance
                                            $newBalance = $user['interest_wallet'] - $amount;

                                            // Insert withdrawal details
                                            $insert = $adminModel->addUserPayout($withdrawId, $userid, $withdraw_code, $amount, $trx, $wallet, $newBalance, $data['withdraw-method']['name']);

                                            if ($insert === 1) {
                                                // Success response
                                                $response = [
                                                    'status' => 'success',
                                                    'message' => 'The withdrawal record have been added successfully'
                                                ];
                                            }else{
                                                // Deposit could not be added, prepare error response
                                                $response = [
                                                    'status' => 'error',
                                                    'message' => 'An error occurred while adding withdrawal record to the user account.',
                                                ];
                                            }
                                            
                                        }
                                    }
                                }
                            }
                        }
                    } catch (Exception $e) {
                        // Error occurred during transaction, prepare error response
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
                // Redirect if request method is not POST
                redirect('admin/users');
            }
        }elseif (isset($this->url[2]) && $this->url[2] == 'add-investment-record') {
            // Check if the request method is POST
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                // Load the Validator library
                $validator = $this->library('Validator');

                // Validate the POST data
                $validation = $validator->check($_POST, [
                    'amount' => [
                        'required' => true,
                        'float' => true
                    ],
                    'planId' => [
                        'required' => true,
                        'digit' => true
                    ],
                    'userid' => [
                        'required' => true,
                        'digit' => true
                    ]
                ]);

                // Check if validation fails
                if ($validation->fails()) {
                    // If validation fails, gather error messages and prepare error response
                    $errors = $validation->errors()->all();
                    $errorMessages = [];

                    // Flatten the error messages array
                    foreach ($errors as $err) {
                        foreach ($err as $r) {
                            $errorMessages[] = $r;
                        }
                    }

                    // Prepare error response
                    $response = [
                        'status' => 'error',
                        'message' => $errorMessages
                    ];
                } else {
                    // Validation passed, proceed with investing
                    try {

                        // set variables
                        $planId = $input->get('planId');
                        $amount = $input->get('amount');
                        $userid = $input->get('userid');
                        $method = "interest_wallet";

                        // Get user details
                        $data['user'] = $adminModel->getUserDetails($userid);

                        // user is banned
                        if ($data['user']['status'] == 2) {
                            $response = [
                                'status' => 'error',
                                'message' => 'This user is currently suspended.',
                            ];
                        }else{

                            // Check if the plan exists
                            $hasPlan = $userModel->hasPlanId($planId);

                            if (!$hasPlan) {
                                // If the plan doesn't exist, prepare error response
                                $response = [
                                    'status' => 'error',
                                    'message' => 'This planId does not exist. Please try again.'
                                ];
                            } else {
                                // Get plan details
                                $data['plan-details'] = $userModel->planDetails($planId);
                                $hours = $data['plan-details']['times'];

                                // Generate investmentId
                                $investId = $this->investmentId();

                                // Check investment limits
                                if ($amount < $data['plan-details']['minimum']) {
                                    // If the amount is below the minimum limit, prepare error response
                                    $response = [
                                        'status' => 'error',
                                        'message' => 'Please follow the minimum investment limit, and try again.'
                                    ];
                                    // Send the JSON response and exit
                                    $this->sendJsonResponse($response);
                                    exit;
                                } elseif ($amount > $data['plan-details']['maximum']) {
                                    // If the amount exceeds the maximum limit, prepare error response
                                    $response = [
                                        'status' => 'error',
                                        'message' => 'Please follow the maximum investment limit, and try again.'
                                    ];
                                    // Send the JSON response and exit
                                    $this->sendJsonResponse($response);
                                    exit;
                                }

                                // Check user balance and investment amount
                                if ($data['user']['interest_wallet'] == 0.00) {
                                    // If the user has an empty balance, prepare error response
                                    $response = [
                                        'status' => 'error',
                                        'message' => 'You can\'t invest from an empty balance, please deposit.'
                                    ];
                                } elseif ($amount > $data['user']['interest_wallet']) {
                                    // If the investment amount exceeds the user's balance, prepare error response
                                    $response = [
                                        'status' => 'error',
                                        'message' => 'This user has an insufficient balance, please deposit.'
                                    ];
                                } else {

                                    // Update user's count of investments and referrals
                                    $data['count-invests'] = $referralModel->countInvestments($data['user']['userid']);
                                    $data['count-referrals'] = $referralModel->countReferrals($data['user']['userid']);

                                    // Determine next rank id and bonus
                                    $data['user_ranking_id'] = 0;
                                    $data['bonus'] = 0;

                                    foreach ($data['ranks'] as $rank) {
                                        if ($rank['id'] > $data['user']['user_ranking_id']) {
                                            if ($data['count-invests'] >= $rank['min_invest'] && $data['count-referrals'] >= $rank['min_referral']) {
                                                $data['user_ranking_id'] = $rank['id'];
                                                $data['bonus'] = $rank['bonus'];
                                            }
                                        }
                                    }

                                    // check if a referral commission is activated
                                    if ($data['settings']['invest_commission'] == 1) {
                                        // check if the user was referred
                                        if (!empty($data['user']['ref_by'])) {
                                            // check if the referrer has already gotten referral commissions
                                            $refExists = $userModel->refExists($data['user']['userid'], $data['user']['ref_by']);
                                            
                                            // if referral commission doesn't exisit add one
                                            if (!$refExists) {

                                                // get the referral settings
                                                $data['referral-settings'] = $userModel->referralSettings();

                                                // set the referral variables
                                                $from_id = $data['user']['userid'];
                                                $to_id = $data['user']['ref_by'];
                                                $referralPercentage = $data['referral-settings']['percent'];

                                                // get referrer's details
                                                $data['referrer'] = $userModel->getRef($data['user']['ref_by']);

                                                // Calculate referral amount based on the percentage passed
                                                $percent = $referralPercentage / 100; // Convert percentage to decimal
                                                $referralAmount = $amount * $percent;

                                                // set referrer's new balance
                                                $new_balance = $data['referrer']['interest_wallet'] + $referralAmount;

                                                // referral transaction details
                                                $details = 'Invested On ' . $data['plan-details']['name'];

                                                // subtract the amount entered from the user's interest wallet and get the new amount
                                                $amount_new = $data['user']['interest_wallet'] - $amount;

                                                // Ensure the result is not negative, set it to 0 if it's negative
                                                $amount_new = max(0.00, $amount_new);

                                                // set referral title
                                                $title = 'Referral Commission From ' . $data['user']['firstname'] . ' ' . $data['user']['lastname'];

                                                // type of transaction performed
                                                $trx_type = "-";

                                                // total interest
                                                $interest = $data['plan-details']['interest'] * $data['plan-details']['repeat_time'];

                                                // Start
                                                if ($data['plan-details']['interest_status'] == 1) {
                                                    $interest_amount = ($amount * $interest) / 100;
                                                } else {
                                                    $interest_amount = $interest;
                                                }

                                                if ($data['plan-details']['lifetime_status'] == 1) {
                                                    $repeat_time = '-1';
                                                } else {
                                                    $repeat_time = $data['plan-details']['repeat_time'];
                                                }

                                                $capital_back_status = $data['plan-details']['capital_back_status'];
                                                // End

                                                // add investment to a database
                                                $insert = $userModel->planPurchase($investId, $data['user']['userid'], $planId, $amount_new, $interest_amount, $repeat_time, $hours, $amount, $method, $details, $from_id, $to_id, $referralAmount, $referralPercentage, $new_balance, $title, $trx_type, $capital_back_status);

                                                // if insert is successful
                                                if ($insert == 1) {

                                                    // Check if user ranking is enabled
                                                    if ($data['settings']["user_ranking"] == 1) {
                                                        // Update user's rank with the new bonus and next rank id
                                                        $userModel->updateRank($data['user']['userid'], $data['bonus'], $data['user_ranking_id']);
                                                    }

                                                    $response = [
                                                        'status' => 'success',
                                                        'message' => 'You have successfully added an investment record for this user.'
                                                    ];
                                                } else {
                                                    $response = [
                                                        'status' => 'error',
                                                        'redirect' => 'An error occurred while adding investment record for this user.'
                                                    ];
                                                }
                                            }else{
                                                // handle plan purchase with a referral commission existing
                                                $details = 'Invested On ' . $data['plan-details']['name'];
                                                $trx_type = "-";

                                                // total interest
                                                $interest = $data['plan-details']['interest'] * $data['plan-details']['repeat_time'];

                                                // Start
                                                if ($data['plan-details']['interest_status'] == 1) {
                                                    $interest_amount = ($amount * $interest) / 100;
                                                } else {
                                                    $interest_amount = $interest;
                                                }

                                                if ($data['plan-details']['lifetime_status'] == 1) {
                                                    $repeat_time = '-1';
                                                } else {
                                                    $repeat_time = $data['plan-details']['repeat_time'];
                                                }

                                                $capital_back_status = $data['plan-details']['capital_back_status'];
                                                // End

                                                $this->handlePlanPurchase($investId, $data, $planId, $amount, $interest_amount, $repeat_time, $hours, $method, $details, $trx_type, $capital_back_status, [$userModel, 'planPurchaseNoRef']);
                                            }
                                        } else {
                                            // handle plan purchase with user not been referred
                                            $details = 'Invested On ' . $data['plan-details']['name'];
                                            $trx_type = "-";

                                            // total interest
                                            $interest = $data['plan-details']['interest'] * $data['plan-details']['repeat_time'];

                                            // Start
                                            if ($data['plan-details']['interest_status'] == 1) {
                                                $interest_amount = ($amount * $interest) / 100;
                                            } else {
                                                $interest_amount = $interest;
                                            }

                                            if ($data['plan-details']['lifetime_status'] == 1) {
                                                $repeat_time = '-1';
                                            } else {
                                                $repeat_time = $data['plan-details']['repeat_time'];
                                            }

                                            $capital_back_status = $data['plan-details']['capital_back_status'];
                                            // End

                                            $this->handlePlanPurchase($investId, $data, $planId, $amount, $interest_amount, $repeat_time, $hours, $method, $details, $trx_type, $capital_back_status, [$userModel, 'planPurchaseNoRef']);
                                        }
                                    } else {
                                        // handle plan purchase without a referral commission being activated
                                        $details = 'Invested On ' . $data['plan-details']['name'];
                                        $trx_type = "-";

                                        // total interest
                                        $interest = $data['plan-details']['interest'] * $data['plan-details']['repeat_time'];

                                        // Start
                                        if ($data['plan-details']['interest_status'] == 1) {
                                            $interest_amount = ($amount * $interest) / 100;
                                        } else {
                                            $interest_amount = $interest;
                                        }

                                        if ($data['plan-details']['lifetime_status'] == 1) {
                                            $repeat_time = '-1';
                                        } else {
                                            $repeat_time = $data['plan-details']['repeat_time'];
                                        }

                                        $capital_back_status = $data['plan-details']['capital_back_status'];
                                        // End

                                        $this->handlePlanPurchase($investId, $data, $planId, $amount, $interest_amount, $repeat_time, $hours, $method, $details, $trx_type, $capital_back_status, [$userModel, 'planPurchaseNoRef']);
                                    }
                                }
                            }
                        }
                    } catch (Exception $e) {
                        // Error occurred during transaction, prepare error response
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
                // Redirect if request method is not POST
                redirect('admin/users');
            }
        }elseif (isset($this->url[2]) && $this->url[2] == 'view-profile') {

            // Check if the URL is set and the user ID exists
            if (!isset($this->url[3]) || !intval($this->url[3]) || !$adminModel->hasUserId($this->url[3])) {
                // If user ID is not provided or invalid, or if the user doesn't exist, display an error message and redirect
                $_SESSION['message'][] = ['error', 'Failed to fetch user details. Please try again later.'];
                redirect('admin/users');
            }

            // Retrieve user details
            $data["user"] = $adminModel->getUserDetails($this->url[3]);

            // Retrieve user referrer
            $data["referrer"] = $adminModel->getUserReferrer($data["user"]['ref_by']);

            // Retrieve referred users and recent referrals
            $data['referred'] = $adminModel->getAllReferredUsers($data["user"]['userid']);
            $data['referrals'] = $adminModel->getThreeReferredUsers($data["user"]['userid']);

            // Retrieve user deposits, payouts, investments, commissions, and referrals count
            $data['deposits'] = $adminModel->depositSum($data["user"]['userid']); // Total deposited amount
            $data['deposits-pending'] = $adminModel->PendingDepositSum($data["user"]['userid']); // Total pending deposit amount
            $data['deposits-completed'] = $adminModel->CompletedDepositSum($data["user"]['userid']); // Total completed deposit amount
            $data['deposits-rejected'] = $adminModel->RejectedDepositSum($data["user"]['userid']); // Total rejected deposit amount
            $data['deposits-initiated'] = $adminModel->InitiatedDepositSum($data["user"]['userid']); // Total initiated deposit amount

            $data['payouts'] = $adminModel->withdrawalSum($data["user"]['userid']); // Total withdrawal amount
            $data['payouts-pending'] = $adminModel->PendingWithdrawalSum($data["user"]['userid']); // Total pending withdrawal amount
            $data['payouts-completed'] = $adminModel->CompletedWithdrawalSum($data["user"]['userid']); // Total completed withdrawal amount
            $data['payouts-rejected'] = $adminModel->RejectedWithdrawalSum($data["user"]['userid']); // Total rejected withdrawal amount
            $data['payouts-initiated'] = $adminModel->InitiatedWithdrawalSum($data["user"]['userid']); // Total initiated withdrawal amount

            $data['investments'] = $adminModel->investmentSum($data["user"]['userid']); // Total investment amount
            $data['investments-pending'] = $adminModel->PendingInvestmentSum($data["user"]['userid']); // Total pending investment amount
            $data['investments-completed'] = $adminModel->CompletedInvestmentSum($data["user"]['userid']); // Total completed investment amount
            $data['investments-cancelled'] = $adminModel->CancelledInvestmentSum($data["user"]['userid']); // Total cancelled investment amount
            $data['investments-initiated'] = $adminModel->InitiatedInvestmentSum($data["user"]['userid']); // Total initiated investment amount

            $data['commissions'] = $adminModel->commissions($data['user']['userid']); // List of commissions earned by the user
            $data['count-referrals'] = $referralModel->countReferrals($data['user']['userid']); // Total number of referrals for the user
            $data['address-proof-count'] = $adminModel->getKYCAddressCount($data['user']['userid']); // Total number of address proofs submitted by the user
            $data['identity-proof-count'] = $adminModel->getKYCAddressCount($data['user']['userid']); // Total number of identity proofs submitted by the user

            // Additional data retrieval for user profile
            $data['get-user-deposits'] = $adminModel->getUserDeposits($data["user"]['userid']); // List of deposits made by the user
            $data['get-user-withdrawals'] = $adminModel->getUserWithdrawals($data['user']['userid']); // List of withdrawals made by the user
            $data['get-user-investments'] = $adminModel->getUsersInvests($data['user']['userid']); // List of investments made by the user
            $data['get-transactions'] = $adminModel->getUserTransactions($data['user']['userid']); // List of all transactions made by the user
            $data['get-commissions'] = $adminModel->getUserCommissions($data['user']['userid']); // List of commissions earned by the user

            // Initialize variable for user's current-ranking name
            $data['current-ranking']['name'] = '';

            // Determine current rank name
            foreach ($data['ranks'] as $rank) {
                if ($rank['id'] == $data['user']['user_ranking_id']) {
                    $data['current-ranking']['name'] = $rank['name'];
                    break;
                }
            }

            // Render view-profile template with the retrieved data
            return ['content' => $this->view->render($data, 'admin/users/view-profile')];
        }elseif (isset($this->url[2]) && $this->url[2] == 'edit-profile') {

            // Check if the URL is set and the user ID exists
            if (!isset($this->url[3]) || !intval($this->url[3]) || !$adminModel->hasUserId($this->url[3])) {
                // If user ID is not provided or invalid, or if the user doesn't exist, display an error message and redirect
                $_SESSION['message'][] = ['error', 'Failed to fetch user details. Please try again later.'];
                redirect('admin/users');
            }

            // Retrieve user details
            $data["user"] = $adminModel->getUserDetails($this->url[3]);

            // Retrieve user referrer
            $data["referrer"] = $adminModel->getUserReferrer($data["user"]['ref_by']);

            // Check if the form is submitted via POST method
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Validate form input
                $validator = $this->library('Validator');

                $validation = $validator->check($_POST, [
                    'firstname' => ['required' => true],
                    'lastname' => ['required' => true],
                    'email' => ['required' => true, 'email' => true],
                    'formattedPhone' => ['required' => true],
                    'country' => ['required' => true],
                    'city' => ['required' => true],
                    'state' => ['required' => true],
                    'address_1' => ['required' => true],
                    'currency' => ['required' => true],
                    'account_verify' => ['required' => true],
                    'twofactor_status' => ['required' => true]
                ]);

                // If validation passes, proceed to update user details
                if (!$validation->fails()) {
                    try {
                        // Check if admin registered email exists
                        $hasAdminEmail = $adminModel->hasAdminEmail($input->get('email'));

                        if ($hasAdminEmail) {
                            $response = [
                                'status' => 'error',
                                'message' => 'This email is registered to another user.',
                            ];
                        } else {
                            // Check if the email passed is not registered to the user
                            if ($data['user']['email'] != $input->get('email')) {
                                $response = [
                                    'status' => 'error',
                                    'message' => 'This email was not registered to this user.',
                                ];
                            } else {
                                // Check if the phone passed is not registered to the user
                                if ($data['user']['phone'] != $input->get('formattedPhone')) {
                                    $response = [
                                        'status' => 'warning',
                                        'message' => 'This phone number was not registered to this user.',
                                    ];
                                } else {
                                    // update user
                                    $update = $adminModel->updateUser(
                                        $data["user"]['userid'],
                                        $input->get('email'),
                                        $input->get('firstname'),
                                        $input->get('lastname'),
                                        $input->get('formattedPhone'),
                                        $input->get('address_1'),
                                        $input->get('country'),
                                        $input->get('city'),
                                        $input->get('state'),
                                        $input->get('currency'),
                                        $input->get('account_verify'),
                                        $input->get('twofactor_status')
                                    );

                                    // Check if the update was successful
                                    if ($update == 1) {
                                        $response = [
                                            'status' => 'success',
                                            'message' => 'This user account has been updated successfully.'
                                        ];
                                    } else {
                                        $response = [
                                            'status' => 'error',
                                            'message' => 'No changes has been made to this user profile.',
                                        ];
                                    }
                                }
                            }
                        }
                    } catch (Exception $e) {
                        // Error occurred while updating extension details
                        $response = [
                            'status' => 'error',
                            'message' => $e->getMessage()
                        ];
                    }
                } else {
                    // Validation fails, prepare error messages
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

                // Send JSON response
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }

            // Render view-profile template with the retrieved data
            return ['content' => $this->view->render($data, 'admin/users/edit-profile')];
        }elseif (isset($this->url[2]) && $this->url[2] == 'address-proof') {
            // Check if the URL is set and the user ID exists
            if (!isset($this->url[3]) || !intval($this->url[3]) || !$adminModel->hasUserId($this->url[3])) {
                // If user ID is not provided or invalid, or if the user doesn't exist, display an error message and redirect
                $_SESSION['message'][] = ['error', 'Failed to fetch user details. Please try again later.'];
                redirect('admin/users');
            }

            // Retrieve user & kyc details
            $data["user"] = $adminModel->getUserDetails($this->url[3]);
            $data["get-address-proof"] = $adminModel->getKYCAddressProof($this->url[3]);

            // Process the approval and rejection of address proof submission
            if (isset($_GET['approve'])) {
                // Get the uploadId from the URL
                $uploadId = $_GET['approve'];

                try {
                    // Approve the address proof
                    $approve = $adminModel->approveAddressProof($uploadId, $data["user"]['userid']);

                    if ($approve == 1) {
                        // Check if email notification is enabled
                        if ($data['settings']["email_notification"] == 1) {
                            $siteName = $data['settings']['sitename'];
                            $siteLogo = $data['settings']['logo'];
                            $siteUrl = getenv('URL_PATH');
                            $dateNow = date('Y');

                            // Check if approve address template is enabled
                            if ($approveAddressTemplate !== null && $approveAddressTemplate['status'] == 1) {
                                // Replace placeholders in the email body
                                $approveAddressTemplate['body'] = str_replace(['{FIRSTNAME}', '{LASTNAME}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'], [$data['user']['firstname'], $data['user']['lastname'], $siteName, $siteLogo, $siteUrl, $dateNow], $approveAddressTemplate['body']);

                                $recipientEmail = $data['user']['email'];
                                $subject = $approveAddressTemplate['subject'];
                                $body = $approveAddressTemplate['body'];

                                // Send email
                                if (emailhelper::sendEmail($data['settings'], $recipientEmail, $subject, $body)) {
                                    // Email sent successfully
                                    $response = [
                                        'status' => 'success',
                                        'message' => 'The KYC submission has been approved successfully'
                                    ];
                                } else {
                                    // Failed to send email
                                    $response = [
                                        'status' => 'warning',
                                        'message' => 'The KYC submission was approved, but we failed to send an email'
                                    ];
                                }
                            } else {
                                // Approve address template is disabled
                                $response = [
                                    'status' => 'success',
                                    'message' => 'The KYC submission has been approved successfully'
                                ]; 
                            }
                        } else {
                            // Email notification is disabled
                            $response = [
                                'status' => 'success',
                                'message' => 'The KYC submission has been approved successfully'
                            ];
                        }
                    } else {
                        $response = [
                            'status' => 'error',
                            'message' => 'Error occurred while approving the submission, please try again.'
                        ];
                    }
                } catch (Exception $e) {
                    $response = [
                        'status' => 'error',
                        'message' => $e->getMessage()
                    ];
                }

                // Return the response as JSON
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            } elseif (isset($_GET['reject'])) {
                // Get the uploadId from the URL
                $uploadId = $_GET['reject'];

                try {
                    // Reject the address proof
                    $reject = $adminModel->rejectAddressProof($uploadId, $data["user"]['userid']);

                    if ($reject == 1) {
                        // Check if email notification is enabled
                        if ($data['settings']["email_notification"] == 1) {
                            $siteName = $data['settings']['sitename'];
                            $siteLogo = $data['settings']['logo'];
                            $siteUrl = getenv('URL_PATH');
                            $dateNow = date('Y');

                            // Check if reject address template is enabled
                            if ($rejectAddressTemplate !== null && $rejectAddressTemplate['status'] == 1) {
                                // Replace placeholders in the email body
                                $rejectAddressTemplate['body'] = str_replace(['{FIRSTNAME}', '{LASTNAME}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'], [$data['user']['firstname'], $data['user']['lastname'], $siteName, $siteLogo, $siteUrl, $dateNow], $rejectAddressTemplate['body']);

                                $recipientEmail = $data['user']['email'];
                                $subject = $rejectAddressTemplate['subject'];
                                $body = $rejectAddressTemplate['body'];

                                // Send email
                                if (emailhelper::sendEmail($data['settings'], $recipientEmail, $subject, $body)) {
                                    // Email sent successfully
                                    $response = [
                                        'status' => 'success',
                                        'message' => 'The KYC submission has been rejected successfully'
                                    ];
                                } else {
                                    // Failed to send email
                                    $response = [
                                        'status' => 'warning',
                                        'message' => 'The KYC submission was rejected, but we failed to send an email'
                                    ];
                                }
                            } else {
                                // Reject address template is disabled
                                $response = [
                                    'status' => 'success',
                                    'message' => 'The KYC submission has been rejected successfully'
                                ]; 
                            }
                        } else {
                            // Email notification is disabled
                            $response = [
                                'status' => 'success',
                                'message' => 'The KYC submission has been rejected successfully'
                            ];
                        }
                    } else {
                        $response = [
                            'status' => 'error',
                            'message' => 'Error occurred while rejecting the submission, please try again.'
                        ];
                    }
                } catch (Exception $e) {
                    $response = [
                        'status' => 'error',
                        'message' => $e->getMessage()
                    ];
                }

                // Return the response as JSON
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }

            // Render view-profile template with the retrieved data
            return ['content' => $this->view->render($data, 'admin/users/kyc-submissions/address-proof')];
        }elseif (isset($this->url[2]) && $this->url[2] == 'identity-proof') {
            // Check if the URL is set and the user ID exists
            if (!isset($this->url[3]) || !intval($this->url[3]) || !$adminModel->hasUserId($this->url[3])) {
                // If user ID is not provided or invalid, or if the user doesn't exist, display an error message and redirect
                $_SESSION['message'][] = ['error', 'Failed to fetch user details. Please try again later.'];
                redirect('admin/users');
            }

            // Retrieve user & kyc details
            $data["user"] = $adminModel->getUserDetails($this->url[3]);
            $data["get-identity-proof"] = $adminModel->getKYCIdentityProof($this->url[3]);

            // Process the approval and rejection of identity proof submission
            if (isset($_GET['approve'])) {
                // Get the uploadId from the URL
                $uploadId = $_GET['approve'];

                try {
                    // Approve the address proof
                    $approve = $adminModel->approveIdentityProof($uploadId, $data["user"]['userid']);

                    if ($approve == 1) {
                        // Check if email notification is enabled
                        if ($data['settings']["email_notification"] == 1) {
                            $siteName = $data['settings']['sitename'];
                            $siteLogo = $data['settings']['logo'];
                            $siteUrl = getenv('URL_PATH');
                            $dateNow = date('Y');

                            // Check if approve identity template is enabled
                            if ($approveIdentityTemplate !== null && $approveIdentityTemplate['status'] == 1) {

                                // Replace placeholders in the email body
                                $approveIdentityTemplate['body'] = str_replace(['{FIRSTNAME}', '{LASTNAME}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'], [$data['user']['firstname'], $data['user']['lastname'], $siteName, $siteLogo, $siteUrl, $dateNow], $approveIdentityTemplate['body']);

                                $recipientEmail = $data['user']['email'];
                                $subject = $approveIdentityTemplate['subject'];
                                $body = $approveIdentityTemplate['body'];

                                // Send email
                                if (emailhelper::sendEmail($data['settings'], $recipientEmail, $subject, $body)) {
                                    // Email sent successfully
                                    $response = [
                                        'status' => 'success',
                                        'message' => 'The KYC submission has been approved successfully'
                                    ];
                                } else {
                                    // Failed to send email
                                    $response = [
                                        'status' => 'warning',
                                        'message' => 'The KYC submission was approved, but we failed to send an email'
                                    ];
                                }
                            } else {
                                // Approve identity template is disabled
                                $response = [
                                    'status' => 'success',
                                    'message' => 'The KYC submission has been approved successfully'
                                ]; 
                            }
                        } else {
                            // Email notification is disabled
                            $response = [
                                'status' => 'success',
                                'message' => 'The KYC submission has been approved successfully'
                            ];
                        }
                    } else {
                        $response = [
                            'status' => 'error',
                            'message' => 'Error occurred while approving the submission, please try again.'
                        ];
                    }
                } catch (Exception $e) {
                    $response = [
                        'status' => 'error',
                        'message' => $e->getMessage()
                    ];
                }

                // Return the response as JSON
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            } elseif (isset($_GET['reject'])) {
                // Get the uploadId from the URL
                $uploadId = $_GET['reject'];

                try {
                    // Reject the identity proof
                    $reject = $adminModel->rejectIdentityProof($uploadId, $data["user"]['userid']);

                    if ($reject == 1) {
                        // Check if email notification is enabled
                        if ($data['settings']["email_notification"] == 1) {
                            $siteName = $data['settings']['sitename'];
                            $siteLogo = $data['settings']['logo'];
                            $siteUrl = getenv('URL_PATH');
                            $dateNow = date('Y');

                            // Check if reject identity template is enabled
                            if ($rejectIdentityTemplate !== null && $rejectIdentityTemplate['status'] == 1) {
                                // Replace placeholders in the email body
                                $rejectIdentityTemplate['body'] = str_replace(['{FIRSTNAME}', '{LASTNAME}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'], [$data['user']['firstname'], $data['user']['lastname'], $siteName, $siteLogo, $siteUrl, $dateNow], $rejectIdentityTemplate['body']);

                                $recipientEmail = $data['user']['email'];
                                $subject = $rejectIdentityTemplate['subject'];
                                $body = $rejectIdentityTemplate['body'];

                                // Send email
                                if (emailhelper::sendEmail($data['settings'], $recipientEmail, $subject, $body)) {
                                    // Email sent successfully
                                    $response = [
                                        'status' => 'success',
                                        'message' => 'The KYC submission has been rejected successfully'
                                    ];
                                } else {
                                    // Failed to send email
                                    $response = [
                                        'status' => 'warning',
                                        'message' => 'The KYC submission was rejected, but we failed to send an email'
                                    ];
                                }
                            } else {
                                // Reject identity template is disabled
                                $response = [
                                    'status' => 'success',
                                    'message' => 'The KYC submission has been rejected successfully'
                                ]; 
                            }
                        } else {
                            // Email notification is disabled
                            $response = [
                                'status' => 'success',
                                'message' => 'The KYC submission has been rejected successfully'
                            ];
                        }
                    } else {
                        $response = [
                            'status' => 'error',
                            'message' => 'Error occurred while rejecting the submission, please try again.'
                        ];
                    }
                } catch (Exception $e) {
                    $response = [
                        'status' => 'error',
                        'message' => $e->getMessage()
                    ];
                }

                // Return the response as JSON
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }

            // Render view-profile template with the retrieved data
            return ['content' => $this->view->render($data, 'admin/users/kyc-submissions/identity-proof')];
        }

        // render all-users templates
        return ['content' => $this->view->render($data, 'admin/users/all-users')];
    }

    /**
     * upload
     */
    public function upload(): void
    {

        /* Use Input Library */
        $input = $this->library('Input');

        /* Use Referral Model */
        $userModel = $this->model('User');

        // Process profile image upload
        if ($input->isAjax()) {

            // Validate the incoming request
            $validator = $this->library('Validator');
            $validation = $validator->check($_POST, [
                'photoimg' => [
                    'required' => true
                ],
                'userid' => [
                    'required' => true
                ]
            ]);

            // If validation passes, proceed with file upload
            if (!$validation->fails()) {
                try {
                    // Define valid file formats
                    $valid_formats = ["jpg", "jpeg", "png"];

                    // Maximum file size allowed (2MB)
                    $max_file_size = 2097152; // 2MB in bytes

                    // Retrieve file name, format, and size
                    $name = $_FILES['photoimg']['name'];
                    $file_size = $_FILES['photoimg']['size'];

                    // Check if the file is selected
                    if (!empty($name)) {
                        $fileFormat = pathinfo($_FILES['photoimg']['name'], PATHINFO_EXTENSION);

                        // If file size is within limits
                        if ($file_size <= $max_file_size) {
                            // If a file format is valid
                            if ($_FILES['photoimg']['error'] == 0 && in_array($fileFormat, $valid_formats)) {
                                $fileName = $this->rando() . '.' . $fileFormat;

                                // Get the image type
                                $image_info = getimagesize($_FILES['photoimg']['tmp_name']);
                                $image_type = $image_info[2];

                                // image
                                $image = '';

                                // Create image based on an image type
                                switch ($image_type) {
                                    case IMAGETYPE_JPEG:
                                        $image = imagecreatefromjpeg($_FILES['photoimg']['tmp_name']);
                                        break;
                                    case IMAGETYPE_PNG:
                                        $image = imagecreatefrompng($_FILES['photoimg']['tmp_name']);
                                        break;
                                    // Add more cases as needed for other image types
                                    default:
                                        break;
                                }

                                // Resize the image to 512x512
                                $resized_image = imagescale($image, 512, 512);

                                // Define the destination directory
                                $path = sprintf('%s/../../%s/%s/users/', __DIR__, PUBLIC_PATH, UPLOADS_PATH);

                                // Save the resized image to a file
                                if (imagejpeg($resized_image, $path . $fileName)) {

                                    // Update user's profile details in the database
                                    $update = $userModel->profileDetails($fileName, $input->get('userid'));

                                    // Construct JSON response based on an update result
                                    if ($update == 1) {
                                        $response = [
                                            'status' => 'success'
                                        ];
                                    } else {
                                        $response = [
                                            'status' => 'error',
                                            'message' => 'No changes have been made to your profile.'
                                        ];
                                    }
                                } else {
                                    // Error saving the image
                                    $response = [
                                        'status' => 'error',
                                        'message' => 'Unable to save the image. Please try again.'
                                    ];
                                }

                                // Free up memory
                                imagedestroy($image);
                                imagedestroy($resized_image);
                            } else {
                                // Invalid file format
                                $response = [
                                    'status' => 'error',
                                    'message' => 'Allowed file extensions: jpg, jpeg, png'
                                ];
                            }
                        } else {
                            // File size exceeds limit
                            $response = [
                                'status' => 'error',
                                'message' => 'File size exceeds the maximum limit of 2MB.'
                            ];
                        }
                    } else {
                        // No image selected
                        $response = [
                            'status' => 'error',
                            'message' => 'No image selected. Please upload a valid image.'
                        ];
                    }
                } catch (Exception $e) {
                    // Exception occurred during upload
                    $response = [
                        'status' => 'error',
                        'message' => $e->getMessage()
                    ];
                }
            } else {
                // Validation failed, construct error messages
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
        } else {
            // If the request is not an Ajax request, redirect to the user profile page
            redirect('admin/users');
        }
    }

    /**
     * This method handles the AJAX request to load user deposits
     *
     * @return void JSON response containing deposits
     */
    public function load_user_deposits(): void
    {
        /* Use Input Library */
        $input = $this->library('Input');

        /* Use Referral Model */
        $adminModel = $this->model('Admin');

        if ($input->isAjax() && $input->get('userid') && $input->get('page')) {

            $userid = $input->get('userid');
            $page = $input->get('page');
            
            $deposits = $adminModel->getUserDepositsWithPagination($userid, $page);

            // Return the JSON response
            header('Content-Type: application/json');
            echo json_encode(['deposits' => $deposits]);
            exit();
        } else {
            // If the request is not an Ajax request, redirect to the user's page
            redirect('admin/users');
        }
    }

    /**
     * This method handles the AJAX request to load user payouts
     *
     * @return void JSON response containing payouts
     */
    public function load_user_payouts(): void
    {
        /* Use Input Library */
        $input = $this->library('Input');

        /* Use Referral Model */
        $adminModel = $this->model('Admin');

        if ($input->isAjax() && $input->get('userid') && $input->get('page')) {

            $userid = $input->get('userid');
            $page = $input->get('page');
            
            $payouts = $adminModel->getUserWithdrawalsWithPagination($userid, $page);

            // Return the JSON response
            header('Content-Type: application/json');
            echo json_encode(['payouts' => $payouts]);
            exit();
        } else {
            // If the request is not an Ajax request, redirect to the user's page
            redirect('admin/users');
        }
    }

    /**
     * This method handles the AJAX request to load user investments
     *
     * @return void JSON response containing investments
     */
    public function load_user_invests(): void
    {
        /* Use Input Library */
        $input = $this->library('Input');

        /* Use Referral Model */
        $adminModel = $this->model('Admin');

        if ($input->isAjax() && $input->get('userid') && $input->get('page')) {

            $userid = $input->get('userid');
            $page = $input->get('page');
            
            $invests = $adminModel->getUsersInvestsWithPagination($userid, $page);

            // Return the JSON response
            header('Content-Type: application/json');
            echo json_encode(['invests' => $invests]);
            exit();
        } else {
            // If the request is not an Ajax request, redirect to the user's page
            redirect('admin/users');
        }
    }

    /**
     * This method handles the AJAX request to load user referrals
     *
     * @return void JSON response containing referrals
     */
    public function load_user_referrals(): void
    {
        /* Use Input Library */
        $input = $this->library('Input');

        /* Use Referral Model */
        $adminModel = $this->model('Admin');

        if ($input->isAjax() && $input->get('userid') && $input->get('page')) {

            $userid = $input->get('userid');
            $page = $input->get('page');
            
            $referrals = $adminModel->getUserReferralsWithPagination($userid, $page);

            // Return the JSON response
            header('Content-Type: application/json');
            echo json_encode(['referrals' => $referrals]);
            exit();
        } else {
            // If the request is not an Ajax request, redirect to the user's page
            redirect('admin/users');
        }
    }

    /**
     * This method handles the AJAX request to load user commissions
     *
     * @return void JSON response containing commissions
     */
    public function load_user_commissions(): void
    {
        /* Use Input Library */
        $input = $this->library('Input');

        /* Use Referral Model */
        $adminModel = $this->model('Admin');

        if ($input->isAjax() && $input->get('userid') && $input->get('page')) {

            $userid = $input->get('userid');
            $page = $input->get('page');
            
            $commissions = $adminModel->getUserCommissionsWithPagination($userid, $page);

            // Return the JSON response
            header('Content-Type: application/json');
            echo json_encode(['commissions' => $commissions]);
            exit();
        } else {
            // If the request is not an Ajax request, redirect to the user's page
            redirect('admin/users');
        }
    }

    /**
     * This method handles the AJAX request to load user transactions
     *
     * @return void JSON response containing transactions
     */
    public function load_user_transactions(): void
    {
        /* Use Input Library */
        $input = $this->library('Input');

        /* Use Referral Model */
        $adminModel = $this->model('Admin');

        if ($input->isAjax() && $input->get('userid') && $input->get('page')) {

            $userid = $input->get('userid');
            $page = $input->get('page');
            
            $transactions = $adminModel->getUserTransactionsWithPagination($userid, $page);

            // Return the JSON response
            header('Content-Type: application/json');
            echo json_encode(['transactions' => $transactions]);
            exit();
        } else {
            // If the request is not an Ajax request, redirect to the user's page
            redirect('admin/users');
        }
    }

    /**
     * active-users
     */
    public function active(): array
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];

        /* Use Admin Library */
        $admin = $this->library('Admin');
        $data['admin'] = $admin->data();

        // If not logged in, redirect to login page
        if(!$admin->isLoggedIn()):
            redirect('admin/login');
        endif;

        /* Use Input Library */
        $input = $this->library('Input');

        /* Use Referral Model */
        $adminModel = $this->model('Admin');

        // GET request to fetch search results.
        if (isset($_GET['search'])) {
            $searchTerm = strtolower(filter_input(INPUT_GET, 'search', FILTER_SANITIZE_STRING));

            // Perform the user search using $searchTerm
            $data['users'] = $adminModel->findActiveUsers($searchTerm);

            // render active-users templates
            return ['content' => $this->view->render($data, 'admin/users/active-users')];
        }

        if ($input->isAjax() && $input->get('page')) {
            $page = $input->get('page');
            $users = $adminModel->getActiveUsersWithPagination($page);

            // Return the JSON response
            header('Content-Type: application/json');
            echo json_encode(['users' => $users]);
            exit();
        } else {
            $data['users'] = $adminModel->getActiveUsers();
        }

        // render active-users templates
        return ['content' => $this->view->render($data, 'admin/users/active-users')];
    }

    /**
     * banned-users
     */
    public function banned(): array
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];

        /* Use Admin Library */
        $admin = $this->library('Admin');
        $data['admin'] = $admin->data();

        // If not logged in, redirect to login page
        if(!$admin->isLoggedIn()):
            redirect('admin/login');
        endif;

        /* Use Input Library */
        $input = $this->library('Input');

        /* Use Referral Model */
        $adminModel = $this->model('Admin');

        // GET request to fetch search results.
        if (isset($_GET['search'])) {
            $searchTerm = strtolower(filter_input(INPUT_GET, 'search', FILTER_SANITIZE_STRING));

            // Perform the user search using $searchTerm
            $data['users'] = $adminModel->findBannedUsers($searchTerm);

            // render banned-users templates
            return ['content' => $this->view->render($data, 'admin/users/banned-users')];
        }

        if ($input->isAjax() && $input->get('page')) {
            $page = $input->get('page');
            $users = $adminModel->getBannedUsersWithPagination($page);

            // Return the JSON response
            header('Content-Type: application/json');
            echo json_encode(['users' => $users]);
            exit();
        } else {
            $data['users'] = $adminModel->getBannedUsers();
        }

        // render banned-users templates
        return ['content' => $this->view->render($data, 'admin/users/banned-users')];
    }

    /**
     * kyc-unverified-users
     */
    public function kyc_unverified(): array
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];

        /* Use Admin Library */
        $admin = $this->library('Admin');
        $data['admin'] = $admin->data();

        // If not logged in, redirect to login page
        if(!$admin->isLoggedIn()):
            redirect('admin/login');
        endif;

        /* Use Input Library */
        $input = $this->library('Input');

        /* Use Referral Model */
        $adminModel = $this->model('Admin');

        // GET request to fetch search results.
        if (isset($_GET['search'])) {
            $searchTerm = strtolower(filter_input(INPUT_GET, 'search', FILTER_SANITIZE_STRING));

            // Perform the user search using $searchTerm
            $data['users'] = $adminModel->findKYCUnverifiedUsers($searchTerm);

            // render kyc-unverfied-users templates
            return ['content' => $this->view->render($data, 'admin/users/kyc-unverified-users')];
        }

        if ($input->isAjax() && $input->get('page')) {
            $page = $input->get('page');
            $users = $adminModel->getKYCUnverifiedUsersWithPagination($page);

            // Return the JSON response
            header('Content-Type: application/json');
            echo json_encode(['users' => $users]);
            exit();
        } else {
            $data['users'] = $adminModel->getKYCUnverifiedUsers();
        }

        // render kyc-unverfied-users templates
        return ['content' => $this->view->render($data, 'admin/users/kyc-unverified-users')];
    }

    /**
     * kyc-pending-users
     */
    public function kyc_pending(): array
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];

        /* Use Admin Library */
        $admin = $this->library('Admin');
        $data['admin'] = $admin->data();

        // If not logged in, redirect to login page
        if(!$admin->isLoggedIn()):
            redirect('admin/login');
        endif;

        /* Use Input Library */
        $input = $this->library('Input');

        /* Use Referral Model */
        $adminModel = $this->model('Admin');

        // GET request to fetch search results.
        if (isset($_GET['search'])) {
            $searchTerm = strtolower(filter_input(INPUT_GET, 'search', FILTER_SANITIZE_STRING));

            // Perform the user search using $searchTerm
            $data['users'] = $adminModel->findKYCPendingUsers($searchTerm);

            // render kyc-pending-users templates
            return ['content' => $this->view->render($data, 'admin/users/kyc-pending-users')];
        }

        if ($input->isAjax() && $input->get('page')) {
            $page = $input->get('page');
            $users = $adminModel->getKYCPendingUsersWithPagination($page);

            // Return the JSON response
            header('Content-Type: application/json');
            echo json_encode(['users' => $users]);
            exit();
        } else {
            $data['users'] = $adminModel->getKYCPendingUsers();
        }

        // render kyc-pending-users templates
        return ['content' => $this->view->render($data, 'admin/users/kyc-pending-users')];
    }

    /**
     * notifications
     */
    public function notifications(): array
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];
        
        /* Use Admin Library */
        $admin = $this->library('Admin');
        $data['admin'] = $admin->data();

        // If not logged in, redirect to login page
        if(!$admin->isLoggedIn()):
            redirect('admin/login');
        endif;

        /* Use Input Library */
        $input = $this->library('Input');
        
        /* Use Admin Model */
        $adminModel = $this->model('Admin');
        
        /* Use Settings Model */
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();

        // Fetch the email template with id = 24
        $data['email-templates'] = $settingsModel->getEmailTemplate();
        $notificationTemplate = $data['email-templates'][24] ?? null;

        $data['users'] = $adminModel->Users();

        // Check if the form is submitted via POST method
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $response = [];

            // Validate form input
            $validator = $this->library('Validator');
            $validationRules = [
                'subject' => [
                    'required' => true
                ],
                'details' => [
                    'required' => true
                ]
            ];

            $validation = $validator->check($_POST, $validationRules);

            // If validation fails, prepare error messages
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

                    $details = $_POST['details'];

                    // Check if email notification is enabled
                    if ($data['settings']["email_notification"] == "1") {

                        // Prepare email content
                        $siteName = $data['settings']['sitename'];
                        $siteLogo = $data['settings']['logo'];
                        $siteUrl = getenv('URL_PATH');
                        $dateNow = date('Y');

                        // Load notification template if enabled
                        $notificationTemplate = $notificationTemplate !== null && $notificationTemplate['status'] == 1 ? $notificationTemplate : null;

                        // Check if there are users
                        if (!empty($data['users'])) {
                            /* Loop through users and send email to each selected one */
                            foreach ($data['users'] as $user) {
                                
                                $body = str_replace(
                                    ['{FIRSTNAME}', '{LASTNAME}', '{MESSAGE}', '{SUBJECT}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'],
                                    [$user['firstname'], $user['lastname'], $details, $input->get('subject'), $siteName, $siteLogo, $siteUrl, $dateNow],
                                    $notificationTemplate['body']
                                );

                                // Send email with notification to the user
                                $recipientEmail = $user['email'];
                                $subject = $input->get('subject');

                                if (emailhelper::sendEmail($data['settings'], $recipientEmail, $subject, $body)) {
                                    // Email sent successfully
                                    $response = [
                                        'status' => 'success',
                                        'message' => 'Email notification has been sent successfully'
                                    ];
                                } else {
                                    // Failed to send email
                                    $response = [
                                        'status' => 'error',
                                        'message' => 'Failed to send email notification'
                                    ];
                                }
                            }
                        } else {
                            // No users found
                            $response = [
                                'status' => 'error',
                                'message' => 'No users found.'
                            ];
                        }
                    } else {
                        // Email notification is disabled
                        $response = [
                            'status' => 'error',
                            'message' => 'Email notification is currently disabled. Please activate it.'
                        ]; 
                    }
                } catch (Exception $e) {
                    // Error occurred while sending email
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

        return ['content' => $this->view->render($data, 'admin/notifications/send-email-all')];
    }

    /**
     * notify
     */
    public function notify(): array
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];
        
        /* Use Admin Library */
        $admin = $this->library('Admin');
        $data['admin'] = $admin->data();

        // If not logged in, redirect to login page
        if(!$admin->isLoggedIn()):
            redirect('admin/login');
        endif;

        /* Use Input Library */
        $input = $this->library('Input');
        
        /* Use Admin Model */
        $adminModel = $this->model('Admin');
        
        /* Use Settings Model */
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();

        // Fetch the email template with id = 24
        $data['email-templates'] = $settingsModel->getEmailTemplate();
        $notificationTemplate = $data['email-templates'][24] ?? null;

        $data['users'] = $adminModel->Users();
        $data['all-initiated-deposits'] = $adminModel->allDepositsInitiated();
        $data['all-pending-deposits'] = $adminModel->allDepositsPending();
        $data['kyc-unverified-users'] = $adminModel->KYCUnverifiedUsers();
        $data['kyc-pending-users'] = $adminModel->KYCPendingUsers();
        $data['users-with-empty-balance'] = $adminModel->UsersWithEmptyBalance();

        $response = [];

        if (isset($this->url[2]) && $this->url[2] == 'selected-users') {
            // Check if the form is submitted via POST method
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Validate form input
                $validator = $this->library('Validator');
                $validationRules = [
                    'users[]' => [
                        'required' => true
                    ],
                    'subject' => [
                        'required' => true
                    ],
                    'details' => [
                        'required' => true
                    ]
                ];

                $validation = $validator->check($_POST, $validationRules);

                // If validation fails, prepare error messages
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

                        if (empty($input->get('users'))) {
                            $response = [
                                'status' => 'error',
                                'message' => 'Please select a user to send email',
                            ];
                            echo json_encode($response);
                            exit;
                        }

                        /* Collect form data */
                        $selected_user = !empty($_POST['users']) ? $_POST['users'] : [];
                        $details = $_POST['details'];

                        // Check if email notification is enabled
                        if ($data['settings']["email_notification"] == "1") {

                            // Prepare email content
                            $siteName = $data['settings']['sitename'];
                            $siteLogo = $data['settings']['logo'];
                            $siteUrl = getenv('URL_PATH');
                            $dateNow = date('Y');

                            // Load notification template if enabled
                            $notificationTemplate = $notificationTemplate !== null && $notificationTemplate['status'] == 1 ? $notificationTemplate : null;

                            $processedUsers = []; // Array to keep track of processed users

                            /* Loop through users and send email to each selected one */
                            foreach ($data['users'] as $user) {
                                if (in_array($user['email'], $selected_user) && !in_array($user['userid'], $processedUsers)) {

                                    $body = str_replace(
                                        ['{FIRSTNAME}', '{LASTNAME}', '{MESSAGE}', '{SUBJECT}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'],
                                        [$user['firstname'], $user['lastname'], $details, $input->get('subject'), $siteName, $siteLogo, $siteUrl, $dateNow],
                                        $notificationTemplate['body']
                                    );

                                    // Send email with notification to the user
                                    $recipientEmail = $user['email'];
                                    $subject = $input->get('subject');

                                    if (emailhelper::sendEmail($data['settings'], $recipientEmail, $subject, $body)) {
                                        // Email sent successfully
                                        $response = [
                                            'status' => 'success',
                                            'message' => 'Email notification has been sent successfully'
                                        ];
                                    } else {
                                        // Failed to send email
                                        $response = [
                                            'status' => 'error',
                                            'message' => 'Failed to send email notification'
                                        ];
                                    }

                                    $processedUsers[] = $user['userid']; // Mark user as processed
                                }
                            }
                        } else {
                            // Email notification is disabled
                            $response = [
                                'status' => 'error',
                                'message' => 'Email notification is currently disabled. Please activate it.'
                            ]; 
                        }
                    } catch (Exception $e) {
                        // Error occurred while sending email
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

            // render the selected-users template
            return ['content' => $this->view->render($data, 'admin/notifications/selected-users')];
        }if (isset($this->url[2]) && $this->url[2] == 'kyc-unverified-users') {
            // Check if the form is submitted via POST method
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Validate form input
                $validator = $this->library('Validator');
                $validationRules = [
                    'subject' => [
                        'required' => true
                    ],
                    'details' => [
                        'required' => true
                    ]
                ];

                $validation = $validator->check($_POST, $validationRules);

                // If validation fails, prepare error messages
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

                        $details = $_POST['details'];

                        // Check if email notification is enabled
                        if ($data['settings']["email_notification"] == "1") {

                            $siteName = $data['settings']['sitename'];
                            $siteLogo = $data['settings']['logo'];
                            $siteUrl = getenv('URL_PATH');
                            $dateNow = date('Y');

                            // Load notification template if enabled
                            $notificationTemplate = $notificationTemplate !== null && $notificationTemplate['status'] == 1 ? $notificationTemplate : null;

                            // Check if there are unverified users
                            if (!empty($data['kyc-unverified-users'])) {

                                $processedUsers = []; // Array to keep track of processed users

                                /* Loop through users and send email to each selected one */
                                foreach ($data['kyc-unverified-users'] as $user) {
                                    if (!in_array($user['userid'], $processedUsers)) {
                                        // Prepare email content
                                        $body = str_replace(
                                            ['{FIRSTNAME}', '{LASTNAME}', '{MESSAGE}', '{SUBJECT}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'],
                                            [$user['firstname'], $user['lastname'], $details, $input->get('subject'), $siteName, $siteLogo, $siteUrl, $dateNow],
                                            $notificationTemplate['body']
                                        );

                                        // Send email with notification to the user
                                        $recipientEmail = $user['email'];
                                        $subject = $input->get('subject');

                                        if (emailhelper::sendEmail($data['settings'], $recipientEmail, $subject, $body)) {
                                            // Email sent successfully
                                            $response = [
                                                'status' => 'success',
                                                'message' => 'Email notification has been sent successfully'
                                            ];
                                        } else {
                                            // Failed to send email
                                            $response = [
                                                'status' => 'error',
                                                'message' => 'Failed to send email notification'
                                            ];
                                        }

                                        $processedUsers[] = $user['userid']; // Mark user as processed
                                    }
                                }
                            } else {
                                // No unverified users found
                                $response = [
                                    'status' => 'error',
                                    'message' => 'No KYC unverified users found.'
                                ];
                            }
                        } else {
                            // Email notification is disabled
                            $response = [
                                'status' => 'error',
                                'message' => 'Email notification is currently disabled. Please activate it.'
                            ]; 
                        }
                    } catch (Exception $e) {
                        // Error occurred while sending email
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

            // render the selected-users template
            return ['content' => $this->view->render($data, 'admin/notifications/kyc-unverified-users')];
        }if (isset($this->url[2]) && $this->url[2] == 'kyc-pending-users') {
            // Check if the form is submitted via POST method
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Validate form input
                $validator = $this->library('Validator');
                $validationRules = [
                    'subject' => [
                        'required' => true
                    ],
                    'details' => [
                        'required' => true
                    ]
                ];

                $validation = $validator->check($_POST, $validationRules);

                // If validation fails, prepare error messages
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

                        $details = $_POST['details'];

                        // Check if email notification is enabled
                        if ($data['settings']["email_notification"] == "1") {

                            $siteName = $data['settings']['sitename'];
                            $siteLogo = $data['settings']['logo'];
                            $siteUrl = getenv('URL_PATH');
                            $dateNow = date('Y');

                            // Load notification template if enabled
                            $notificationTemplate = $notificationTemplate !== null && $notificationTemplate['status'] == 1 ? $notificationTemplate : null;

                            // Check if there are pending users
                            if (!empty($data['kyc-pending-users'])) {

                                $processedUsers = []; // Array to keep track of processed users

                                /* Loop through users and send email to each selected one */
                                foreach ($data['kyc-pending-users'] as $user) {
                                    if (!in_array($user['userid'], $processedUsers)) {
                                        // Prepare email content
                                        $body = str_replace(
                                            ['{FIRSTNAME}', '{LASTNAME}', '{MESSAGE}', '{SUBJECT}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'],
                                            [$user['firstname'], $user['lastname'], $details, $input->get('subject'), $siteName, $siteLogo, $siteUrl, $dateNow],
                                            $notificationTemplate['body']
                                        );

                                        // Send email with notification to the user
                                        $recipientEmail = $user['email'];
                                        $subject = $input->get('subject');

                                        if (emailhelper::sendEmail($data['settings'], $recipientEmail, $subject, $body)) {
                                            // Email sent successfully
                                            $response = [
                                                'status' => 'success',
                                                'message' => 'Email notification has been sent successfully'
                                            ];
                                        } else {
                                            // Failed to send email
                                            $response = [
                                                'status' => 'error',
                                                'message' => 'Failed to send email notification'
                                            ];
                                        }

                                        $processedUsers[] = $user['userid']; // Mark user as processed
                                    }
                                }
                            } else {
                                // No, pending users found
                                $response = [
                                    'status' => 'error',
                                    'message' => 'No KYC pending users found.'
                                ];
                            }
                        } else {
                            // Email notification is disabled
                            $response = [
                                'status' => 'error',
                                'message' => 'Email notification is currently disabled. Please activate it.'
                            ]; 
                        }
                    } catch (Exception $e) {
                        // Error occurred while sending email
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

            // render the selected-users template
            return ['content' => $this->view->render($data, 'admin/notifications/kyc-pending-users')];
        }if (isset($this->url[2]) && $this->url[2] == 'users-with-empty-balance') {
            // Check if the form is submitted via POST method
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Validate form input
                $validator = $this->library('Validator');
                $validationRules = [
                    'subject' => [
                        'required' => true
                    ],
                    'details' => [
                        'required' => true
                    ]
                ];

                $validation = $validator->check($_POST, $validationRules);

                // If validation fails, prepare error messages
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

                        $details = $_POST['details'];

                        // Check if email notification is enabled
                        if ($data['settings']["email_notification"] == "1") {

                            $siteName = $data['settings']['sitename'];
                            $siteLogo = $data['settings']['logo'];
                            $siteUrl = getenv('URL_PATH');
                            $dateNow = date('Y');

                            // Load notification template if enabled
                            $notificationTemplate = $notificationTemplate !== null && $notificationTemplate['status'] == 1 ? $notificationTemplate : null;

                            // Check if there are users with empty balance
                            if (!empty($data['users-with-empty-balance'])) {

                                $processedUsers = []; // Array to keep track of processed users

                                /* Loop through users and send email to each selected one */
                                foreach ($data['users-with-empty-balance'] as $user) {
                                    if (!in_array($user['userid'], $processedUsers)) {
                                        // Prepare email content
                                        $body = str_replace(
                                            ['{FIRSTNAME}', '{LASTNAME}', '{MESSAGE}', '{SUBJECT}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'],
                                            [$user['firstname'], $user['lastname'], $details, $input->get('subject'), $siteName, $siteLogo, $siteUrl, $dateNow],
                                            $notificationTemplate['body']
                                        );

                                        // Send email with notification to the user
                                        $recipientEmail = $user['email'];
                                        $subject = $input->get('subject');

                                        if (emailhelper::sendEmail($data['settings'], $recipientEmail, $subject, $body)) {
                                            // Email sent successfully
                                            $response = [
                                                'status' => 'success',
                                                'message' => 'Email notification has been sent successfully'
                                            ];
                                        } else {
                                            // Failed to send email
                                            $response = [
                                                'status' => 'error',
                                                'message' => 'Failed to send email notification'
                                            ];
                                        }

                                        $processedUsers[] = $user['userid']; // Mark user as processed
                                    }
                                }
                            } else {
                                // No users with empty balance found
                                $response = [
                                    'status' => 'error',
                                    'message' => 'No users with empty balance found.'
                                ];
                            }
                        } else {
                            // Email notification is disabled
                            $response = [
                                'status' => 'error',
                                'message' => 'Email notification is currently disabled. Please activate it.'
                            ]; 
                        }
                    } catch (Exception $e) {
                        // Error occurred while sending email
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

            // render the selected-users template
            return ['content' => $this->view->render($data, 'admin/notifications/users-with-empty-balance')];
        }if (isset($this->url[2]) && $this->url[2] == 'users-with-initiated-deposits') {
            // Check if the form is submitted via POST method
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Validate form input
                $validator = $this->library('Validator');
                $validationRules = [
                    'subject' => [
                        'required' => true
                    ],
                    'details' => [
                        'required' => true
                    ]
                ];

                $validation = $validator->check($_POST, $validationRules);

                // If validation fails, prepare error messages
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

                        $details = $_POST['details'];

                        // Check if email notification is enabled
                        if ($data['settings']["email_notification"] == "1") {

                            $siteName = $data['settings']['sitename'];
                            $siteLogo = $data['settings']['logo'];
                            $siteUrl = getenv('URL_PATH');
                            $dateNow = date('Y');

                            // Load notification template if enabled
                            $notificationTemplate = $notificationTemplate !== null && $notificationTemplate['status'] == 1 ? $notificationTemplate : null;

                            // Check if there are users with initiated deposits
                            if (!empty($data['all-initiated-deposits'])) {

                                $processedUsers = []; // Array to keep track of processed users

                                foreach ($data['all-initiated-deposits'] as $deposit) {
                                    foreach ($data['users'] as $user) {
                                        if ($deposit['userid'] == $user['userid'] && !in_array($user['userid'], $processedUsers)) {
                                            // Prepare email content
                                            $body = str_replace(
                                                ['{FIRSTNAME}', '{LASTNAME}', '{MESSAGE}', '{SUBJECT}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'],
                                                [$user['firstname'], $user['lastname'], $details, $input->get('subject'), $siteName, $siteLogo, $siteUrl, $dateNow],
                                                $notificationTemplate['body']
                                            );

                                            // Send email with notification to the user
                                            $recipientEmail = $user['email'];
                                            $subject = $input->get('subject');

                                            if (emailhelper::sendEmail($data['settings'], $recipientEmail, $subject, $body)) {
                                                // Email sent successfully
                                                $response = [
                                                    'status' => 'success',
                                                    'message' => 'Email notification has been sent successfully'
                                                ];
                                            } else {
                                                // Failed to send email
                                                $response = [
                                                    'status' => 'error',
                                                    'message' => 'Failed to send email notification'
                                                ];
                                            }

                                            $processedUsers[] = $user['userid']; // Mark user as processed
                                        }
                                    }
                                }
                            } else {
                                // No users with initiated deposits
                                $response = [
                                    'status' => 'error',
                                    'message' => 'No users with initiated deposits found.'
                                ];
                            }
                        } else {
                            // Email notification is disabled
                            $response = [
                                'status' => 'error',
                                'message' => 'Email notification is currently disabled. Please activate it.'
                            ]; 
                        }
                    } catch (Exception $e) {
                        // Error occurred while sending email
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

            // render the selected-users template
            return ['content' => $this->view->render($data, 'admin/notifications/users-with-initiated-deposits')];
        }if (isset($this->url[2]) && $this->url[2] == 'users-with-pending-deposits') {
            // Check if the form is submitted via POST method
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Validate form input
                $validator = $this->library('Validator');
                $validationRules = [
                    'subject' => [
                        'required' => true
                    ],
                    'details' => [
                        'required' => true
                    ]
                ];

                $validation = $validator->check($_POST, $validationRules);

                // If validation fails, prepare error messages
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

                        $details = $_POST['details'];

                        // Check if email notification is enabled
                        if ($data['settings']["email_notification"] == "1") {

                            $siteName = $data['settings']['sitename'];
                            $siteLogo = $data['settings']['logo'];
                            $siteUrl = getenv('URL_PATH');
                            $dateNow = date('Y');

                            // Load notification template if enabled
                            $notificationTemplate = $notificationTemplate !== null && $notificationTemplate['status'] == 1 ? $notificationTemplate : null;

                            // Check if there are users with pending deposits
                            if (!empty($data['all-pending-deposits'])) {

                                $processedUsers = []; // Array to keep track of processed users

                                foreach ($data['all-pending-deposits'] as $deposit) {
                                    foreach ($data['users'] as $user) {
                                        if ($deposit['userid'] == $user['userid'] && !in_array($user['userid'], $processedUsers)) {
                                            // Prepare email content
                                            $body = str_replace(
                                                ['{FIRSTNAME}', '{LASTNAME}', '{MESSAGE}', '{SUBJECT}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'],
                                                [$user['firstname'], $user['lastname'], $details, $input->get('subject'), $siteName, $siteLogo, $siteUrl, $dateNow],
                                                $notificationTemplate['body']
                                            );

                                            // Send email with notification to the user
                                            $recipientEmail = $user['email'];
                                            $subject = $input->get('subject');

                                            if (emailhelper::sendEmail($data['settings'], $recipientEmail, $subject, $body)) {
                                                // Email sent successfully
                                                $response = [
                                                    'status' => 'success',
                                                    'message' => 'Email notification has been sent successfully'
                                                ];
                                            } else {
                                                // Failed to send email
                                                $response = [
                                                    'status' => 'error',
                                                    'message' => 'Failed to send email notification'
                                                ];
                                            }

                                            $processedUsers[] = $user['userid']; // Mark user as processed
                                        }
                                    }
                                }
                            } else {
                                // No users with pending deposits
                                $response = [
                                    'status' => 'error',
                                    'message' => 'No users with pending deposits found.'
                                ];
                            }
                        } else {
                            // Email notification is disabled
                            $response = [
                                'status' => 'error',
                                'message' => 'Email notification is currently disabled. Please activate it.'
                            ]; 
                        }
                    } catch (Exception $e) {
                        // Error occurred while sending email
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

            // render the selected-users template
            return ['content' => $this->view->render($data, 'admin/notifications/users-with-pending-deposits')];
        } else {
            // Redirect if request method is not POST
            redirect('admin/notifications');
        }

        return [];
    }

    /**
     * deposit-gateway
     */
    public function deposit_gateway(): array
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];
        
        /* Use Admin Library */
        $admin = $this->library('Admin');
        $data['admin'] = $admin->data();

        // If not logged in, redirect to login page
        if(!$admin->isLoggedIn()):
            redirect('admin/login');
        endif;

        /* Use Input Library */
        $input = $this->library('Input');
        
        /* Use Admin Model */
        $adminModel = $this->model('Admin');

        // User Model
        $userModel = $this->model('User');
        
        /* Use Settings Model */
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();

        // Ajax request to load more deposit methods
        if ($input->isAjax() && $input->get('page')) {
            $page = $input->get('page');
            $gateways = $settingsModel->getDepositGatewaysWithPagination($page);

            // Return the JSON response
            header('Content-Type: application/json');
            echo json_encode(['gateways' => $gateways]);
            exit();
        } else {
            $data['gateways'] = $settingsModel->getDepositGateways();
        }

        if (isset($this->url[2]) && $this->url[2] == 'add-method') {
            // Check if the form is submitted via POST method
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                // Validate form input
                $validator = $this->library('Validator');

                $validation = $validator->check($_POST, [
                    'name' => ['required' => true],
                    'abbreviation' => ['required' => true],
                    'min_amount' => ['required' => true],
                    'max_amount' => ['required' => true],
                    'gateway_parameter' => ['required' => true],
                    'status' => ['required' => true],
                    'need_proof' => ['required' => true],
                    'proof_type' => ['required' => true]
                ]);

                // If validation passes, update deposit methods
                if (!$validation->fails()) {
                    try {
                        $validFormats = ["jpg", "jpeg", "png"];
                        $name = $_FILES['photoimg']['name'];
                        $size = $_FILES['photoimg']['size'];

                        if (!empty($name)) {
                            $fileFormat = pathinfo($name, PATHINFO_EXTENSION);

                            if ($_FILES['photoimg']['error'] === UPLOAD_ERR_OK) {
                                if (in_array($fileFormat, $validFormats)) {
                                    if ($size <= 2097152) { // 2MB

                                        $fileName = $this->rando() . '.' . $fileFormat;
                                        $method_code = $this->uniqueid();

                                        // Path to upload directory
                                        $path = sprintf('%s/../../%s/%s/deposit/', __DIR__, PUBLIC_PATH, UPLOADS_PATH);

                                        // Move uploaded file to destination
                                        if (move_uploaded_file($_FILES['photoimg']['tmp_name'], $path . $fileName)) {
                                            // Retrieve current wallet addresses from .env
                                            $wallets = json_decode(getenv('WALLET_ADDRESSES'), true);

                                            if (!is_array($wallets)) {
                                                $wallets = [];
                                            }

                                            // Add new deposit method
                                            $wallets[] = [
                                                'method_code' => $method_code,
                                                'name' => $_POST['name'],
                                                'abbreviation' => $_POST['abbreviation'],
                                                'min_amount' => $_POST['min_amount'],
                                                'max_amount' => $_POST['max_amount'],
                                                'gateway_parameter' => $_POST['gateway_parameter'],
                                                'status' => $_POST['status'],
                                                'need_proof' => $_POST['need_proof'],
                                                'proof_type' => $_POST['proof_type'],
                                                'image' => $fileName
                                            ];

                                            // Convert to JSON and update .env file
                                            $envPath = __DIR__ . '/../../.env';
                                            $envContent = file_get_contents($envPath);

                                            // Update WALLET_ADDRESSES line
                                            $newEnvContent = preg_replace(
                                                '/^WALLET_ADDRESSES=.*/m',
                                                'WALLET_ADDRESSES=\'' . json_encode($wallets) . '\'',
                                                $envContent
                                            );

                                            file_put_contents($envPath, $newEnvContent);

                                            $response = [
                                                'status' => 'success',
                                                'message' => 'Deposit method has been added successfully.'
                                            ];
                                        } else {
                                            $response = [
                                                'status' => 'error',
                                                'message' => 'Unable to upload the image. Please try again.'
                                            ];
                                        }
                                    } else {
                                        $response = [
                                            'status' => 'warning',
                                            'message' => 'File size exceeds the maximum limit of 2MB.'
                                        ];
                                    }
                                } else {
                                    $response = [
                                        'status' => 'warning',
                                        'message' => 'Allowed file extensions: jpg, jpeg, png'
                                    ];
                                }
                            } else {
                                $response = [
                                    'status' => 'warning',
                                    'message' => 'Unable to upload the document, please try again.'
                                ];
                            }
                        } else {
                            $response = [
                                'status' => 'error',
                                'message' => 'No image was selected. Please try again.'
                            ];
                        }
                    } catch (Exception $e) {
                        $response = [
                            'status' => 'error',
                            'message' => $e->getMessage()
                        ];
                    }
                } else {
                    // Validation fails, prepare error messages
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

                // Send JSON response
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }else{
                redirect('admin/deposit_gateway');
            }
        }if (isset($this->url[2]) && $this->url[2] == 'edit-method') {

            // Check if the URL is set and the gateway ID exists
            if (!isset($this->url[3]) || !intval($this->url[3]) || !$userModel->hasMethod($this->url[3])) {
                // If gateway ID is not provided or invalid, or if the gateway doesn't exist, display an error message and redirect
                $_SESSION['message'][] = ['error', 'Failed to fetch gateway details. Please try again later.'];
                redirect('admin/deposit_gateway');
            }

            $method_code = $this->url[3];
            $data['payment-method'] = $userModel->getMethod($method_code);

            // Check if the form is submitted via POST method
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                // Validate form input
                $validator = $this->library('Validator');

                $validation = $validator->check($_POST, [
                    'name' => ['required' => true],
                    'abbreviation' => ['required' => true],
                    'min_amount' => ['required' => true],
                    'max_amount' => ['required' => true],
                    'gateway_parameter' => ['required' => true],
                    'status' => ['required' => true],
                    'need_proof' => ['required' => true],
                    'proof_type' => ['required' => true]
                ]);

                // If validation passes, update wallet details
                if (!$validation->fails()) {
                    try {
                        // Get wallets from .env
                        $wallets = json_decode(getenv('WALLET_ADDRESSES'), true);

                        if (!is_array($wallets)) {
                            throw new Exception('Invalid wallet data format.');
                        }

                        // Update wallet details
                        $wallets[$method_code] = [
                            'name' => $_POST['name'],
                            'abbreviation' => $_POST['abbreviation'],
                            'min_amount' => $_POST['min_amount'],
                            'max_amount' => $_POST['max_amount'],
                            'gateway_parameter' => $_POST['gateway_parameter'],
                            'status' => $_POST['status'],
                            'need_proof' => $_POST['need_proof'],
                            'proof_type' => $_POST['proof_type']
                        ];

                        // Encode and save back to .env (or another storage solution)
                        $updatedWallets = json_encode($wallets);
                        putenv("WALLET_ADDRESSES=$updatedWallets");

                        $response = [
                            'status' => 'success',
                            'message' => 'Wallet has been updated successfully.',
                        ];
                    } catch (Exception $e) {
                        // Handle exceptions
                        $response = [
                            'status' => 'error',
                            'message' => $e->getMessage()
                        ];
                    }
                } else {
                    // Validation fails, prepare error messages
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

                // Send JSON response
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }

            return ['content' => $this->view->render($data, 'admin/gateways/deposit/edit-method')];
        }if (isset($this->url[2]) && $this->url[2] == 'activate-method') {
            // Check if the request method is POST
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $validator = $this->library('Validator');
                $validation = $validator->check($_POST, [
                    'method_code' => [
                        'required' => true,
                        'digit' => true
                    ]
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

                        // activate a deposit method
                        $update = $adminModel->activateDepositMethod($input->get('method_code'));

                        if ($update == 1) {
                            $response = [
                                'status' => 'success',
                                'message' => 'This deposit method has been activated'
                            ];
                        }else{
                            $response = [
                                'status' => 'error',
                                'message' => 'An error occurred while activating deposit method, try again later.'
                            ];
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
            }else{
                // Redirect if request method is not POST
                redirect('admin/deposit_gateway');
            }
        }if (isset($this->url[2]) && $this->url[2] == 'deactivate-method') {
            // Check if the request method is POST
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $validator = $this->library('Validator');
                $validation = $validator->check($_POST, [
                    'method_code' => [
                        'required' => true,
                        'digit' => true
                    ]
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

                        // deactivate a deposit method
                        $update = $adminModel->deactivateDepositMethod($input->get('method_code'));

                        if ($update == 1) {
                            $response = [
                                'status' => 'success',
                                'message' => 'This deposit method has been deactivated'
                            ];
                        }else{
                            $response = [
                                'status' => 'error',
                                'message' => 'An error occurred while deactivating deposit method, try again later.'
                            ];
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
            }else{
                // Redirect if request method is not POST
                redirect('admin/deposit_gateway');
            }
        }

        return ['content' => $this->view->render($data, 'admin/gateways/deposit/manual-methods')];
    }

    /**
     * withdrawal-gateway
     */
    public function withdrawal_gateway(): array
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];
        
        /* Use Admin Library */
        $admin = $this->library('Admin');
        $data['admin'] = $admin->data();

        // If not logged in, redirect to login page
        if(!$admin->isLoggedIn()):
            redirect('admin/login');
        endif;

        /* Use Input Library */
        $input = $this->library('Input');
        
        /* Use Admin Model */
        $adminModel = $this->model('Admin');

        // User Model
        $userModel = $this->model('User');
        
        /* Use Settings Model */
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();

        // Ajax request to load more deposit methods
        if ($input->isAjax() && $input->get('page')) {
            $page = $input->get('page');
            $gateways = $settingsModel->getWithdrawalGatewaysWithPagination($page);

            // Return the JSON response
            header('Content-Type: application/json');
            echo json_encode(['gateways' => $gateways]);
            exit();
        } else {
            $data['gateways'] = $settingsModel->getWithdrawalGateways();
        }

        if (isset($this->url[2]) && $this->url[2] == 'add-method') {
            // Check if the form is submitted via POST method
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                // Validate form input
                $validator = $this->library('Validator');

                $validation = $validator->check($_POST, [
                    'name' => [
                        'required' => true
                    ],
                    'abbreviation' => [
                        'required' => true
                    ],
                    'min_amount' => [
                        'required' => true
                    ],
                    'max_amount' => [
                        'required' => true
                    ],
                    'status' => [
                        'required' => true
                    ]
                ]);

                // If validation passes, update rank details
                if (!$validation->fails()) {
                    try {
                        // file formats
                        $validFormats = ["jpg", "jpeg", "png"];

                        // Retrieve file details
                        $name = $_FILES['photoimg']['name'];
                        $size = $_FILES['photoimg']['size'];

                        if (!empty($name)) {

                            $fileFormat = pathinfo($_FILES['photoimg']['name'], PATHINFO_EXTENSION);

                            if ($_FILES['photoimg']['error'] === UPLOAD_ERR_OK) {
                                if (in_array($fileFormat, $validFormats)) {

                                    // Check file size
                                    if ($size <= 2097152) { // 2MB in bytes

                                        $fileName = $this->rando() . '.' . $fileFormat;
                                        $withdraw_code = $this->uniqueid();

                                        // Path to upload directory
                                        $path = sprintf('%s/../../%s/%s/withdrawal/', __DIR__, PUBLIC_PATH, UPLOADS_PATH);

                                        // Move uploaded file to destination
                                        if (move_uploaded_file($_FILES['photoimg']['tmp_name'], $path . $fileName)) {

                                            // insert withdrawal method with image
                                            $insert = $adminModel->addWithdrawalMethod(
                                                $withdraw_code,
                                                $fileName,
                                                $input->get('name'),
                                                $input->get('abbreviation'),
                                                $input->get('min_amount'),
                                                $input->get('max_amount'),
                                                $input->get('status')
                                            );

                                            if ($insert == 1) {
                                                // withdrawal method inserted successfully
                                                $response = [
                                                    'status' => 'success',
                                                    'message' => 'Withdrawal method has been added successfully.',
                                                ];
                                            } else {
                                                // No changes were made
                                                $response = [
                                                    'status' => 'error',
                                                    'message' => 'An error occurred while saving withdrawal method..'
                                                ];
                                            }
                                        } else {
                                            $response = [
                                                'status' => 'error',
                                                'message' => 'Unable to upload the image. Please try again.'
                                            ];
                                        }
                                    } else {
                                        $response = [
                                            'status' => 'warning',
                                            'message' => 'File size exceeds the maximum limit of 2MB.'
                                        ];
                                    }
                                } else {
                                    $response = [
                                        'status' => 'warning',
                                        'message' => 'Allowed file extensions: jpg, jpeg, png'
                                    ];
                                }
                            } else {
                                $response = [
                                    'status' => 'warning',
                                    'message' => 'Unable to upload the document, please try again.'
                                ];
                            }
                        } else {
                            // No image selected
                            $response = [
                                'status' => 'error',
                                'message' => 'No image was selected. Please try again.'
                            ];
                        }
                    } catch (Exception $e) {
                        // If an exception occurs
                        $response = [
                            'status' => 'error',
                            'message' => $e->getMessage()
                        ];
                    }
                } else {
                    // Validation fails, prepare error messages
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

                // Send JSON response
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }else{
                redirect('admin/withdrawal_gateway');
            }
        }if (isset($this->url[2]) && $this->url[2] == 'edit-method') {

            // Check if the URL is set and the gateway ID exists
            if (!isset($this->url[3]) || !intval($this->url[3]) || !$userModel->hasWithdrawMethod($this->url[3])) {
                // If gateway ID is not provided or invalid, or if the gateway doesn't exist, display an error message and redirect
                $_SESSION['message'][] = ['error', 'Failed to fetch gateway details. Please try again later.'];
                redirect('admin/withdrawal_gateway');
            }

            $withdraw_code = $this->url[3];
            $data['payment-method'] = $userModel->getWithdrawMethod($withdraw_code);

            // Check if the form is submitted via POST method
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                // Validate form input
                $validator = $this->library('Validator');

                $validation = $validator->check($_POST, [
                    'name' => [
                        'required' => true
                    ],
                    'abbreviation' => [
                        'required' => true
                    ],
                    'min_amount' => [
                        'required' => true
                    ],
                    'max_amount' => [
                        'required' => true
                    ],
                    'status' => [
                        'required' => true
                    ]
                ]);

                // If validation passes, update rank details
                if (!$validation->fails()) {
                    try {
                        // file formats
                        $validFormats = ["jpg", "jpeg", "png"];

                        // Retrieve file details
                        $name = $_FILES['photoimg']['name'];
                        $size = $_FILES['photoimg']['size'];

                        if (!empty($name)) {

                            $fileFormat = pathinfo($_FILES['photoimg']['name'], PATHINFO_EXTENSION);

                            if ($_FILES['photoimg']['error'] === UPLOAD_ERR_OK) {
                                if (in_array($fileFormat, $validFormats)) {

                                    // Check file size
                                    if ($size <= 2097152) { // 2MB in bytes

                                        $fileName = $this->rando() . '.' . $fileFormat;

                                        // Path to upload directory
                                        $path = sprintf('%s/../../%s/%s/withdrawal/', __DIR__, PUBLIC_PATH, UPLOADS_PATH);

                                        // Move uploaded file to destination
                                        if (move_uploaded_file($_FILES['photoimg']['tmp_name'], $path . $fileName)) {

                                            // update withdrawal method with image
                                            $update = $adminModel->updateWithdrawalMethod(
                                                $withdraw_code,
                                                $fileName,
                                                $input->get('name'),
                                                $input->get('abbreviation'),
                                                $input->get('min_amount'),
                                                $input->get('max_amount'),
                                                $input->get('status')
                                            );

                                            if ($update == 1) {
                                                // withdrawal method updated successfully
                                                $response = [
                                                    'status' => 'success',
                                                    'message' => 'Withdrawal method has been updated successfully.',
                                                ];
                                            } else {
                                                // No changes were made
                                                $response = [
                                                    'status' => 'error',
                                                    'message' => 'No changes was made to the withdrawal methods.'
                                                ];
                                            }
                                        } else {
                                            $response = [
                                                'status' => 'error',
                                                'message' => 'Unable to upload the image. Please try again.'
                                            ];
                                        }
                                    } else {
                                        $response = [
                                            'status' => 'warning',
                                            'message' => 'File size exceeds the maximum limit of 2MB.'
                                        ];
                                    }
                                } else {
                                    $response = [
                                        'status' => 'warning',
                                        'message' => 'Allowed file extensions: jpg, jpeg, png'
                                    ];
                                }
                            } else {
                                $response = [
                                    'status' => 'warning',
                                    'message' => 'Unable to upload the document, please try again.'
                                ];
                            }
                        } else {
                            // No image selected
                            $update = $adminModel->updateWithdrawalMethodNoImage(
                                $withdraw_code,
                                $input->get('name'),
                                $input->get('abbreviation'),
                                $input->get('min_amount'),
                                $input->get('max_amount'),
                                $input->get('status')
                            );

                            if ($update == 1) {
                                // withdrawal method updated successfully
                                $response = [
                                    'status' => 'success',
                                    'message' => 'Withdrawal method has been updated successfully.',
                                ];
                            } else {
                                // No changes were made
                                $response = [
                                    'status' => 'error',
                                    'message' => 'No changes was made to the withdrawal methods.'
                                ];
                            }
                        }
                    } catch (Exception $e) {
                        // If an exception occurs
                        $response = [
                            'status' => 'error',
                            'message' => $e->getMessage()
                        ];
                    }
                } else {
                    // Validation fails, prepare error messages
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

                // Send JSON response
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }

            return ['content' => $this->view->render($data, 'admin/gateways/withdrawal/edit-method')];
        }if (isset($this->url[2]) && $this->url[2] == 'activate-method') {
            // Check if the request method is POST
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $validator = $this->library('Validator');
                $validation = $validator->check($_POST, [
                    'withdraw_code' => [
                        'required' => true,
                        'digit' => true
                    ]
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

                        // activate a withdrawal method
                        $update = $adminModel->activateWithdrawalMethod($input->get('withdraw_code'));

                        if ($update == 1) {
                            $response = [
                                'status' => 'success',
                                'message' => 'This withdrawal method has been activated'
                            ];
                        }else{
                            $response = [
                                'status' => 'error',
                                'message' => 'An error occurred while activating withdrawal method, try again later.'
                            ];
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
            }else{
                // Redirect if request method is not POST
                redirect('admin/withdrawal_gateway');
            }
        }if (isset($this->url[2]) && $this->url[2] == 'deactivate-method') {
            // Check if the request method is POST
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $validator = $this->library('Validator');
                $validation = $validator->check($_POST, [
                    'withdraw_code' => [
                        'required' => true,
                        'digit' => true
                    ]
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

                        // deactivate a withdrawal method
                        $update = $adminModel->deactivateWithdrawalMethod($input->get('withdraw_code'));

                        if ($update == 1) {
                            $response = [
                                'status' => 'success',
                                'message' => 'This withdrawal method has been deactivated'
                            ];
                        }else{
                            $response = [
                                'status' => 'error',
                                'message' => 'An error occurred while deactivating withdrawal method, try again later.'
                            ];
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
            }else{
                // Redirect if request method is not POST
                redirect('admin/withdrawal_gateway');
            }
        }

        return ['content' => $this->view->render($data, 'admin/gateways/withdrawal/manual-methods')];
    }

    /**
     * commissions
     */
    public function commissions(): array
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];

        /* Use Admin Library */
        $admin = $this->library('Admin');
        $data['admin'] = $admin->data();

        // If not logged in, redirect to login page
        if(!$admin->isLoggedIn()):
            redirect('admin/login');
        endif;

        /* Use Input Library */
        $input = $this->library('Input');
        
        /* Use Admin Model */
        $adminModel = $this->model('Admin');

        /* Use Settings Model */
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();

        if ($input->isAjax() && $input->get('page')) {
            $page = $input->get('page');
            $commissions = $adminModel->getAllCommissionsWithPagination($page);

            // Return the JSON response
            header('Content-Type: application/json');
            echo json_encode(['commissions' => $commissions]);
            exit();
        } else {
            $data['users'] = $adminModel->Users();
            $data['commissions'] = $adminModel->getAllCommissions();
        }

        // Render the edit profile view with data
        return ['content' => $this->view->render($data, 'admin/reports/commissions')];
    }

    /**
     * transactions
     */
    public function transactions(): array
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];

        /* Use Admin Library */
        $admin = $this->library('Admin');
        $data['admin'] = $admin->data();

        // If not logged in, redirect to login page
        if(!$admin->isLoggedIn()):
            redirect('admin/login');
        endif;

        /* Use Input Library */
        $input = $this->library('Input');
        
        /* Use Admin Model */
        $adminModel = $this->model('Admin');

        /* Use Settings Model */
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();

        if ($input->isAjax() && $input->get('page')) {
            $page = $input->get('page');
            $transactions = $adminModel->getAllTransactionsWithPagination($page);

            // Return the JSON response
            header('Content-Type: application/json');
            echo json_encode(['transactions' => $transactions]);
            exit();
        } else {
            $data['users'] = $adminModel->Users();
            $data['transactions'] = $adminModel->getAllTransactions();
        }

        // Render the edit profile view with data
        return ['content' => $this->view->render($data, 'admin/reports/transactions')];
    }

    /**
     * deposits
     */
    public function deposits(): array
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];

        /* Use Admin Library */
        $admin = $this->library('Admin');
        $data['admin'] = $admin->data();

        // If not logged in, redirect to login page
        if(!$admin->isLoggedIn()):
            redirect('admin/login');
        endif;

        /* Use Input Library */
        $input = $this->library('Input');
        
        /* Use Admin Model */
        $adminModel = $this->model('Admin');
        $depositModel = $this->model('Deposit');
        $userModel = $this->model('User');
        $referralModel = $this->model('Referral');

        /* Use Settings Model */
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();
        $data['gateways'] = $settingsModel->getAllDepositMethod();

        // Fetch the email template with id = 8, 31,
        $data['email-templates'] = $settingsModel->getEmailTemplate();
        $referralTemplate = $data['email-templates'][11] ?? null;
        $investmentTemplate = $data['email-templates'][12] ?? null;
        $approveDepositTemplate = $data['email-templates'][8] ?? null;
        $rejectDepositTemplate = $data['email-templates'][31] ?? null;

        if ($input->isAjax() && $input->get('page')) {
            $page = $input->get('page');
            $deposits = $adminModel->getDepositsWithPagination($page);

            // Return the JSON response
            header('Content-Type: application/json');
            echo json_encode(['deposits' => $deposits]);
            exit();
        } else {
            $data['users'] = $adminModel->Users();
            $data['deposits'] = $adminModel->getDeposits();
        }

        if (isset($this->url[2]) && $this->url[2] == 'view-deposit') {

            // Check if the URL is set and the deposit ID exists
            if (!isset($this->url[3]) || !intval($this->url[3])|| !$depositModel->hasDepositId($this->url[3])) {
                $_SESSION['message'][] = ['error', 'Failed to fetch deposit details. Please try again later.'];
                redirect('admin/deposits');
            }

            $data['deposit-details'] = $depositModel->depositDetails($this->url[3]);
            $data['deposit-method'] = $adminModel->getDepositMethod($data['deposit-details']['method_code']);

            // Retrieve user details
            $data["user"] = $adminModel->getUserDetails($data['deposit-details']['userid']);

            // Process the approval deposit submission
            if (isset($_GET['approve'])) {

                // Get the depositId from the URL
                $depositId = $_GET['approve'];

                try {

                    // get investment associated with the deposit
                    $investment = $userModel->investmentDetails($depositId);

                    // Check if the key 'investId' exists in the $investment array
                    if (isset($investment['investId']) && $depositId == $investment['investId']) {

                        // get the plan details using the planId
                        $data['plan-details'] = $userModel->planDetails($investment['planId']);

                        // Update user's count of investments and referrals
                        $data['ranks'] = $referralModel->getRanks();
                        $data['count-invests'] = $referralModel->countInvestments($data['user']['userid']);
                        $data['count-referrals'] = $referralModel->countReferrals($data['user']['userid']);

                        // Determine next rank id and bonus
                        $data['user_ranking_id'] = 0;
                        $data['bonus'] = 0;

                        foreach ($data['ranks'] as $rank) {
                            if ($rank['id'] > $data['user']['user_ranking_id']) {
                                if ($data['count-invests'] >= $rank['min_invest'] && $data['count-referrals'] >= $rank['min_referral']) {
                                    $data['user_ranking_id'] = $rank['id'];
                                    $data['bonus'] = $rank['bonus'];
                                }
                            }
                        }

                        // check if a commission on investing is activated
                        if ($data['settings']['invest_commission'] == 1) {
                            // check if the user was referred
                            if ($data['user']['ref_by'] !== NULL) {
                                // check if the referrer has already gotten referral commissions
                                $refExists = $userModel->refExists($data['user']['userid'], $data['user']['ref_by']);
                                
                                // if referral commission doesn't exisit add one
                                if (!$refExists) {

                                    // get the referral settings
                                    $data['referral-settings'] = $userModel->referralSettings();

                                    // set the referral variables
                                    $from_id = $data['user']['userid'];
                                    $to_id = $data['user']['ref_by'];
                                    $referralPercentage = $data['referral-settings']['percent'];

                                    // get referrer's details
                                    $data['referrer'] = $userModel->getRef($data['user']['ref_by']);
                                    $referrerFirstName = $data['referrer']['firstname'];
                                    $referrerLastName = $data['referrer']['lastname'];

                                    // Calculate referral amount based on the percentage passed
                                    $percent = $referralPercentage / 100; // Convert percentage to decimal
                                    $referralAmount = $investment['amount'] * $percent;

                                    // set referrer's new balance
                                    $new_balance = $data['referrer']['interest_wallet'] + $referralAmount;

                                    // set referral title
                                    $title = 'Referral Commission From ' . $data['user']['firstname'] . ' ' . $data['user']['lastname'];

                                    // investment details
                                    $details = 'Invested On ' . $data['plan-details']['name'];

                                    // total interest
                                    $interest = $data['plan-details']['interest'] * $data['plan-details']['repeat_time'];

                                    if ($data['plan-details']['interest_status'] == 1) {
                                        $interest_amount = ($investment['amount'] * $interest) / 100;
                                    } else {
                                        $interest_amount = $interest;
                                    }

                                    // add investment to a database
                                    $insert = $adminModel->planPurchaseDeposit($investment['investId'], $investment['amount'], $from_id, $to_id, $referralAmount, $referralPercentage, $title, $investment['hours'], $details, $data['user']['userid'], $new_balance);

                                    // if insert is successful
                                    if ($insert == 1) {

                                        // check if the user ranking is enabled
                                        if ($data['settings']["user_ranking"] == 1) {
                                            // Determine next rank id
                                            $userModel->updateRank($data['user']['userid'], $data['bonus'], $data['user_ranking_id']);
                                        }

                                        // Initialize variables for email notifications
                                        $referralEmailSent = false;
                                        $investmentEmailSent = false;
                                        $approveDepositEmailSent = false;

                                        // email notification is enabled
                                        if ($data['settings']["email_notification"] == 1) {

                                            $siteName = $data['settings']['sitename'];
                                            $siteLogo = $data['settings']['logo'];
                                            $siteUrl = getenv('URL_PATH');
                                            $dateNow = date('Y');

                                            // referral template is enabled
                                            if ($referralTemplate !== null && $referralTemplate['status'] == 1) {

                                                // Replace placeholders in referral email body
                                                $referralBody = str_replace(
                                                    ['{FIRSTNAME}', '{LASTNAME}', '{REFFIRSTNAME}', '{REFLASTNAME}', '{REFAMOUNT}', '{NEWAMOUNT}', '{CURRENCY}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'],
                                                    [$data['user']['firstname'], $data['user']['lastname'], $referrerFirstName, $referrerLastName, $referralAmount, $new_balance, $data['user']['currency'], $siteName, $siteLogo, $siteUrl, $dateNow],
                                                    $referralTemplate['body']
                                                );

                                                $referralEmail = $data['referrer']['email'];
                                                $referralSubject = $referralTemplate['subject'];

                                                // Send referral email
                                                $referralEmailSent = emailhelper::sendEmail($data['settings'], $referralEmail, $referralSubject, $referralBody);
                                            }

                                            // investment template is enabled
                                            if ($investmentTemplate !== null && $investmentTemplate['status'] == 1) {

                                                // Replace placeholders in investment email body
                                                $investmentBody = str_replace(
                                                    ['{FIRSTNAME}', '{LASTNAME}', '{AMOUNT}', '{INTEREST}', '{CURRENCY}', '{PLAN}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'],
                                                    [$data['user']['firstname'], $data['user']['lastname'], $investment['amount'], $interest_amount, $data['user']['currency'], $data['plan-details']['name'], $siteName, $siteLogo, $siteUrl, $dateNow],
                                                    $investmentTemplate['body']
                                                );

                                                $recipientEmail = $data['user']['email'];
                                                $investmentSubject = $investmentTemplate['subject'];

                                                // Send plan purchase email
                                                $investmentEmailSent = emailhelper::sendEmail($data['settings'], $recipientEmail, $investmentSubject, $investmentBody);
                                            }

                                            // investment template is enabled
                                            if ($approveDepositTemplate !== null && $approveDepositTemplate['status'] == 1) {

                                                // Replace placeholders in investment email body
                                                $approveDepositTemplate['body'] = str_replace(['{FIRSTNAME}', '{LASTNAME}', '{CURRENCY}', '{AMOUNT}', '{METHOD}', '{CRYPTO}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'], [$data['user']['firstname'], $data['user']['lastname'], $data['user']['currency'], $data['deposit-details']['amount'], $data['deposit-method']['name'], $data['deposit-details']['crypto_amount'], $siteName, $siteLogo, $siteUrl, $dateNow], $approveDepositTemplate['body']);

                                                $recipientEmail = $data['user']['email'];
                                                $investmentSubject = $approveDepositTemplate['subject'];
                                                $body = $approveDepositTemplate['body'];

                                                // Send plan purchase email
                                                $approveDepositEmailSent = emailhelper::sendEmail($data['settings'], $recipientEmail, $investmentSubject, $body);
                                            }

                                            if ($referralEmailSent && $investmentEmailSent && $approveDepositEmailSent) {
                                                // Email sent successfully
                                                $response = [
                                                    'status' => 'success',
                                                    'message' => 'The deposit has been approved successfully'
                                                ];
                                            } else {
                                                $response = [
                                                    'status' => 'error',
                                                    'message' => 'Deposit approved, but we failed to send notification emails.'
                                                ];
                                            }
                                        } else {
                                            $response = [
                                                'status' => 'success',
                                                'message' => 'The deposit has been approved successfully'
                                            ];
                                        }
                                    }else{
                                        $response = [
                                            'status' => 'error',
                                            'message' => 'Error occurred while approving the deposit, please try again.'
                                        ];
                                    }
                                }else{
                                    // handle plan purchase with a referral commission existing
                                    $details = 'Invested On ' . $data['plan-details']['name'];

                                    // total interest
                                    $interest = $data['plan-details']['interest'] * $data['plan-details']['repeat_time'];

                                    if ($data['plan-details']['interest_status'] == 1) {
                                        $interest_amount = ($investment['amount'] * $interest) / 100;
                                    } else {
                                        $interest_amount = $interest;
                                    }

                                    // add investment to a database
                                    $insert = $adminModel->planPurchaseDepositNoRef($investment['investId'], $investment['amount'], $investment['hours'], $details, $data['user']['userid']);

                                    // if insert is successful
                                    if ($insert == 1) {

                                        // check if the user ranking is enabled
                                        if ($data['settings']["user_ranking"] == 1) {
                                            // Determine next rank id
                                            $userModel->updateRank($data['user']['userid'], $data['bonus'], $data['user_ranking_id']);
                                        }

                                        // Initialize variables for email notifications
                                        $investmentEmailSent = false;
                                        $approveDepositEmailSent = false;

                                        // email notification is enabled
                                        if ($data['settings']["email_notification"] == 1) {

                                            $siteName = $data['settings']['sitename'];
                                            $siteLogo = $data['settings']['logo'];
                                            $siteUrl = getenv('URL_PATH');
                                            $dateNow = date('Y');

                                            // investment template is enabled
                                            if ($investmentTemplate !== null && $investmentTemplate['status'] == 1) {

                                                // Replace placeholders in investment email body
                                                $investmentBody = str_replace(
                                                    ['{FIRSTNAME}', '{LASTNAME}', '{AMOUNT}', '{INTEREST}', '{CURRENCY}', '{PLAN}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'],
                                                    [$data['user']['firstname'], $data['user']['lastname'], $investment['amount'], $interest_amount, $data['user']['currency'], $data['plan-details']['name'], $siteName, $siteLogo, $siteUrl, $dateNow],
                                                    $investmentTemplate['body']
                                                );

                                                $recipientEmail = $data['user']['email'];
                                                $investmentSubject = $investmentTemplate['subject'];

                                                // Send plan purchase email
                                                $investmentEmailSent = emailhelper::sendEmail($data['settings'], $recipientEmail, $investmentSubject, $investmentBody);
                                            }

                                            // investment template is enabled
                                            if ($approveDepositTemplate !== null && $approveDepositTemplate['status'] == 1) {

                                                // Replace placeholders in investment email body
                                                $approveDepositTemplate['body'] = str_replace(['{FIRSTNAME}', '{LASTNAME}', '{CURRENCY}', '{AMOUNT}', '{METHOD}', '{CRYPTO}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'], [$data['user']['firstname'], $data['user']['lastname'], $data['user']['currency'], $data['deposit-details']['amount'], $data['deposit-method']['name'], $data['deposit-details']['crypto_amount'], $siteName, $siteLogo, $siteUrl, $dateNow], $approveDepositTemplate['body']);

                                                $recipientEmail = $data['user']['email'];
                                                $investmentSubject = $approveDepositTemplate['subject'];
                                                $body = $approveDepositTemplate['body'];

                                                // Send plan purchase email
                                                $approveDepositEmailSent = emailhelper::sendEmail($data['settings'], $recipientEmail, $investmentSubject, $body);
                                            }

                                            if ($investmentEmailSent && $approveDepositEmailSent) {
                                                // Email sent successfully
                                                $response = [
                                                    'status' => 'success',
                                                    'message' => 'The deposit has been approved successfully'
                                                ];
                                            } else {
                                                $response = [
                                                    'status' => 'error',
                                                    'message' => 'Deposit approved, but we failed to send notification emails.'
                                                ];
                                            }
                                        } else {
                                            $response = [
                                                'status' => 'success',
                                                'message' => 'The deposit has been approved successfully'
                                            ];
                                        }
                                    }else{
                                        $response = [
                                            'status' => 'error',
                                            'message' => 'Error occurred while approving the deposit, please try again.'
                                        ];
                                    }
                                }
                            } else {
                                // handle plan purchase with user not been referred
                                $details = 'Invested On ' . $data['plan-details']['name'];

                                // total interest
                                $interest = $data['plan-details']['interest'] * $data['plan-details']['repeat_time'];

                                if ($data['plan-details']['interest_status'] == 1) {
                                    $interest_amount = ($investment['amount'] * $interest) / 100;
                                } else {
                                    $interest_amount = $interest;
                                }

                                // add investment to a database
                                $insert = $adminModel->planPurchaseDepositNoRef($investment['investId'], $investment['amount'], $investment['hours'], $details, $data['user']['userid']);

                                // if insert is successful
                                if ($insert == 1) {

                                    // check if the user ranking is enabled
                                    if ($data['settings']["user_ranking"] == 1) {
                                        // Determine next rank id
                                        $userModel->updateRank($data['user']['userid'], $data['bonus'], $data['user_ranking_id']);
                                    }

                                    // Initialize variables for email notifications
                                    $investmentEmailSent = false;
                                    $approveDepositEmailSent = false;

                                    // email notification is enabled
                                    if ($data['settings']["email_notification"] == 1) {

                                        $siteName = $data['settings']['sitename'];
                                        $siteLogo = $data['settings']['logo'];
                                        $siteUrl = getenv('URL_PATH');
                                        $dateNow = date('Y');

                                        // investment template is enabled
                                        if ($investmentTemplate !== null && $investmentTemplate['status'] == 1) {

                                            // Replace placeholders in investment email body
                                            $investmentBody = str_replace(
                                                ['{FIRSTNAME}', '{LASTNAME}', '{AMOUNT}', '{INTEREST}', '{CURRENCY}', '{PLAN}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'],
                                                [$data['user']['firstname'], $data['user']['lastname'], $investment['amount'], $interest_amount, $data['user']['currency'], $data['plan-details']['name'], $siteName, $siteLogo, $siteUrl, $dateNow],
                                                $investmentTemplate['body']
                                            );

                                            $recipientEmail = $data['user']['email'];
                                            $investmentSubject = $investmentTemplate['subject'];

                                            // Send plan purchase email
                                            $investmentEmailSent = emailhelper::sendEmail($data['settings'], $recipientEmail, $investmentSubject, $investmentBody);
                                        }

                                        // investment template is enabled
                                        if ($approveDepositTemplate !== null && $approveDepositTemplate['status'] == 1) {

                                            // Replace placeholders in investment email body
                                            $approveDepositTemplate['body'] = str_replace(['{FIRSTNAME}', '{LASTNAME}', '{CURRENCY}', '{AMOUNT}', '{METHOD}', '{CRYPTO}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'], [$data['user']['firstname'], $data['user']['lastname'], $data['user']['currency'], $data['deposit-details']['amount'], $data['deposit-method']['name'], $data['deposit-details']['crypto_amount'], $siteName, $siteLogo, $siteUrl, $dateNow], $approveDepositTemplate['body']);

                                            $recipientEmail = $data['user']['email'];
                                            $investmentSubject = $approveDepositTemplate['subject'];
                                            $body = $approveDepositTemplate['body'];

                                            // Send plan purchase email
                                            $approveDepositEmailSent = emailhelper::sendEmail($data['settings'], $recipientEmail, $investmentSubject, $body);
                                        }

                                        if ($investmentEmailSent && $approveDepositEmailSent) {
                                            // Email sent successfully
                                            $response = [
                                                'status' => 'success',
                                                'message' => 'The deposit has been approved successfully'
                                            ];
                                        } else {
                                            $response = [
                                                'status' => 'error',
                                                'message' => 'Deposit approved, but we failed to send notification emails.'
                                            ];
                                        }
                                    } else {
                                        $response = [
                                            'status' => 'success',
                                            'message' => 'The deposit has been approved successfully'
                                        ];
                                    }
                                }else{
                                    $response = [
                                        'status' => 'error',
                                        'message' => 'Error occurred while approving the deposit, please try again.'
                                    ];
                                }  
                            }
                        } else {
                            // handle plan purchase without a referral commission being activated
                            $details = 'Invested On ' . $data['plan-details']['name'];

                            // total interest
                            $interest = $data['plan-details']['interest'] * $data['plan-details']['repeat_time'];

                            if ($data['plan-details']['interest_status'] == 1) {
                                $interest_amount = ($investment['amount'] * $interest) / 100;
                            } else {
                                $interest_amount = $interest;
                            }

                            // add investment to a database
                            $insert = $adminModel->planPurchaseDepositNoRef($investment['investId'], $investment['amount'], $investment['hours'], $details, $data['user']['userid']);

                            // if insert is successful
                            if ($insert == 1) {

                                // check if the user ranking is enabled
                                if ($data['settings']["user_ranking"] == 1) {
                                    // Determine next rank id
                                    $userModel->updateRank($data['user']['userid'], $data['bonus'], $data['user_ranking_id']);
                                }

                                // Initialize variables for email notifications
                                $investmentEmailSent = false;
                                $approveDepositEmailSent = false;

                                // email notification is enabled
                                if ($data['settings']["email_notification"] == 1) {

                                    $siteName = $data['settings']['sitename'];
                                    $siteLogo = $data['settings']['logo'];
                                    $siteUrl = getenv('URL_PATH');
                                    $dateNow = date('Y');

                                    // investment template is enabled
                                    if ($investmentTemplate !== null && $investmentTemplate['status'] == 1) {

                                        // Replace placeholders in investment email body
                                        $investmentBody = str_replace(
                                            ['{FIRSTNAME}', '{LASTNAME}', '{AMOUNT}', '{INTEREST}', '{CURRENCY}', '{PLAN}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'],
                                            [$data['user']['firstname'], $data['user']['lastname'], $investment['amount'], $interest_amount, $data['user']['currency'], $data['plan-details']['name'], $siteName, $siteLogo, $siteUrl, $dateNow],
                                            $investmentTemplate['body']
                                        );

                                        $recipientEmail = $data['user']['email'];
                                        $investmentSubject = $investmentTemplate['subject'];

                                        // Send plan purchase email
                                        $investmentEmailSent = emailhelper::sendEmail($data['settings'], $recipientEmail, $investmentSubject, $investmentBody);
                                    }

                                    // investment template is enabled
                                    if ($approveDepositTemplate !== null && $approveDepositTemplate['status'] == 1) {

                                        // Replace placeholders in investment email body
                                        $approveDepositTemplate['body'] = str_replace(['{FIRSTNAME}', '{LASTNAME}', '{CURRENCY}', '{AMOUNT}', '{METHOD}', '{CRYPTO}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'], [$data['user']['firstname'], $data['user']['lastname'], $data['user']['currency'], $data['deposit-details']['amount'], $data['deposit-method']['name'], $data['deposit-details']['crypto_amount'], $siteName, $siteLogo, $siteUrl, $dateNow], $approveDepositTemplate['body']);

                                        $recipientEmail = $data['user']['email'];
                                        $investmentSubject = $approveDepositTemplate['subject'];
                                        $body = $approveDepositTemplate['body'];

                                        // Send plan purchase email
                                        $approveDepositEmailSent = emailhelper::sendEmail($data['settings'], $recipientEmail, $investmentSubject, $body);
                                    }

                                    if ($investmentEmailSent && $approveDepositEmailSent) {
                                        // Email sent successfully
                                        $response = [
                                            'status' => 'success',
                                            'message' => 'The deposit has been approved successfully'
                                        ];
                                    } else {
                                        $response = [
                                            'status' => 'error',
                                            'message' => 'Deposit approved, but we failed to send notification emails.'
                                        ];
                                    }
                                } else {
                                    $response = [
                                        'status' => 'success',
                                        'message' => 'The deposit has been approved successfully'
                                    ];
                                }
                            }else{
                                $response = [
                                    'status' => 'error',
                                    'message' => 'Error occurred while approving the deposit, please try again.'
                                ];
                            }
                        }
                    }else{

                        // Approve the deposit
                        $approve = $adminModel->approveDeposit($depositId, $data["user"]['userid'], $data['deposit-details']['amount']);

                        if ($approve == 1) {

                            // Check if email notification is enabled
                            if ($data['settings']["email_notification"] == 1) {

                                $siteName = $data['settings']['sitename'];
                                $siteLogo = $data['settings']['logo'];
                                $siteUrl = getenv('URL_PATH');
                                $dateNow = date('Y');

                                // Check if approve deposit template is enabled
                                if ($approveDepositTemplate !== null && $approveDepositTemplate['status'] == 1) {

                                    // Replace placeholders in the email body
                                    $approveDepositTemplate['body'] = str_replace(['{FIRSTNAME}', '{LASTNAME}', '{CURRENCY}', '{AMOUNT}', '{METHOD}', '{CRYPTO}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'], [$data['user']['firstname'], $data['user']['lastname'], $data['user']['currency'], $data['deposit-details']['amount'], $data['deposit-method']['name'], $data['deposit-details']['crypto_amount'], $siteName, $siteLogo, $siteUrl, $dateNow], $approveDepositTemplate['body']);

                                    $recipientEmail = $data['user']['email'];
                                    $subject = $approveDepositTemplate['subject'];
                                    $body = $approveDepositTemplate['body'];

                                    // Send email
                                    if (emailhelper::sendEmail($data['settings'], $recipientEmail, $subject, $body)) {
                                        // Email sent successfully
                                        $response = [
                                            'status' => 'success',
                                            'message' => 'The deposit has been approved successfully'
                                        ];
                                    } else {
                                        // Failed to send email
                                        $response = [
                                            'status' => 'warning',
                                            'message' => 'The deposit was approved, but we failed to send an email'
                                        ];
                                    }
                                } else {
                                    // Approve deposit is disabled
                                    $response = [
                                        'status' => 'success',
                                        'message' => 'The deposit has been approved successfully'
                                    ]; 
                                }
                            } else {
                                // Email notification is disabled
                                $response = [
                                    'status' => 'success',
                                    'message' => 'The deposit has been approved successfully'
                                ];
                            }
                        } else {
                            $response = [
                                'status' => 'error',
                                'message' => 'Error occurred while approving the deposit, please try again.'
                            ];
                        }
                    }
                } catch (Exception $e) {
                    $response = [
                        'status' => 'error',
                        'message' => $e->getMessage()
                    ];
                }

                // Return the response as JSON
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            } elseif (isset($_GET['reject'])) {

                // Get the depositId from the URL
                $depositId = $_GET['reject'];

                try {

                    // Reject the deposit
                    $reject = $adminModel->rejectDeposit($depositId);

                    if ($reject == 1) {

                        // Check if email notification is enabled
                        if ($data['settings']["email_notification"] == 1) {

                            $siteName = $data['settings']['sitename'];
                            $siteLogo = $data['settings']['logo'];
                            $siteUrl = getenv('URL_PATH');
                            $dateNow = date('Y');

                            // Check if reject deposit is enabled
                            if ($rejectDepositTemplate !== null && $rejectDepositTemplate['status'] == 1) {

                                // Replace placeholders in the email body
                                $rejectDepositTemplate['body'] = str_replace(['{FIRSTNAME}', '{LASTNAME}', '{CURRENCY}', '{AMOUNT}', '{METHOD}', '{CRYPTO}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'], [$data['user']['firstname'], $data['user']['lastname'], $data['user']['currency'], $data['deposit-details']['amount'], $data['deposit-method']['name'], $data['deposit-details']['crypto_amount'], $siteName, $siteLogo, $siteUrl, $dateNow], $rejectDepositTemplate['body']);

                                $recipientEmail = $data['user']['email'];
                                $subject = $rejectDepositTemplate['subject'];
                                $body = $rejectDepositTemplate['body'];

                                // Send email
                                if (emailhelper::sendEmail($data['settings'], $recipientEmail, $subject, $body)) {
                                    // Email sent successfully
                                    $response = [
                                        'status' => 'success',
                                        'message' => 'The deposit has been rejected successfully'
                                    ];
                                } else {
                                    // Failed to send email
                                    $response = [
                                        'status' => 'warning',
                                        'message' => 'The deposit was rejected, but we failed to send an email'
                                    ];
                                }
                            } else {
                                // Reject deposit template is disabled
                                $response = [
                                    'status' => 'success',
                                    'message' => 'The deposit has been rejected successfully'
                                ]; 
                            }
                        } else {
                            // Email notification is disabled
                            $response = [
                                'status' => 'success',
                                'message' => 'The deposit has been rejected successfully'
                            ];
                        }
                    } else {
                        $response = [
                            'status' => 'error',
                            'message' => 'Error occurred while rejecting the deposit, please try again.'
                        ];
                    }
                } catch (Exception $e) {
                    $response = [
                        'status' => 'error',
                        'message' => $e->getMessage()
                    ];
                }

                // Return the response as JSON
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }

            // Render the view-investment template with data
            return ['content' => $this->view->render($data, 'admin/reports/deposits/view-deposit')];
        }

        // Render the edit profile view with data
        return ['content' => $this->view->render($data, 'admin/reports/deposits/get-deposits')];
    }

    /**
     * deposits-completed
     */
    public function deposits_completed(): array
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];

        /* Use Admin Library */
        $admin = $this->library('Admin');
        $data['admin'] = $admin->data();

        // If not logged in, redirect to login page
        if(!$admin->isLoggedIn()):
            redirect('admin/login');
        endif;

        /* Use Input Library */
        $input = $this->library('Input');
        
        /* Use Admin Model */
        $adminModel = $this->model('Admin');

        /* Use Settings Model */
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();
        $data['gateways'] = $settingsModel->getAllDepositMethod();

        if ($input->isAjax() && $input->get('page')) {
            $page = $input->get('page');
            $deposits = $adminModel->getCompletedDepositsWithPagination($page);

            // Return the JSON response
            header('Content-Type: application/json');
            echo json_encode(['deposits' => $deposits]);
            exit();
        } else {
            $data['users'] = $adminModel->Users();
            $data['deposits'] = $adminModel->getCompletedDeposits();
        }

        // Render the edit profile view with data
        return ['content' => $this->view->render($data, 'admin/reports/deposits/approved-deposits')];
    }

    /**
     * deposits-initiated
     */
    public function deposits_initiated(): array
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];

        /* Use Admin Library */
        $admin = $this->library('Admin');
        $data['admin'] = $admin->data();

        // If not logged in, redirect to login page
        if(!$admin->isLoggedIn()):
            redirect('admin/login');
        endif;

        /* Use Input Library */
        $input = $this->library('Input');
        
        /* Use Admin Model */
        $adminModel = $this->model('Admin');

        /* Use Settings Model */
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();
        $data['gateways'] = $settingsModel->getAllDepositMethod();

        if ($input->isAjax() && $input->get('page')) {
            $page = $input->get('page');
            $deposits = $adminModel->getInitiatedDepositsWithPagination($page);

            // Return the JSON response
            header('Content-Type: application/json');
            echo json_encode(['deposits' => $deposits]);
            exit();
        } else {
            $data['users'] = $adminModel->Users();
            $data['deposits'] = $adminModel->getInitiatedDeposits();
        }

        // Render the edit profile view with data
        return ['content' => $this->view->render($data, 'admin/reports/deposits/initiated-deposits')];
    }

    /**
     * deposits-pending
     */
    public function deposits_pending(): array
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];

        /* Use Admin Library */
        $admin = $this->library('Admin');
        $data['admin'] = $admin->data();

        // If not logged in, redirect to login page
        if(!$admin->isLoggedIn()):
            redirect('admin/login');
        endif;

        /* Use Input Library */
        $input = $this->library('Input');
        
        /* Use Admin Model */
        $adminModel = $this->model('Admin');

        /* Use Settings Model */
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();
        $data['gateways'] = $settingsModel->getAllDepositMethod();

        if ($input->isAjax() && $input->get('page')) {
            $page = $input->get('page');
            $deposits = $adminModel->getPendingDepositsWithPagination($page);

            // Return the JSON response
            header('Content-Type: application/json');
            echo json_encode(['deposits' => $deposits]);
            exit();
        } else {
            $data['users'] = $adminModel->Users();
            $data['deposits'] = $adminModel->getPendingDeposits();
        }

        // Render the edit profile view with data
        return ['content' => $this->view->render($data, 'admin/reports/deposits/pending-deposits')];
    }

    /**
     * deposits-rejected
     */
    public function deposits_rejected(): array
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];

        /* Use Admin Library */
        $admin = $this->library('Admin');
        $data['admin'] = $admin->data();

        // If not logged in, redirect to login page
        if(!$admin->isLoggedIn()):
            redirect('admin/login');
        endif;

        /* Use Input Library */
        $input = $this->library('Input');
        
        /* Use Admin Model */
        $adminModel = $this->model('Admin');

        /* Use Settings Model */
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();
        $data['gateways'] = $settingsModel->getAllDepositMethod();

        if ($input->isAjax() && $input->get('page')) {
            $page = $input->get('page');
            $deposits = $adminModel->getRejectedDepositsWithPagination($page);

            // Return the JSON response
            header('Content-Type: application/json');
            echo json_encode(['deposits' => $deposits]);
            exit();
        } else {
            $data['users'] = $adminModel->Users();
            $data['deposits'] = $adminModel->getRejectedDeposits();
        }

        // Render the edit profile view with data
        return ['content' => $this->view->render($data, 'admin/reports/deposits/rejected-deposits')];
    }

    /**
     * withdrawals
     */
    public function withdrawals(): array
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];

        /* Use Admin Library */
        $admin = $this->library('Admin');
        $data['admin'] = $admin->data();

        // If not logged in, redirect to login page
        if(!$admin->isLoggedIn()):
            redirect('admin/login');
        endif;

        /* Use Input Library */
        $input = $this->library('Input');
        
        /* Use Admin Model */
        $adminModel = $this->model('Admin');
        $withdrawalModel = $this->model('Withdrawal');

        /* Use Settings Model */
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();

        // Fetch the email template with id = 10, 30,
        $data['email-templates'] = $settingsModel->getEmailTemplate();
        $approveWithdrawalTemplate = $data['email-templates'][10] ?? null;
        $rejectWithdrawalTemplate = $data['email-templates'][30] ?? null;

        $data['withdrawal-gateways'] = $settingsModel->getAllWithdrawMethods();

        if ($input->isAjax() && $input->get('page')) {
            $page = $input->get('page');
            $withdrawals = $adminModel->getWithdrawalsWithPagination($page);

            // Return the JSON response
            header('Content-Type: application/json');
            echo json_encode(['withdrawals' => $withdrawals]);
            exit();
        } else {
            $data['users'] = $adminModel->Users();
            $data['withdrawals'] = $adminModel->getAllWithdrawals();
        }

        if (isset($this->url[2]) && $this->url[2] == 'view-withdrawal') {

            // Check if the URL is set and the withdrawal ID exists
            if (!isset($this->url[3]) || !intval($this->url[3])|| !$withdrawalModel->hasWithdrawal($this->url[3])) {
                $_SESSION['message'][] = ['error', 'Failed to fetch withdrawal details. Please try again later.'];
                redirect('admin/withdrawals');
            }

            $data['withdrawal-details'] = $withdrawalModel->withdrawalDetails($this->url[3]);
            $data['withdraw-method'] = $adminModel->getWithdrawMethod($data['withdrawal-details']['withdraw_code']);

            // Retrieve user details
            $data["user"] = $adminModel->getUserDetails($data['withdrawal-details']['userid']);

            // Process the approval withdrawal submission
            if (isset($_GET['approve'])) {

                // Get the withdrawId from the URL
                $withdrawId = $_GET['approve'];

                try {

                    // Approve the withdrawal
                    $approve = $adminModel->approveWithdrawal($withdrawId, $data["user"]['userid'], $data['withdrawal-details']['amount']);

                    if ($approve == 1) {

                        // Check if email notification is enabled
                        if ($data['settings']["email_notification"] == 1) {

                            $siteName = $data['settings']['sitename'];
                            $siteLogo = $data['settings']['logo'];
                            $siteUrl = getenv('URL_PATH');
                            $dateNow = date('Y');

                            // Check if approve withdrawal template is enabled
                            if ($approveWithdrawalTemplate !== null && $approveWithdrawalTemplate['status'] == 1) {

                                // Replace placeholders in the email body
                                $approveWithdrawalTemplate['body'] = str_replace(['{FIRSTNAME}', '{LASTNAME}', '{CURRENCY}', '{AMOUNT}', '{METHOD}', '{CRYPTO}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'], [$data['user']['firstname'], $data['user']['lastname'], $data['user']['currency'], $data['withdrawal-details']['amount'], $data['withdraw-method']['name'], $data['withdrawal-details']['crypto_amount'], $siteName, $siteLogo, $siteUrl, $dateNow], $approveWithdrawalTemplate['body']);

                                $recipientEmail = $data['user']['email'];
                                $subject = $approveWithdrawalTemplate['subject'];
                                $body = $approveWithdrawalTemplate['body'];

                                // Send email
                                if (emailhelper::sendEmail($data['settings'], $recipientEmail, $subject, $body)) {
                                    // Email sent successfully
                                    $response = [
                                        'status' => 'success',
                                        'message' => 'The withdrawal has been approved successfully'
                                    ];
                                } else {
                                    // Failed to send email
                                    $response = [
                                        'status' => 'warning',
                                        'message' => 'The withdrawal was approved, but we failed to send an email'
                                    ];
                                }
                            } else {
                                // Approve withdrawal is disabled
                                $response = [
                                    'status' => 'success',
                                    'message' => 'The withdrawal has been approved successfully'
                                ]; 
                            }
                        } else {
                            // Email notification is disabled
                            $response = [
                                'status' => 'success',
                                'message' => 'The withdrawal has been approved successfully'
                            ];
                        }
                    } else {
                        $response = [
                            'status' => 'error',
                            'message' => 'Error occurred while approving the withdrawal, please try again.'
                        ];
                    }
                } catch (Exception $e) {
                    $response = [
                        'status' => 'error',
                        'message' => $e->getMessage()
                    ];
                }

                // Return the response as JSON
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            } elseif (isset($_GET['reject'])) {

                // Get the withdrawId from the URL
                $withdrawId = $_GET['reject'];

                try {

                    // Reject the withdrawal
                    $reject = $adminModel->rejectWithdrawal($withdrawId);

                    if ($reject == 1) {

                        // Check if email notification is enabled
                        if ($data['settings']["email_notification"] == 1) {

                            $siteName = $data['settings']['sitename'];
                            $siteLogo = $data['settings']['logo'];
                            $siteUrl = getenv('URL_PATH');
                            $dateNow = date('Y');

                            // Check if reject withdrawal is enabled
                            if ($rejectWithdrawalTemplate !== null && $rejectWithdrawalTemplate['status'] == 1) {

                                // Replace placeholders in the email body
                                $rejectWithdrawalTemplate['body'] = str_replace(['{FIRSTNAME}', '{LASTNAME}', '{CURRENCY}', '{AMOUNT}', '{METHOD}', '{CRYPTO}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'], [$data['user']['firstname'], $data['user']['lastname'], $data['user']['currency'], $data['withdrawal-details']['amount'], $data['withdraw-method']['name'], $data['withdrawal-details']['crypto_amount'], $siteName, $siteLogo, $siteUrl, $dateNow], $rejectWithdrawalTemplate['body']);

                                $recipientEmail = $data['user']['email'];
                                $subject = $rejectWithdrawalTemplate['subject'];
                                $body = $rejectWithdrawalTemplate['body'];

                                // Send email
                                if (emailhelper::sendEmail($data['settings'], $recipientEmail, $subject, $body)) {
                                    // Email sent successfully
                                    $response = [
                                        'status' => 'success',
                                        'message' => 'The withdrawal has been rejected successfully'
                                    ];
                                } else {
                                    // Failed to send email
                                    $response = [
                                        'status' => 'warning',
                                        'message' => 'The withdrawal was rejected, but we failed to send an email'
                                    ];
                                }
                            } else {
                                // Reject withdrawal template is disabled
                                $response = [
                                    'status' => 'success',
                                    'message' => 'The withdrawal has been rejected successfully'
                                ]; 
                            }
                        } else {
                            // Email notification is disabled
                            $response = [
                                'status' => 'success',
                                'message' => 'The withdrawal has been rejected successfully'
                            ];
                        }
                    } else {
                        $response = [
                            'status' => 'error',
                            'message' => 'Error occurred while rejecting the withdrawal, please try again.'
                        ];
                    }
                } catch (Exception $e) {
                    $response = [
                        'status' => 'error',
                        'message' => $e->getMessage()
                    ];
                }

                // Return the response as JSON
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }

            // Render the view-investment template with data
            return ['content' => $this->view->render($data, 'admin/reports/withdrawals/view-withdrawal')];
        }

        // Render the edit profile view with data
        return ['content' => $this->view->render($data, 'admin/reports/withdrawals/get-withdrawals')];
    }

    /**
     * withdrawals-completed
     */
    public function withdrawals_completed(): array
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];

        /* Use Admin Library */
        $admin = $this->library('Admin');
        $data['admin'] = $admin->data();

        // If not logged in, redirect to login page
        if(!$admin->isLoggedIn()):
            redirect('admin/login');
        endif;

        /* Use Input Library */
        $input = $this->library('Input');
        
        /* Use Admin Model */
        $adminModel = $this->model('Admin');

        /* Use Settings Model */
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();
        $data['withdrawal-gateways'] = $settingsModel->getAllWithdrawMethods();

        if ($input->isAjax() && $input->get('page')) {
            $page = $input->get('page');
            $withdrawals = $adminModel->getCompletedWithdrawalsWithPagination($page);

            // Return the JSON response
            header('Content-Type: application/json');
            echo json_encode(['withdrawals' => $withdrawals]);
            exit();
        } else {
            $data['users'] = $adminModel->Users();
            $data['withdrawals'] = $adminModel->getCompletedWithdrawals();
        }

        // Render the edit profile view with data
        return ['content' => $this->view->render($data, 'admin/reports/withdrawals/approved-withdrawals')];
    }

    /**
     * withdrawals-initiated
     */
    public function withdrawals_initiated(): array
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];

        /* Use Admin Library */
        $admin = $this->library('Admin');
        $data['admin'] = $admin->data();

        // If not logged in, redirect to login page
        if(!$admin->isLoggedIn()):
            redirect('admin/login');
        endif;

        /* Use Input Library */
        $input = $this->library('Input');
        
        /* Use Admin Model */
        $adminModel = $this->model('Admin');

        /* Use Settings Model */
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();
        $data['withdrawal-gateways'] = $settingsModel->getAllWithdrawMethods();

        if ($input->isAjax() && $input->get('page')) {
            $page = $input->get('page');
            $withdrawals = $adminModel->getInitiatedWithdrawalsWithPagination($page);

            // Return the JSON response
            header('Content-Type: application/json');
            echo json_encode(['withdrawals' => $withdrawals]);
            exit();
        } else {
            $data['users'] = $adminModel->Users();
            $data['withdrawals'] = $adminModel->getInitiatedWithdrawals();
        }

        // Render the edit profile view with data
        return ['content' => $this->view->render($data, 'admin/reports/withdrawals/initiated-withdrawals')];
    }

    /**
     * withdrawals-pending
     */
    public function withdrawals_pending(): array
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];

        /* Use Admin Library */
        $admin = $this->library('Admin');
        $data['admin'] = $admin->data();

        // If not logged in, redirect to login page
        if(!$admin->isLoggedIn()):
            redirect('admin/login');
        endif;

        /* Use Input Library */
        $input = $this->library('Input');
        
        /* Use Admin Model */
        $adminModel = $this->model('Admin');

        /* Use Settings Model */
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();
        $data['withdrawal-gateways'] = $settingsModel->getAllWithdrawMethods();

        if ($input->isAjax() && $input->get('page')) {
            $page = $input->get('page');
            $withdrawals = $adminModel->getPendingWithdrawalsWithPagination($page);

            // Return the JSON response
            header('Content-Type: application/json');
            echo json_encode(['withdrawals' => $withdrawals]);
            exit();
        } else {
            $data['users'] = $adminModel->Users();
            $data['withdrawals'] = $adminModel->getPendingWithdrawals();
        }

        // Render the edit profile view with data
        return ['content' => $this->view->render($data, 'admin/reports/withdrawals/pending-withdrawals')];
    }

    /**
     * withdrawals-rejected
     */
    public function withdrawals_rejected(): array
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];

        /* Use Admin Library */
        $admin = $this->library('Admin');
        $data['admin'] = $admin->data();

        // If not logged in, redirect to login page
        if(!$admin->isLoggedIn()):
            redirect('admin/login');
        endif;

        /* Use Input Library */
        $input = $this->library('Input');
        
        /* Use Admin Model */
        $adminModel = $this->model('Admin');

        /* Use Settings Model */
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();
        $data['withdrawal-gateways'] = $settingsModel->getAllWithdrawMethods();

        if ($input->isAjax() && $input->get('page')) {
            $page = $input->get('page');
            $withdrawals = $adminModel->getRejectedWithdrawalsWithPagination($page);

            // Return the JSON response
            header('Content-Type: application/json');
            echo json_encode(['withdrawals' => $withdrawals]);
            exit();
        } else {
            $data['users'] = $adminModel->Users();
            $data['withdrawals'] = $adminModel->getRejectedWithdrawals();
        }

        // Render the edit profile view with data
        return ['content' => $this->view->render($data, 'admin/reports/withdrawals/rejected-withdrawals')];
    }

    /**
     * investments
     */
    public function investments(): array
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];

        /* Use Admin Library */
        $admin = $this->library('Admin');
        $data['admin'] = $admin->data();

        // If not logged in, redirect to login page
        if(!$admin->isLoggedIn()):
            redirect('admin/login');
        endif;

        /* Use Input Library */
        $input = $this->library('Input');
        
        /* Use Admin Model */
        $adminModel = $this->model('Admin');

        /* Use Investment Model */
        $investmentModel = $this->model('Investments');

        /* Use Settings Model */
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();

        if ($input->isAjax() && $input->get('page')) {
            $page = $input->get('page');
            $investments = $investmentModel->getInvestmentsWithPagination($page);

            // Return the JSON response
            header('Content-Type: application/json');
            echo json_encode(['investments' => $investments]);
            exit();
        } else {
            $data['users'] = $adminModel->Users();
            $data['plans'] = $adminModel->plans();
            $data['times'] = $investmentModel->times();
            $data['investments'] = $investmentModel->getInvestments();
        }

        if (isset($this->url[2]) && $this->url[2] == 'view-investment') {

            // Check if the URL is set and the gateway ID exists
            if (!isset($this->url[3]) || !intval($this->url[3])|| !$investmentModel->hasInvestment($this->url[3])) {
                $_SESSION['message'][] = ['error', 'Failed to fetch investment details. Please try again later.'];
                redirect('admin/investments');
            }

            $data['investment-details'] = $investmentModel->investmentDetails($this->url[3]);

            $investId = $data['investment-details']['investId'];
            $userid = $data['investment-details']['userid'];
            $capital = $data['investment-details']['amount'];
            $interest = $data['investment-details']['interest'];

            // Check if the form is submitted via POST method
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Validate form input
                $validator = $this->library('Validator');
                $validationRules = [
                    'action' => [
                        'required' => true
                    ]
                ];

                $validation = $validator->check($_POST, $validationRules);

                // If validation fails, prepare error messages
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
                        // Check if action parameter is provided
                        if (empty($input->get('action'))) {
                            $response = [
                                'status' => 'error',
                                'message' => 'Please select an action to cancel investment',
                            ];
                        } else {
                            // Perform action based on provided action parameter
                            switch ($input->get('action')) {
                                case 1:
                                    // Cancel investment and return capital with interest
                                    $returnCapital = $investmentModel->returnCapitalWithInterest($investId, $userid, $capital, $interest);
                                    break;
                                case 2:
                                    // Cancel investment without returning interest but return capital
                                    $returnCapital = $investmentModel->returnCapitalNoInterest($investId, $userid, $capital);
                                    break;
                                case 3:
                                    // Cancel investment without returning capital but return interest
                                    $returnCapital = $investmentModel->returnInterestNoCapital($investId, $userid, $interest);
                                    break;
                                case 4:
                                    // Cancel investment without returning anything
                                    $returnCapital = $investmentModel->returnNone($investId);
                                    break;
                                default:
                                    // Invalid action
                                    break;
                            }

                            // Check the result of the cancellation operation
                            if (isset($returnCapital) && $returnCapital == 1) {
                                $response = [
                                    'status' => 'success',
                                    'message' => 'This investment has been cancelled successfully',
                                ];
                            } else {
                                // Cancellation operation failed
                                $response = [
                                    'status' => 'error',
                                    'message' => 'An error occurred while cancelling investment, try again.',
                                ];
                            }
                        }
                    } catch (Exception $e) {
                        // Error occurred while cancelling investment
                        $response = [
                            'status' => 'error',
                            'message' => $e->getMessage(),
                        ];
                    }
                }

                // Send the JSON response
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }

            // Render the view-investment template with data
            return ['content' => $this->view->render($data, 'admin/reports/investments/view-investment')];
        }

        // Render the edit profile view with data
        return ['content' => $this->view->render($data, 'admin/reports/investments/all-investments')];
    }

    /**
     * investments-running
     */
    public function investments_running(): array
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];

        /* Use Admin Library */
        $admin = $this->library('Admin');
        $data['admin'] = $admin->data();

        // If not logged in, redirect to login page
        if(!$admin->isLoggedIn()):
            redirect('admin/login');
        endif;

        /* Use Input Library */
        $input = $this->library('Input');
        
        /* Use Admin Model */
        $adminModel = $this->model('Admin');

        /* Use Investment Model */
        $investmentModel = $this->model('Investments');

        /* Use Settings Model */
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();

        if ($input->isAjax() && $input->get('page')) {
            $page = $input->get('page');
            $investments = $investmentModel->getRunningInvestmentsWithPagination($page);

            // Return the JSON response
            header('Content-Type: application/json');
            echo json_encode(['investments' => $investments]);
            exit();
        } else {
            $data['users'] = $adminModel->Users();
            $data['plans'] = $adminModel->plans();
            $data['investments'] = $investmentModel->getRunningInvestments();
        }

        // Render the edit profile view with data
        return ['content' => $this->view->render($data, 'admin/reports/investments/running-investments')];
    }

    /**
     * investments-completed
     */
    public function investments_completed(): array
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];

        /* Use Admin Library */
        $admin = $this->library('Admin');
        $data['admin'] = $admin->data();

        // If not logged in, redirect to login page
        if(!$admin->isLoggedIn()):
            redirect('admin/login');
        endif;

        /* Use Input Library */
        $input = $this->library('Input');
        
        /* Use Admin Model */
        $adminModel = $this->model('Admin');

        /* Use Investment Model */
        $investmentModel = $this->model('Investments');

        /* Use Settings Model */
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();

        if ($input->isAjax() && $input->get('page')) {
            $page = $input->get('page');
            $investments = $investmentModel->getCompletedInvestmentsWithPagination($page);

            // Return the JSON response
            header('Content-Type: application/json');
            echo json_encode(['investments' => $investments]);
            exit();
        } else {
            $data['users'] = $adminModel->Users();
            $data['plans'] = $adminModel->plans();
            $data['investments'] = $investmentModel->getCompletedInvestments();
        }

        // Render the edit profile view with data
        return ['content' => $this->view->render($data, 'admin/reports/investments/completed-investments')];
    }

    /**
     * investments-cancelled
     */
    public function investments_cancelled(): array
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];

        /* Use Admin Library */
        $admin = $this->library('Admin');
        $data['admin'] = $admin->data();

        // If not logged in, redirect to login page
        if(!$admin->isLoggedIn()):
            redirect('admin/login');
        endif;

        /* Use Input Library */
        $input = $this->library('Input');
        
        /* Use Admin Model */
        $adminModel = $this->model('Admin');

        /* Use Investment Model */
        $investmentModel = $this->model('Investments');

        /* Use Settings Model */
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();

        if ($input->isAjax() && $input->get('page')) {
            $page = $input->get('page');
            $investments = $investmentModel->getCancelledInvestmentsWithPagination($page);

            // Return the JSON response
            header('Content-Type: application/json');
            echo json_encode(['investments' => $investments]);
            exit();
        } else {
            $data['users'] = $adminModel->Users();
            $data['plans'] = $adminModel->plans();
            $data['investments'] = $investmentModel->getCancelledInvestments();
        }

        // Render the edit profile view with data
        return ['content' => $this->view->render($data, 'admin/reports/investments/cancelled-investments')];
    }

    /**
     * investments-initiated
     */
    public function investments_initiated(): array
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];

        /* Use Admin Library */
        $admin = $this->library('Admin');
        $data['admin'] = $admin->data();

        // If not logged in, redirect to login page
        if(!$admin->isLoggedIn()):
            redirect('admin/login');
        endif;

        /* Use Input Library */
        $input = $this->library('Input');
        
        /* Use Admin Model */
        $adminModel = $this->model('Admin');

        /* Use Investment Model */
        $investmentModel = $this->model('Investments');

        /* Use Settings Model */
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();

        if ($input->isAjax() && $input->get('page')) {
            $page = $input->get('page');
            $investments = $investmentModel->getInitiatedInvestmentsWithPagination($page);

            // Return the JSON response
            header('Content-Type: application/json');
            echo json_encode(['investments' => $investments]);
            exit();
        } else {
            $data['users'] = $adminModel->Users();
            $data['plans'] = $adminModel->plans();
            $data['investments'] = $investmentModel->getInitiatedInvestments();
        }

        // Render the edit profile view with data
        return ['content' => $this->view->render($data, 'admin/reports/investments/initiated-investments')];
    }

    /**
     * referrals
     */
    public function referrals(): array
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];
        
        /* Use Admin Library */
        $admin = $this->library('Admin');
        $data['admin'] = $admin->data();

        // If not logged in, redirect to login page
        if(!$admin->isLoggedIn()):
            redirect('admin/login');
        endif;

        /* Use Input Library */
        $input = $this->library('Input');
        
        /* Use Admin Model */
        $adminModel = $this->model('Admin');
        
        /* Use Settings Model */
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();

        $data['referral-settings'] = $adminModel->referralSettings();

        // Check if the form is submitted via POST method
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate form input
            $validator = $this->library('Validator');
            $validationRules = [
                'percent' => [
                    'required' => true
                ],
                'status' => [
                    'required' => true
                ]
            ];

            $validation = $validator->check($_POST, $validationRules);

            // If validation fails, prepare error messages
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

                    $percent = $input->get('percent');
                    $status = $input->get('status');

                    $update = $adminModel->updateReferralSettings($percent, $status);

                    if ($update) {
                        $response = [
                            'status' => 'success',
                            'message' => 'Referral settings have been updated successfully'
                        ];
                    } else {
                        $response = [
                            'status' => 'error',
                            'message' => 'No changes has been made to the referral settings'
                        ];
                    }
                } catch (Exception $e) {
                    // Error occurred while sending email
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

        // render the referral template
        return ['content' => $this->view->render($data, 'admin/site/referral-settings')];
    }

    /**
     * time
     */
    public function time(): array
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];
        
        /* Use Admin Library */
        $admin = $this->library('Admin');
        $data['admin'] = $admin->data();

        // If not logged in, redirect to login page
        if(!$admin->isLoggedIn()):
            redirect('admin/login');
        endif;

        /* Use Input Library */
        $input = $this->library('Input');
        
        /* Use Admin Model */
        $adminModel = $this->model('Admin');
        
        /* Use Settings Model */
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();

        $data['times'] = $adminModel->times();

        if (isset($this->url[2]) && $this->url[2] == 'edit-time') {
            // Check if the form is submitted via POST method
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Validate form input
                $validator = $this->library('Validator');
                $validationRules = [
                    'timeId' => [
                        'required' => true
                    ],
                    'name' => [
                        'required' => true
                    ],
                    'hours' => [
                        'required' => true
                    ]
                ];

                $validation = $validator->check($_POST, $validationRules);

                // If validation fails, prepare error messages
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

                        $timeId = $input->get('timeId');
                        $name = $input->get('name');
                        $hours = $input->get('hours');

                        $update = $adminModel->updateTimeSettings($timeId, $name, $hours);

                        if ($update) {
                            $response = [
                                'status' => 'success',
                                'message' => 'Time settings have been updated successfully'
                            ];
                        } else {
                            $response = [
                                'status' => 'error',
                                'message' => 'No changes has been made to the time settings'
                            ];
                        }
                    } catch (Exception $e) {
                        // Error occurred while sending email
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

        // Check if the form is submitted via POST method
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate form input
            $validator = $this->library('Validator');
            $validationRules = [
                'name' => [
                    'required' => true
                ],
                'hours' => [
                    'required' => true
                ]
            ];

            $validation = $validator->check($_POST, $validationRules);

            // If validation fails, prepare error messages
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

                    $name = $input->get('name');
                    $hours = $input->get('hours');

                    $update = $adminModel->insertTimeSettings($name, $hours);

                    if ($update) {
                        $response = [
                            'status' => 'success',
                            'message' => 'Time settings have been added successfully'
                        ];
                    } else {
                        $response = [
                            'status' => 'error',
                            'message' => 'An error occurred while adding time settings'
                        ];
                    }
                } catch (Exception $e) {
                    // Error occurred while sending email
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

        // render the referral template
        return ['content' => $this->view->render($data, 'admin/time-settings')];
    }

    /**
     * plans
     */
    public function plans(): array
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];
        
        /* Use Admin Library */
        $admin = $this->library('Admin');
        $data['admin'] = $admin->data();

        // If not logged in, redirect to login page
        if(!$admin->isLoggedIn()):
            redirect('admin/login');
        endif;

        /* Use Input Library */
        $input = $this->library('Input');
        
        /* Use Admin Model */
        $adminModel = $this->model('Admin');
        
        /* Use Settings Model */
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();

        $data['plans'] = $adminModel->plans();
        $data['times'] = $adminModel->times();

        if (isset($this->url[2]) && $this->url[2] == 'edit-plan') {
            // Check if the URL is set and the gateway ID exists
            if (!isset($this->url[3]) || !intval($this->url[3]) || !$adminModel->hasPlanId($this->url[3])) {
                // If gateway ID is not provided or invalid, or if the plan doesn't exist, display an error message and redirect
                $_SESSION['message'][] = ['error', 'Failed to fetch plan details. Please try again later.'];
                redirect('admin/plans');
            }

            $data['plan-details'] = $adminModel->planDetails($this->url[3]);

            // Check if the form is submitted via POST method
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                
                try {
                    // Initialize default values
                    $minimum = 0.00;
                    $maximum = 0.00;
                    $fixed_amount = 0.00;
                    $capital_back_status = 0;
                    $lifetime_status = 0;
                    $repeat_time = 0;
                    
                    $planId = $this->url[3];
                    $name = $input->get('name');
                    $invest_type = $input->get('invest_type');
                    $return_type = $input->get('return_type');
                    $status = $input->get('status');
                    $featured = $input->get('featured');
                    $interest = $input->get('interest');
                    $interest_status = $input->get('interest_status');
                    $times = $input->get('time');

                    if ($invest_type == 1) {
                        $minimum = $input->get('minimum');
                        $maximum = $input->get('maximum');
                    } elseif ($invest_type == 2) {
                        $fixed_amount = $input->get('fixed_amount');
                    }

                    if ($return_type == 1) {
                        $capital_back_status = $input->get('capital_back');
                        $repeat_time = $input->get('repeat_time');
                    } elseif ($return_type == 0) {
                        $lifetime_status = 1;
                    }

                    $update = $adminModel->updatePlan($planId, $name, $minimum, $maximum, $fixed_amount, $interest, $interest_status, $times, $status, $featured, $capital_back_status, $lifetime_status, $repeat_time);

                    if ($update) {
                        $response = [
                            'status' => 'success',
                            'message' => 'Plan has been updated successfully'
                        ];
                    } else {
                        $response = [
                            'status' => 'error',
                            'message' => 'No changes has been made to this plan details'
                        ];
                    }
                } catch (Exception $e) {
                    // Error occurred
                    $response = [
                        'status' => 'error',
                        'message' => $e->getMessage()
                    ];
                }

                // Send the JSON response
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }

            // render the referral template
            return ['content' => $this->view->render($data, 'admin/edit-plan-settings')];
        }

        // Check if the form is submitted via POST method
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            try {
                // Initialize default values
                $minimum = 0.00;
                $maximum = 0.00;
                $fixed_amount = 0.00;
                $capital_back_status = 0;
                $lifetime_status = 0;
                $repeat_time = 0;
                
                $planId = $this->uniqueid();
                $name = $input->get('name');
                $invest_type = $input->get('invest_type');
                $return_type = $input->get('return_type');
                $status = $input->get('status');
                $featured = $input->get('featured');
                $interest = $input->get('interest');
                $interest_status = $input->get('interest_status');
                $times = $input->get('time');

                if ($invest_type == 1) {
                    $minimum = $input->get('minimum');
                    $maximum = $input->get('maximum');
                } elseif ($invest_type == 2) {
                    $fixed_amount = $input->get('fixed_amount');
                }

                if ($return_type == 1) {
                    $capital_back_status = $input->get('capital_back');
                    $repeat_time = $input->get('repeat_time');
                } elseif ($return_type == 0) {
                    $lifetime_status = 1;
                }

                $insert = $adminModel->addPlan($planId, $name, $minimum, $maximum, $fixed_amount, $interest, $interest_status, $times, $status, $featured, $capital_back_status, $lifetime_status, $repeat_time);

                if ($insert) {
                    $response = [
                        'status' => 'success',
                        'message' => 'Plan has been added successfully'
                    ];
                } else {
                    $response = [
                        'status' => 'error',
                        'message' => 'An error occurred while adding the plan'
                    ];
                }
            } catch (Exception $e) {
                // Error occurred
                $response = [
                    'status' => 'error',
                    'message' => $e->getMessage()
                ];
            }

            // Send the JSON response
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }

        // render the referral template
        return ['content' => $this->view->render($data, 'admin/plan-settings')];
    }

    /**
     * loans
     */
    public function loans(): array
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];
        
        /* Use Admin Library */
        $admin = $this->library('Admin');
        $data['admin'] = $admin->data();

        // If not logged in, redirect to login page
        if(!$admin->isLoggedIn()):
            redirect('admin/login');
        endif;

        /* Use Input Library */
        $input = $this->library('Input');
        
        /* Use Admin Model */
        $adminModel = $this->model('Admin');
        
        /* Use Settings Model */
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();

        $data['get-loans'] = $adminModel->getLoans();

        if (isset($this->url[2]) && $this->url[2] == 'approve-loans') {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $validator = $this->library('Validator');
                $validationRules = [
                    'loanId' => ['required' => true]
                ];

                $validation = $validator->check($_POST, $validationRules);

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
                        // Get Loan Details
                        $loanId = $input->get('loanId');
                        $loanDetails = $adminModel->getLoanDetails($loanId);

                        if (!$loanDetails) {
                            throw new Exception("Loan details not found.");
                        }

                        // Get User associated with the loan
                        $userDetails = $adminModel->getUserDetails($loanDetails['userid']);

                        if (!$userDetails) {
                            throw new Exception("User details not found.");
                        }

                        // Calculate new user balance
                        $userNewBalance = $loanDetails['amount'] + $userDetails['interest_wallet'];

                        // Update user balance
                        $update = $adminModel->updateLoan($loanId, $loanDetails['userid'], $userNewBalance);

                        if ($update == 1) {
                            // Email notification is enabled
                            if ($data['settings']["email_notification"] == "1") {
                                $siteName = $data['settings']['sitename'];
                                $siteUrl = getenv('URL_PATH');
                                $dateNow = date('Y');

                                // Fetch the email template for loan approval
                                $emailTemplates = $settingsModel->getEmailTemplate();
                                $approveLoanTemplate = $emailTemplates[36] ?? null;

                                if ($approveLoanTemplate) {
                                    $approveLoanTemplate['body'] = str_replace(
                                        ['{FIRSTNAME}', '{LASTNAME}', '{AMOUNT}', '{SITENAME}', '{URL}', '{DATENOW}'],
                                        [$userDetails['firstname'], $userDetails['lastname'], $loanDetails['amount'], $siteName, $siteUrl, $dateNow],
                                        $approveLoanTemplate['body']
                                    );

                                    // Send email notification to the user
                                    $recipientEmail = $userDetails['email'];
                                    $subject = $approveLoanTemplate['subject'];
                                    $body = $approveLoanTemplate['body'];

                                    if (emailhelper::sendEmail($data['settings'], $recipientEmail, $subject, $body)) {
                                        $response = [
                                            'status' => 'success',
                                            'message' => 'Loan request approved successfully.'
                                        ];
                                    } else {
                                        $response = [
                                            'status' => 'error',
                                            'message' => 'Failed to send email notification. Please try again.'
                                        ];
                                    }
                                } else {
                                    throw new Exception("Email template not found.");
                                }
                            } else {
                                // Email notification is disabled
                                $response = [
                                    'status' => 'success',
                                    'message' => 'Loan request approved successfully.'
                                ];
                            }
                        } else {
                            throw new Exception("Failed to update user balance.");
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
        }elseif (isset($this->url[2]) && $this->url[2] == 'reject-loans') {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $validator = $this->library('Validator');
                $validationRules = [
                    'loanId' => ['required' => true]
                ];

                $validation = $validator->check($_POST, $validationRules);

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
                        // Get Loan Details
                        $loanId = $input->get('loanId');
                        $loanDetails = $adminModel->getLoanDetails($loanId);

                        if (!$loanDetails) {
                            throw new Exception("Loan details not found.");
                        }

                        // Get User associated with the loan
                        $userDetails = $adminModel->getUserDetails($loanDetails['userid']);

                        if (!$userDetails) {
                            throw new Exception("User details not found.");
                        }

                        // Mark the loan as rejected
                        $rejectLoan = $adminModel->rejectLoan($loanId);

                        if ($rejectLoan == 1) {
                            // Email notification is enabled
                            if ($data['settings']["email_notification"] == "1") {
                                $siteName = $data['settings']['sitename'];
                                $siteUrl = getenv('URL_PATH');
                                $dateNow = date('Y');

                                // Fetch the email template for loan rejection
                                $emailTemplates = $settingsModel->getEmailTemplate();
                                $rejectLoanTemplate = $emailTemplates[37] ?? null; // Assuming template ID 37 is for rejection

                                if ($rejectLoanTemplate) {
                                    $rejectLoanTemplate['body'] = str_replace(
                                        ['{FIRSTNAME}', '{LASTNAME}', '{AMOUNT}', '{SITENAME}', '{URL}', '{DATENOW}'],
                                        [$userDetails['firstname'], $userDetails['lastname'], $loanDetails['amount'], $siteName, $siteUrl, $dateNow],
                                        $rejectLoanTemplate['body']
                                    );

                                    // Send email notification to the user
                                    $recipientEmail = $userDetails['email'];
                                    $subject = $rejectLoanTemplate['subject'];
                                    $body = $rejectLoanTemplate['body'];

                                    if (emailhelper::sendEmail($data['settings'], $recipientEmail, $subject, $body)) {
                                        $response = [
                                            'status' => 'success',
                                            'message' => 'Loan request rejected successfully.'
                                        ];
                                    } else {
                                        $response = [
                                            'status' => 'error',
                                            'message' => 'Failed to send email notification. Please try again.'
                                        ];
                                    }
                                } else {
                                    throw new Exception("Email template not found.");
                                }
                            } else {
                                // Email notification is disabled
                                $response = [
                                    'status' => 'success',
                                    'message' => 'Loan request rejected successfully.'
                                ];
                            }
                        } else {
                            throw new Exception("Failed to reject the loan.");
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

        // render the loans template
        return ['content' => $this->view->render($data, 'admin/reports/loans')];
    }

    /**
     * handle-plan-purchase
     */
    public function handlePlanPurchase($investId, $data, $planId, $amount, $interest_amount, $repeat_time, $hours, $method, $details, $trx_type, $capital_back_status, $insertType): void
    {
        // User Model
        $userModel = $this->model('User');

        /* Use Referral Model */
        $referralModel = $this->model('Referral');
        $data['ranks'] = $referralModel->getRanks();
        $data['count-invests'] = $referralModel->countInvestments($data['user']['userid']);
        $data['count-referrals'] = $referralModel->countReferrals($data['user']['userid']);

        $data['user_ranking_id'] = 0;
        $data['bonus'] = 0;

        foreach ($data['ranks'] as $rank) {
            if ($rank['id'] > $data['user']['user_ranking_id']) {
                if ($data['count-invests'] >= $rank['min_invest'] && $data['count-referrals'] >= $rank['min_referral']) {
                    $data['user_ranking_id'] = $rank['id'];
                    $data['bonus'] = $rank['bonus'];
                }
            }
        }

        // subtract the amount from the interest wallet and get the new amount
        $amount_new = $data['user']['interest_wallet'] - $amount;

        // Ensure the result is not negative, set it to 0 if it's negative
        $amount_new = max(0.00, $amount_new);

        // Perform plan purchase based on insertType
        $insert = $insertType($investId, $data['user']['userid'], $planId, $amount_new, $interest_amount, $repeat_time, $hours, $amount, $method, $details, $trx_type, $capital_back_status);

        if ($insert == 1) {

            // check if the user ranking is enabled
            if ($data['settings']["user_ranking"] == 1) {
                // Determine next rank id
                $userModel->updateRank($data['user']['userid'], $data['bonus'], $data['user_ranking_id']);
            }

            $response = [
                'status' => 'success',
                'message' => 'You have successfully added an investment record for this user.'
            ];
        } else {
            $response = [
                'status' => 'info',
                'redirect' => 'An error occurred while adding investment record for this user.'
            ];
        }

        // Send the JSON response
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    /**
     * Logout
     */
    public function logout(): void
    {
        $admin = $this->library('Admin');
        
        $admin->logout();
        
        redirect('admin/login');
    }

    /**
     * Send Json Response
     */
    public function sendJsonResponse($response): void
    {
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    // Generate a random string of specified length
    private function rando(): string
    {
        $str = "";
        $characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
        $max = count($characters) - 1;
        for ($i = 0; $i < 14; $i++) {
            $rand = mt_rand(0, $max);
            $str .= $characters[$rand];
        }
        return $str;
    }

    /**
     * Generate a unique transaction ID
     *
     * This method generates a unique transaction ID, which can be used for identifying transactions.
     *
     * @return string The generated unique transaction ID.
     */
    private function generateTransactionID(): string
    {
        // Generate a unique transaction ID, such as using a random string or a combination of timestamp and user ID
        // Here's an example using a random string of length 10
        $chars = 'ABCDEFGHJKMNOPQRSTUVWXYZ123456789';
        $chars_len = strlen($chars);
        $trx_id = '';
        for ($i = 0; $i < 10; $i++) {
            $trx_id .= $chars[rand(0, $chars_len - 1)];
        }
        return $trx_id;
    }

    /**
     * Generate a unique ID
     *
     * This method generates a unique ID, which can be used for various purposes.
     *
     * @return string The generated unique ID.
     */
    private function uniqueid(): string
    {
        // Generate a unique ID based on current timestamp and a random number
        return substr(number_format(time() * rand(), 0, '', ''), 0, 12);
    }

    /**
     * Generate a unique ID for deposit
     *
     * @return string
     */
    private function investmentId(): string
    {
        return substr(number_format(time() * rand(), 0, '', ''), 0, 12);
    }
}