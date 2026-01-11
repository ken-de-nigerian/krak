<?php

namespace Fir\Controllers;

use Exception;
use Fir\Helpers\EmailHelper;

class User extends Controller 
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
    public function index(): void
    {
        redirect('login');
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

        /* Use User Library */
        $user = $this->library('User');
        $data['user'] = $user->data();

        // Redirect if a user is not logged in or is blocked
        if (!$user->isLoggedIn()) {
            redirect('login');
        } elseif($data['user']['status'] == 2) {
            redirect('blocked');
        }

        /* Use Models */
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();

        if ($data['settings']["twofa_status"] == 1) {
            // check if twofactor check was initiated
            if ($user->data()["twofactor_flag"] == 1) {
                redirect('twofa');
            }
        }

        /* Use Models */
        $userModel = $this->model('User');

        $data['get-gateway'] = $settingsModel->getGatewaysWithConversion(); 

        // Retrieve all pending money requests
        $data['requests'] = $userModel->getAllPendingRequests($data['user']['userid']);

        // unset the sessions and redirect
        $session = $this->library('Session');
        $session->delete('referral_id');
        $session->delete('planId');
        $session->delete('amount');
        $session->delete("investId");
        $session->delete('access_token');

        /* Use Input Library */
        $input = $this->library('Input');

        if ($input->isAjax() && $input->get('page')) {
            $page = $input->get('page');
            $transactions = $userModel->transactions_limits($data['user']['userid'], $page);

            // Return the JSON response
            header('Content-Type: application/json');
            echo json_encode(['transactions' => $transactions]);
            exit();
        } else {
            $data['deposits'] = $userModel->deposits($data['user']['userid']);
            $data['payouts'] = $userModel->withdrawals($data['user']['userid']);
            $data['investments'] = $userModel->investments($data['user']['userid']);
            $data['get-transactions'] = $userModel->getTransactions($data['user']['userid']);
        }

        // Default: Render dashboard view
        return ['content' => $this->view->render($data, 'user/dashboard')];
    }

    /**
     * profile
     */
    public function profile(): array
    {
        // Data array to store all data passed to the views
        $data = [];

        /* Use User Model */
        $user = $this->library('User');
        $data['user'] = $user->data();

        // Redirect if a user is not logged in or is blocked
        if (!$user->isLoggedIn()) {
            redirect('login');
        } elseif($data['user']['status'] == 2) {
            redirect('blocked');
        }

        /* Use Models */
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();

        if ($data['settings']["twofa_status"] == 1) {
            // check if twofactor check was initiated
            if ($user->data()["twofactor_flag"] == 1) {
                redirect('twofa');
            }
        }

        /* Use Input Library */
        $input = $this->library('Input');

        /* Use Models */
        $userModel = $this->model('User');

        // unset the sessions and redirect
        $session = $this->library('Session');
        $session->delete('referral_id');
        $session->delete('planId');
        $session->delete('amount');
        $session->delete("investId");
        $session->delete('access_token');

        // Check if the request method is POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Check if input exists
            if ($input->exists()) {

                $validator = $this->library('Validator');

                // Validate input data
                $validation = $validator->check($_POST, [
                    'firstname' => [
                        'required' => true,
                        'alpha' => true
                    ],
                    'lastname' => [
                        'required' => true,
                        'alpha' => true
                    ],
                    'address_1' => [
                        'required' => true
                    ],
                    'country' => [
                        'required' => true
                    ],
                    'city' => [
                        'required' => true
                    ],
                    'state' => [
                        'required' => true
                    ],
                    'timezone' => [
                        'required' => true
                    ]
                ]);

                if (!$validation->fails()) {
                    try {
                        // Update user profile
                        $update = $userModel->updateuser(
                            $input->get('firstname'),
                            $input->get('lastname'),
                            $input->get('address_1'),
                            $input->get('address_2'),
                            $input->get('country'),
                            $input->get('city'),
                            $input->get('state'),
                            $input->get('timezone'),
                            $data['user']['userid']
                        );

                        if ($update == 1) {
                            // Success response if profile is updated
                            $response = [
                                'status' => 'success',
                                'redirect' => 'user/profile',
                                'message' => 'Your profile information has been updated successfully.'
                            ];
                        } else {
                            // Error response if no changes are made to the profile
                            $response = [
                                'status' => 'error',
                                'message' => 'No changes have been made to your profile.'
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

        // Render the view for editing the profile
        return ['content' => $this->view->render($data, 'user/profile')];
    }

    /**
     * verifications
     */
    public function verifications(): array
    {
        // Data array to store all data passed to the views
        $data = [];

        /* Use User Model */
        $user = $this->library('User');
        $data['user'] = $user->data();

        // Redirect if a user is not logged in or is blocked
        if (!$user->isLoggedIn()) {
            redirect('login');
        } elseif($data['user']['status'] == 2) {
            redirect('blocked');
        }

        /* Use Models */
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();

        if ($data['settings']['kyc_status'] == 2) {
            redirect('user/dashboard');
        }

        if ($data['settings']["twofa_status"] == 1) {
            // check if twofactor check was initiated
            if ($user->data()["twofactor_flag"] == 1) {
                redirect('twofa');
            }
        }

        /* Use User Model */
        $user = $this->library('User');
        $data['user'] = $user->data();

        /* Use Models */
        $userModel = $this->model('User');

        /* Use Input Library */
        $input = $this->library('Input');

        $data['settings'] = $settingsModel->get();

        // unset the sessions and redirect
        $session = $this->library('Session');
        $session->delete('referral_id');
        $session->delete('planId');
        $session->delete('amount');
        $session->delete("investId");
        $session->delete('access_token');

        // Fetch the email template with id = 5 & 6
        $data['email-templates'] = $settingsModel->getEmailTemplate();
        $identityTemplate = $data['email-templates'][5] ?? null;
        $addressTemplate = $data['email-templates'][6] ?? null;

        $data['identity-proof'] = $userModel->identity_proof($data['user']['userid']);
        $data['personal-address'] = $userModel->personal_address($data['user']['userid']);

        if (isset($this->url[2]) && $this->url[2] == 'personal-id') {

            // Process documents to upload
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && $input->exists()) {
                $validator = $this->library('Validator');
                $validation = $validator->check($_POST, [
                    'photoimg' => [
                        'required' => true
                    ],
                    'identity_type' => [
                        'required' => true
                    ],
                    'identity_number' => [
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
                        'status' => 'warning',
                        'message' => $errorMessages
                    ];
                } else {
                    try {
                        // file formats
                        $validFormats = ["jpg", "jpeg", "png"];
        
                        $name = $_FILES['photoimg']['name'];
                        $size = $_FILES['photoimg']['size'];
                        $type = $_FILES['photoimg']['type'];
        
                        if (!empty($name)) {
        
                            $fileFormat = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        
                            if ($_FILES['photoimg']['error'] === UPLOAD_ERR_OK) {
                                if (in_array($fileFormat, $validFormats)) {
                                    // Check file size
                                    if ($size <= 2097152) { // 2MB in bytes
                                        $newSize = (int)($size / 1024); // new file size in KB (converted to integer)
        
                                        $fileName = $this->rando() . '.' . $fileFormat;
                                        $uploadid = $this->uniqueid();
        
                                        // Define the destination directory
                                        $path = sprintf('%s/../../%s/%s/identity-proof/', __DIR__, PUBLIC_PATH, UPLOADS_PATH);
                                        
                                        // Create directory if it doesn't exist
                                        if (!file_exists($path)) {
                                            mkdir($path, 0755, true);
                                        }
        
                                        $destination = $path . $fileName;
        
                                        // Move uploaded file to destination
                                        if (move_uploaded_file($_FILES['photoimg']['tmp_name'], $destination)) {
        
                                            $insert = $userModel->addIdentity($uploadid, $data['user']['userid'], $input->get('identity_type'), $input->get('identity_number'), $fileName, $type, $fileFormat, $newSize);
        
                                            if ($insert == 1) {
        
                                                // email notification is enabled
                                                if ($data['settings']["email_notification"] == 1) {
        
                                                    $siteName = $data['settings']['sitename'];
                                                    $siteLogo = $data['settings']['logo'];
                                                    $siteUrl = getenv('URL_PATH');
                                                    $dateNow = date('Y');
        
                                                    // identity template is enabled
                                                    if ($identityTemplate !== null && $identityTemplate['status'] == 1) {
                                                        // Replace the placeholders with user input in the email body
                                                        $identityTemplate['body'] = str_replace(['{FIRSTNAME}', '{LASTNAME}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'], [$data['user']['firstname'], $data['user']['lastname'], $siteName, $siteLogo, $siteUrl, $dateNow], $identityTemplate['body']);
        
                                                        $recipientEmail = $data['user']['email'];
                                                        $subject = $identityTemplate['subject'];
                                                        $body = $identityTemplate['body'];
        
                                                        // Send email
                                                        if (EmailHelper::sendEmail($data['settings'], $recipientEmail, $subject, $body)) {
                                                            // Email sent successfully
                                                            $response = [
                                                                'status' => 'success',
                                                                'message' => 'Your document has been uploaded successfully',
                                                                'redirect' => 'user/verifications/personal-id'
                                                            ];
                                                        } else {
                                                            // Failed to send email
                                                            $response = [
                                                                'status' => 'error',
                                                                'message' => 'Your document was uploaded, but we failed to send email',
                                                                'redirect' => 'user/verifications/personal-id'
                                                            ];
                                                        }
                                                    } else {
                                                        // Identity template is disabled
                                                        $response = [
                                                            'status' => 'success',
                                                            'message' => 'Your document has been uploaded successfully',
                                                            'redirect' => 'user/verifications/personal-id'
                                                        ]; 
                                                    }
                                                } else {
                                                    // Email notification is disabled
                                                    $response = [
                                                        'status' => 'success',
                                                        'message' => 'Your document has been uploaded successfully',
                                                        'redirect' => 'user/verifications/personal-id'
                                                    ];
                                                }
                                            } else {
                                                $response = [
                                                    'status' => 'warning',
                                                    'message' => 'Error saving document details, please try again.'
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
                                'status' => 'warning',
                                'message' => 'No document selected, please choose a document and try again.'
                            ];
                        }
                    } catch (Exception $e) {
                        $response = [
                            'status' => 'warning',
                            'message' => $e->getMessage()
                        ];
                    }
                }
        
                // Send the JSON response
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }
            // Render the view for editing the verifications
            return ['content' => $this->view->render($data, 'user/verifications/personal-id')];
        }elseif (isset($this->url[2]) && $this->url[2] == 'personal-address') {

            // Process documents to upload
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && $input->exists()) {
                $validator = $this->library('Validator');
                $validation = $validator->check($_POST, [
                    'photoimg' => [
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
                        'status' => 'warning',
                        'message' => $errorMessages
                    ];
                } else {
                    try {
                        // File formats
                        $validFormats = ["jpg", "jpeg", "png"];

                        $name = $_FILES['photoimg']['name'];
                        $size = $_FILES['photoimg']['size'];
                        $type = $_FILES['photoimg']['type'];

                        if (!empty($name)) {
                            $fileFormat = strtolower(pathinfo($name, PATHINFO_EXTENSION));

                            if ($_FILES['photoimg']['error'] === UPLOAD_ERR_OK) {
                                // Check if the file format is valid
                                if (in_array($fileFormat, $validFormats)) {
                                    // Check if the file size is within the limit (2MB)
                                    if ($size <= 2097152) { // 2MB in bytes
                                        $newSize = $size / 1024; // New file size in KB

                                        $fileName = $this->rando() . '.' . $fileFormat;
                                        $uploadid = $this->uniqueid();

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

                                        // Define the destination directory
                                        $path = sprintf('%s/../../%s/%s/address-proof/', __DIR__, PUBLIC_PATH, UPLOADS_PATH);

                                        // Save the resized image to a file
                                        if (imagejpeg($resized_image, $path . $fileName)) {

                                            $insert = $userModel->addAddress($uploadid, $data['user']['userid'], $fileName, $type, $fileFormat, $newSize);

                                            if ($insert == 1) {
                                                
                                                // email notification is enabled
                                                if ($data['settings']["email_notification"] == 1) {

                                                    $siteName = $data['settings']['sitename'];
                                                    $siteLogo = $data['settings']['logo'];
                                                    $siteUrl = getenv('URL_PATH');
                                                    $dateNow = date('Y');

                                                    // address template is enabled
                                                    if ($addressTemplate !== null && $addressTemplate['status'] == 1) {
                                                        // Replace the placeholders with user input in the email body
                                                        $addressTemplate['body'] = str_replace(['{FIRSTNAME}', '{LASTNAME}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'], [$data['user']['firstname'], $data['user']['lastname'], $siteName, $siteLogo, $siteUrl, $dateNow], $addressTemplate['body']);

                                                        $recipientEmail = $data['user']['email'];
                                                        $subject = $addressTemplate['subject'];
                                                        $body = $addressTemplate['body'];

                                                        // Send email
                                                        if (EmailHelper::sendEmail($data['settings'], $recipientEmail, $subject, $body)) {
                                                            $response = [
                                                                'status' => 'success',
                                                                'message' => 'Your document has been uploaded successfully',
                                                                'redirect' => 'user/verifications/personal-address'
                                                            ];
                                                        } else {
                                                            $response = [
                                                                'status' => 'error',
                                                                'message' => 'Your document was uploaded, but we failed to send email',
                                                                'redirect' => 'user/verifications/personal-address'
                                                            ];
                                                        }
                                                    }else{
                                                        $response = [
                                                            'status' => 'success',
                                                            'message' => 'Your document has been uploaded successfully',
                                                            'redirect' => 'user/verifications/personal-address'
                                                        ];
                                                    }
                                                }else{
                                                    $response = [
                                                        'status' => 'success',
                                                        'message' => 'Your document has been uploaded successfully',
                                                        'redirect' => 'user/verifications/personal-address'
                                                    ];
                                                }
                                            } else {
                                                $response = [
                                                    'status' => 'warning',
                                                    'message' => 'Error saving document details, please try again.'
                                                ];
                                            }
                                        } else {
                                            $response = [
                                                'status' => 'warning',
                                                'message' => 'Unable to upload the document, please try again.'
                                            ];
                                        }

                                        // Free up memory
                                        imagedestroy($image);
                                        imagedestroy($resized_image);
                                    } else {
                                        // File size exceeds the limit
                                        $response = [
                                            'status' => 'warning',
                                            'message' => 'File size exceeds the maximum limit of 2MB.'
                                        ];
                                    }
                                } else {
                                    // Invalid file format
                                    $response = [
                                        'status' => 'warning',
                                        'message' => 'Allowed file extensions: jpg, jpeg, png'
                                    ];
                                }
                            } else {
                                // Error in uploading the file
                                $response = [
                                    'status' => 'warning',
                                    'message' => 'Unable to upload the document, please try again.'
                                ];
                            }
                        } else {
                            // No document selected
                            $response = [
                                'status' => 'warning',
                                'message' => 'No document selected, please choose a document and try again.'
                            ];
                        }
                    } catch (Exception $e) {
                        // Exception occurred
                        $response = [
                            'status' => 'warning',
                            'message' => $e->getMessage()
                        ];
                    }
                }

                // Send the JSON response
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }
            // Render the view for editing the verifications
            return ['content' => $this->view->render($data, 'user/verifications/personal-address')];
        }elseif (isset($this->url[2]) && $this->url[2] == 'two-factor') {

            // Check if the request method is POST
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                // Check if input exists
                if ($input->exists()) {

                    $validator = $this->library('Validator');

                    // Validate input data
                    $validation = $validator->check($_POST, [
                        'twofactor_status' => [
                            'required' => true
                        ]
                    ]);

                    if (!$validation->fails()) {
                        try {
                            // Update user 2-factor Authentication Method
                            $update = $userModel->twofactor($input->get('twofactor_status'), $data['user']['userid']
                            );

                            if ($update == 1) {
                                // Success response if profile is updated
                                $response = [
                                    'status' => 'success',
                                    'message' => '2-factor auth status has been updated',
                                    'redirect' => 'user/verifications/two-factor'
                                ];
                            } else {
                                // Error response if no changes are made to the profile
                                $response = [
                                    'status' => 'error',
                                    'message' => 'No changes have been made to your profile.'
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

            // Render the view for editing the verifications
            return ['content' => $this->view->render($data, 'user/verifications/two-factor-authentication')];
        }else{
            redirect ('user/verifications/personal-id');
        }

        // return an empty array
        return [];
    }

    /**
     * download identity-proof
     */
    public function identity_proof(): void
    {

        /*Use User Library*/
        $user = $this->library('User');
        $data['user'] = $user->data();
        
        if($user->isLoggedIn() === true):   
            
            /* Use User Model */
            $userModel = $this->model('User');
            $has_file = $userModel->has_identity_proof($this->url[2], $data['user']['userid']);
            if($has_file === true):
                
                $file = $userModel->get_identity_proof($this->url[2]);
                $filepath = URL_PATH.'/'.PUBLIC_PATH.'/'.UPLOADS_PATH.'/identity-proof/'.$file["fileupload"];  

                // Process download
                header('Content-Type: application/octet-stream');
                header("Content-Transfer-Encoding: Binary"); 
                header("Content-disposition: attachment; filename=\"" . basename($filepath) . "\""); 
                readfile($filepath);  
                exit;
                
            else:
                $_SESSION['message'][] = ['error', 'Unable to download the file. Please try again.'];
                redirect('user/verifications/personal-id');
            endif;
        else:
            $_SESSION['message'][] = ['error', 'Unable to download the file. Please login'];
            redirect();  
        endif;
    }

    /**
     * download address-proof
     */
    public function address_proof(): void
    {

        /*Use User Library*/
        $user = $this->library('User');
        $data['user'] = $user->data();
        
        if($user->isLoggedIn() === true):   
            
            /* Use User Model */
            $userModel = $this->model('User');
            $has_file = $userModel->has_address_proof($this->url[2], $data['user']['userid']);
            if($has_file === true):
                
                $file = $userModel->get_address_proof($this->url[2]);
                $filepath = URL_PATH.'/'.PUBLIC_PATH.'/'.UPLOADS_PATH.'/address-proof/'.$file["fileupload"];  

                // Process download
                header('Content-Type: application/octet-stream');
                header("Content-Transfer-Encoding: Binary"); 
                header("Content-disposition: attachment; filename=\"" . basename($filepath) . "\""); 
                readfile($filepath);  
                exit;
                
            else:
                $_SESSION['message'][] = ['error', 'Unable to download the file. Please try again.'];
                redirect('user/verifications/personal-id');
            endif;
        else:
            $_SESSION['message'][] = ['error', 'Unable to download the file. Please login'];
            redirect();  
        endif;
    }

    /**
     * upload
     */
    public function upload(): void
    {
        // Data array to store all data passed to the views
        $data = [];

        // Use User Model
        $user = $this->library('User');
        $data['user'] = $user->data();

        // Use Models
        $userModel = $this->model('User');

        /* Use Input Library */
        $input = $this->library('Input');

        // Process profile image upload
        if ($input->isAjax()) {

            // Validate the incoming request
            $validator = $this->library('Validator');
            $validation = $validator->check($_POST, [
                'photoimg' => [
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
                                    $update = $userModel->profileDetails($fileName, $data['user']['userid']);

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
            redirect('user/profile');
        }
    }
    
    /**
     * currency
     */
    public function currency(): void
    {
        // Data array to store all data passed to the views
        $data = [];

        /* Use User Model */
        $user = $this->library('User');
        $data['user'] = $user->data();

        /* Use Input Library */
        $input = $this->library('Input');

        /* Use Models */
        $userModel = $this->model('User');

        // Check if the request method is POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Check if input exists
            if ($input->exists()) {

                // Validate input data
                $validator = $this->library('Validator');
                $validation = $validator->check($_POST, [
                    'currency' => [
                        'required' => true,
                    ]
                ]);

                if (!$validation->fails()) {
                    try {
                        // Update currency
                        $update = $userModel->currency($input->get('currency'), $data['user']['userid']);

                        if ($update == 1) {
                            $response = [
                                'status' => 'success',
                                'message' => 'Your currency has been successfully updated.',
                                'redirect' => 'user/profile'
                            ];
                        } else {
                            $response = [
                                'status' => 'error',
                                'message' => 'No changes were made to your account.'
                            ];
                        }
                    } catch (Exception $e) {
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
        } else {
            // If the request is not a POST request, redirect to the user profile page
            redirect('user/profile');
        }
    }

    /**
     * password-change
     */
    public function password(): void
    {
        /**
         * The $data array stores all the data passed to the views.
         */
        $data = [];

        /* Use User Model */
        $user = $this->library('User');
        $data['user'] = $user->data();

        /* Use Input Library */
        $input = $this->library('Input');

        /* Use Models */
        $userModel = $this->model('User');
        $settingsModel = $this->model('Settings');

        $data['settings'] = $settingsModel->get();

        // Fetch the email template with id = 3
        $data['email-templates'] = $settingsModel->getEmailTemplate();
        $resetTemplate = $data['email-templates'][3] ?? null;

        // Check if the request method is POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Check if input exists
            if ($input->exists()) {

                $validator = $this->library('Validator');

                // Validate input data
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
                            if (password_verify($input->get('oldPassword'), $data['user']['password'])) {

                                /* Hash New Password */
                                $password = password_hash($input->get('password'), PASSWORD_DEFAULT);

                                // Update password
                                $update = $userModel->password($password, $data['user']['userid']);

                                if ($update == 1) {

                                    // email notification is enabled
                                    if ($data['settings']["email_notification"] == 1) {

                                        $siteName = $data['settings']['sitename'];
                                        $siteLogo = $data['settings']['logo'];
                                        $siteUrl = getenv('URL_PATH');
                                        $dateNow = date('Y');

                                        // reset template is enabled
                                        if ($resetTemplate !== null && $resetTemplate['status'] == 1) {

                                            $resetTemplate['body'] = str_replace(['{FIRSTNAME}', '{LASTNAME}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'], [$data['user']["firstname"], $data['user']["lastname"], $siteName, $siteLogo, $siteUrl, $dateNow], $resetTemplate['body']);

                                            // Send email with success notification to user
                                            $recipientEmail = $data['user']['email'];
                                            $subject = $resetTemplate['subject'];
                                            $body = $resetTemplate['body'];

                                            // If email is sent successfully
                                            if (EmailHelper::sendEmail($data['settings'], $recipientEmail, $subject, $body)) {
                                                $response = [
                                                    'status' => 'success',
                                                    'message' => 'Your password has been successfully changed.',
                                                    'redirect' => 'user/profile'
                                                ];
                                            }else{
                                                $response = [
                                                    'status' => 'warning',
                                                    'message' => 'The password reset was successful, but there was an issue while sending you a notification.',
                                                    'redirect' => 'user/profile'
                                                ];
                                            }
                                        }else{
                                            $response = [
                                                'status' => 'success',
                                                'message' => 'Your password has been successfully changed.',
                                                'redirect' => 'user/profile'
                                            ];
                                        }
                                    }else{
                                        $response = [
                                            'status' => 'success',
                                            'message' => 'Your password has been successfully changed.',
                                            'redirect' => 'user/profile'
                                        ];   
                                    }
                                } else {
                                    $response = [
                                        'status' => 'error',
                                        'message' => 'No changes were made to your account.'
                                    ];
                                }
                            } else {
                                $response = [
                                    'status' => 'error',
                                    'message' => 'Your current password does not match our records. Please double-check and try again.'
                                ];
                            }
                        } else {
                            $response = [
                                'status' => 'error',
                                'message' => 'Your passwords do not match. Please try again.'
                            ];
                        }
                    } catch (Exception $e) {
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
        } else {
            // If the request is not a POST request, redirect to the user profile page
            redirect('user/profile');
        }
    }

    /**
     * phone
     */
    public function phone(): void
    {
        // Data array to store all data passed to the views
        $data = [];

        /* Use User Model */
        $user = $this->library('User');
        $data['user'] = $user->data();

        /* Use Input Library */
        $input = $this->library('Input');

        /* Use Models */
        $userModel = $this->model('User');

        // Check if the request method is POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Check if input exists
            if ($input->exists()) {

                $validator = $this->library('Validator');

                // Validate input data
                $validation = $validator->check($_POST, [
                    'formattedPhone' => [
                        'required' => true
                    ]
                ]);

                if (!$validation->fails()) {
                    try {
                        // Check if the phone number is already registered to this user.
                        if ($input->get('formattedPhone') != $data['user']['phone']) {

                            // Check if the phone number is already registered to another user.
                            $has_phone = $userModel->hasPhone($input->get('formattedPhone'));

                            if (!$has_phone) {

                                // Update phone
                                $update = $userModel->phone($input->get('formattedPhone'), $input->get('country'), $data['user']['userid']);

                                if ($update == 1) {
                                    $response = [
                                        'status' => 'success',
                                        'message' => 'Your phone number has been successfully updated.',
                                        'redirect' => 'user/profile'
                                    ];
                                } else {
                                    $response = [
                                        'status' => 'error',
                                        'message' => 'No changes were made to your account.'
                                    ];
                                }
                            }else {
                                // Phone number already registered
                                $response = [
                                    'status' => 'error',
                                    'message' => 'This phone number is already registered to another user.',
                                ];
                            }
                        } else{
                            $response = [
                                'status' => 'error',
                                'message' => 'This phone number is already registered to you.'
                            ];
                        }
                    } catch (Exception $e) {
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
        } else {
            // If the request is not a POST request, redirect to the user profile page
            redirect('user/profile');
        }
    }

    /**
     * wallets
     */
    public function wallets(): array
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];

        /* Use User Model */
        $user = $this->library('User');
        $data['user'] = $user->data();

        // Redirect if a user is not logged in or is blocked
        if (!$user->isLoggedIn()) {
            redirect('login');
        } elseif($data['user']['status'] == 2) {
            redirect('blocked');
        }

        /* Use Models */
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();

        if ($data['settings']["twofa_status"] == 1) {
            // check if twofactor check was initiated
            if ($user->data()["twofactor_flag"] == 1) {
                redirect('twofa');
            }
        }

        /* Use Models */
        $userModel = $this->model('User');

        $data['get-gateway'] = $settingsModel->getGateways(); 

        // unset the sessions and redirect
        $session = $this->library('Session');
        $session->delete('referral_id');
        $session->delete('planId');
        $session->delete('amount');
        $session->delete("investId");
        $session->delete('access_token');

        $data['transaction'] = $userModel->getTransaction($data['user']['userid']);

        // Default: Render wallets view
        return ['content' => $this->view->render($data, 'user/wallets')];
    }

    /**
     * This method handles the AJAX request to fetch slug and amount.
     *
     * @return void JSON response containing slug and amount
     */
    public function fetch(): void
    {
        // Get user data
        $user = $this->library('User');
        $userData = $user->data();
    
        // Get currency mapping
        $currencyMap = [
            "€" => "eur",
            "£" => "gbp",
            "$"  => "usd",
        ];
    
        // Determine source currency based on user currency
        $sourceCurrency = $currencyMap[$userData['currency']] ?? '';
    
        // Get target currency and amount from request parameters
        $targetCurrency = $_GET['abbreviation'] ?? '';
        $amount = isset($_GET['amount']) ? floatval($_GET['amount']) : 0;
    
        // Ensure both target currency and amount are provided
        if (empty($targetCurrency)) {
            $response = [
                'status' => 'error',
                'message' => 'Invalid currency'
            ];
        } else {
            // Prepare API URL
            $apiUrl = "https://min-api.cryptocompare.com/data/price?fsym=" . urlencode($sourceCurrency) . "&tsyms=" . urlencode($targetCurrency);
    
            // Initialize cURL
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification for troubleshooting
    
            // Execute cURL request
            $apiResponse = curl_exec($ch);
            $curlError = curl_error($ch);
            curl_close($ch);
    
            if ($apiResponse !== false) {
                // Decode JSON response
                $data = json_decode($apiResponse, true);
    
                // Check if the target currency exists in the response
                if (isset($data[$targetCurrency])) {
                    // Calculate the converted amount
                    $convertedAmount = $data[$targetCurrency] * $amount;
    
                    // Format converted amount
                    $convertedAmountFormatted = number_format($convertedAmount, 6);
    
                    $response = [
                        'status' => 'success',
                        'converted' => $convertedAmountFormatted
                    ];
                } else {
                    $response = [
                        'status' => 'error',
                        'message' => 'Invalid currency'
                    ];
                }
            } else {
                // Construct error message
                $errorMessage = $curlError ?: "Failed to get conversion rate";
    
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
     * deposit
     */
    public function deposit(): array
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];

        /* Use User Model */
        $user = $this->library('User');
        $data['user'] = $user->data();

        // Redirect if a user is not logged in or is blocked
        if (!$user->isLoggedIn()) {
            redirect('login');
        } elseif($data['user']['status'] == 2) {
            redirect('blocked');
        }

        /* Use Models */
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();

        if ($data['settings']["twofa_status"] == 1) {
            // check if twofactor check was initiated
            if ($user->data()["twofactor_flag"] == 1) {
                redirect('twofa');
            }
        }

        /* Use Input Library */
        $input = $this->library('Input');

        // Use Models
        $userModel = $this->model('User');

        $data['get-gateway'] = $settingsModel->getGateways(); 

        $session = $this->library('Session');

        // Check if the request method is POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Check if input exists
            if ($input->exists()) {

                $validator = $this->library('Validator');

                // Validate input data
                $validation = $validator->check($_POST, [
                    'amount' => [
                        'required' => true,
                        'float' => true
                    ],
                    'method_code' => [
                        'required' => true,
                        'digit' => true
                    ]
                ]);

                if (!$validation->fails()) {
                    try {
                        // Get input data
                        $amount = $input->get('amount');
                        $method_code = $input->get('method_code');

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

                                // Insert deposit details
                                $insert = $userModel->deposit($depositId, $data['user']['userid'], $method_code, $amount, $trx);

                                if ($insert === 1) {
                                    // Success response
                                    $response = [
                                        'status' => 'success',
                                        'redirect' => 'user/confirm/deposit/' . $depositId. '/' .$method_code
                                    ];
                                }else{
                                    $response = [
                                        'status' => 'error',
                                        'message' => 'We failed to process your deposit, Try again later.'
                                    ];
                                }
                            }
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

        if (isset($this->url[2]) && $this->url[2] == 'success') {

            if (!isset($this->url[3]) || !intval($this->url[3])|| !$userModel->hasDepositId($this->url[3])) {
                $_SESSION['message'][] = ['error', 'Failed to fetch deposit details. Please try again later.'];
                redirect('payment/failed');
            }

            if (!isset($this->url[4]) || !intval($this->url[4]) || !$userModel->hasMethod($this->url[4])) {
                $_SESSION['message'][] = ['error', 'Please choose a method and try again.'];
                redirect('user/deposit');
            }

            try {

                $data['deposit-details'] = $userModel->getDeposit($this->url[3]);

                // Retrieve the variables
                $depositId = $this->url[3];
                $amount = $data['deposit-details']['amount'];
                $method_code = $this->url[4];

                $data['payment-amount'] = $amount;
                $data['payment-method'] = $userModel->getMethod($method_code);

                // Update deposit details
                $userModel->updateDepositStatus($depositId, $data['user']['userid'], $method_code);

                // Render the success view with the data
                return ['content' => $this->view->render($data, 'user/deposits/deposit-success')];
            } catch (Exception $e) {
                // Error response if an exception occurs during deposit update
                $_SESSION['message'][] = ['error', $e->getMessage()];
                redirect('payment/failed');
            }
        }

        // unset the sessions and redirect
        $session->delete('referral_id');
        $session->delete('planId');
        $session->delete('amount');
        $session->delete("investId");
        $session->delete('access_token');

        // Default: Render deposit view
        return ['content' => $this->view->render($data, 'user/deposits/deposit')];
    }

    /**
     * payout
     */
    public function payout(): array
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];

        /* Use User Model */
        $user = $this->library('User');
        $data['user'] = $user->data();

        // Redirect if a user is not logged in or is blocked
        if (!$user->isLoggedIn()) {
            redirect('login');
        } elseif($data['user']['status'] == 2) {
            redirect('blocked');
        }

        /* Use Models */
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();

        if ($data['settings']["twofa_status"] == 1) {
            // check if twofactor check was initiated
            if ($user->data()["twofactor_flag"] == 1) {
                redirect('twofa');
            }
        }

        /* Use Input Library */
        $input = $this->library('Input');

        // Use Models
        $userModel = $this->model('User');

        $data['get-withdraw'] = $settingsModel->getWithdraws();  

        $session = $this->library('Session');

        // Check if the request method is POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Check if input exists
            if ($input->exists()) {

                $validator = $this->library('Validator');

                // Validate input data
                $validation = $validator->check($_POST, [
                    'amount' => [
                        'required' => true,
                        'float' => true
                    ],
                    'withdraw_code' => [
                        'required' => true,
                        'digit' => true
                    ]
                ]);

                if (!$validation->fails()) {
                    try {

                        // Get input data
                        $amount = $input->get('amount');
                        $withdraw_code = $input->get('withdraw_code');

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
                            if ($data['user']['interest_wallet'] == 0.00) {
                                // Empty balance warning
                                $response = [
                                    'status' => 'warning',
                                    'message' => 'You can\'t withdraw from an empty balance.'
                                ];
                            } else {
                                if ($amount > $data['user']['interest_wallet']) {
                                    // Insufficient funds warning
                                    $response = [
                                        'status' => 'warning',
                                        'message' => 'You have insufficient funds to withdraw.'
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

                                        // Insert payout details
                                        $insert = $userModel->payout($withdrawId, $data['user']['userid'], $withdraw_code, $amount, $trx);

                                        if ($insert === 1) {
                                            // Success response
                                            $response = [
                                                'status' => 'success',
                                                'redirect' => 'user/confirm/payout/' . $withdrawId. '/' .$withdraw_code
                                            ];
                                        }else{
                                            $response = [
                                                'status' => 'warning',
                                                'message' => 'We failed to process your payout, Try again later.'
                                            ];
                                        }
                                    }
                                }
                            }
                        }
                    } catch (Exception $e) {
                        // Error response if an exception occurs during a withdrawal process
                        $response = [
                            'status' => 'warning',
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

        if (isset($this->url[2]) && $this->url[2] == 'success') {

            if (!isset($this->url[3]) || !intval($this->url[3]) || !$userModel->hasWithdrawalId($this->url[3])) {
                $_SESSION['message'][] = ['error', 'Failed to fetch withdrawal details. Please try again later.'];
                redirect('payout/failed');
            }

            if (!isset($this->url[4]) || !intval($this->url[4]) || !$userModel->hasWithdrawalMethod($this->url[4])) {
                $_SESSION['message'][] = ['error', 'Please choose a withdrawal method and try again.'];
                redirect('user/payout');
            }

            try {

                $data['withdrawal-details'] = $userModel->getWithdrawal($this->url[3]);

                // Retrieve the session variables
                $withdraw_code = $this->url[4];
                $amount = $data['withdrawal-details']['amount'];
                $withdrawId = $this->url[3];

                $data['withdraw-amount'] = $amount;
                $data['withdraw-method'] = $userModel->getWithdrawMethod($withdraw_code);

                // Update payout status
                $userModel->updatePayoutStatus($withdrawId, $data['user']['userid']);

                // Render the success view with the data
                return ['content' => $this->view->render($data, 'user/payouts/payout-success')];
            }catch (Exception $e) {
                // Error response if an exception occurs during payout update
                $_SESSION['message'][] = ['error', $e->getMessage()];
                redirect('payout/failed');
            }
        }

        // unset the sessions and redirect
        $session->delete('referral_id');
        $session->delete('planId');
        $session->delete('amount');
        $session->delete("investId");
        $session->delete('access_token');

        // Default: Render deposit view
        return ['content' => $this->view->render($data, 'user/payouts/payout')];
    }

    /**
     * confirm
     */
    public function confirm(): array
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];

        /* Use User Model */
        $user = $this->library('User');
        $data['user'] = $user->data();

        // Redirect if a user is not logged in or is blocked
        if (!$user->isLoggedIn()) {
            redirect('login');
        } elseif($data['user']['status'] == 2) {
            redirect('blocked');
        }

        /* Use Models */
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();

        if ($data['settings']["twofa_status"] == 1) {
            // check if twofactor check was initiated
            if ($user->data()["twofactor_flag"] == 1) {
                redirect('twofa');
            }
        }

        /* Use Input Library */
        $input = $this->library('Input');

        // Use Models
        $userModel = $this->model('User');

        $data['get-gateway'] = $settingsModel->getGateways(); 

        // Fetch the email template with id = 7 & 9
        $data['email-templates'] = $settingsModel->getEmailTemplate();
        $depositTemplate = $data['email-templates'][7] ?? null;
        $withdrawTemplate = $data['email-templates'][9] ?? null;

        $adminDepositTemplate = $data['email-templates'][32] ?? null;
        $adminWithdrawalTemplate = $data['email-templates'][33] ?? null;

        // unset the sessions and redirect
        $session = $this->library('Session');
        $session->delete('referral_id');
        $session->delete('planId');
        $session->delete('amount');
        $session->delete("investId");
        $session->delete('access_token');

        if (isset($this->url[2]) && $this->url[2] == 'deposit') {

            if (!isset($this->url[3]) || !intval($this->url[3]) || !$userModel->hasDepositId($this->url[3])) {
                $_SESSION['message'][] = ['error', 'Failed to fetch deposit details. Please try again later.'];
                redirect('payment/failed');
            }

            if (!isset($this->url[4]) || !intval($this->url[4]) || !$userModel->hasMethod($this->url[4])) {
                $_SESSION['message'][] = ['error', 'Please choose a deposit method and try again.'];
                redirect('user/deposit');
            }

            // Update deposit timestamp
            $userModel->updateDepositTimestamps($this->url[3]);

            $data['deposit-details'] = $userModel->getDeposit($this->url[3]);

            if ($data['deposit-details']['status'] == 3) {
                $_SESSION['message'][] = ['error', 'This deposit has already been marked as rejected'];
                redirect('payment/failed');
            }

            // Retrieve the variables
            $depositId = $this->url[3];
            $amount = $data['deposit-details']['amount'];
            $method_code = $this->url[4];

            $data['payment-amount'] = $amount;
            $data['payment-method'] = $userModel->getMethod($method_code);

            // Check if the request method is POST
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $response = [];

                // Check if input exists
                if ($input->exists()) {

                    $validator = $this->library('Validator');

                    // Validate input data
                    $validation = $validator->check($_POST, [
                        'balance' => [
                            'required' => true
                        ]
                    ]);

                    if (!$validation->fails()) {
                        try {
                            $has = $userModel->hasMethod($method_code);
                            if ($data['payment-method']['need_proof'] == 1) {

                                // check the type of proof needed
                                if ($data['payment-method']['proof_type'] == "image") {
                                    // Check if payment method exists
                                    if (!$has) {
                                        // Payment method does not exist error
                                        $response = [
                                            'status' => 'warning',
                                            'message' => 'This deposit method does not exist. Please try again.'
                                        ];
                                    } else {

                                        // File formats
                                        $validFormats = ["jpg", "jpeg", "png"];

                                        $name = $_FILES['photoimg']['name'];

                                        if (!empty($name)) {
                                            $fileFormat = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                                            $size = $_FILES['photoimg']['size'];

                                            if ($_FILES['photoimg']['error'] === UPLOAD_ERR_OK) {
                                                // Check if a file format is valid
                                                if (in_array($fileFormat, $validFormats)) {
                                                    // Check if the file size is within the limit (2MB)
                                                    if ($size <= 2097152) { // 2MB in bytes
                                                        $fileName = $this->rando() . '.' . $fileFormat;

                                                        $path = sprintf('%s/../../%s/%s/deposit-proof/', __DIR__, PUBLIC_PATH, UPLOADS_PATH);

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

                                                        // Save the resized image to a file
                                                        if (imagejpeg($resized_image, $path . $fileName)) {

                                                            // Get input data
                                                            $balance = $input->get('balance');

                                                            // Update deposit details
                                                            $update = $userModel->updateDeposit($depositId, $data['user']['userid'], $balance, $fileName, $data['payment-method']['name']);

                                                            if ($update === 1) {
                                                                
                                                                // Initialize variables for email notifications
                                                                $depositEmailSent = false;
                                                                $adminEmailSent = false;

                                                                // email notification is enabled
                                                                if ($data['settings']["email_notification"] == 1) {

                                                                    $siteName = $data['settings']['sitename'];
                                                                    $siteLogo = $data['settings']['logo'];
                                                                    $siteUrl = getenv('URL_PATH');
                                                                    $dateNow = date('Y');

                                                                    // deposit template is enabled
                                                                    if ($depositTemplate !== null && $depositTemplate['status'] == 1) {

                                                                        // Replace placeholders in email body
                                                                        $depositTemplate['body'] = str_replace(
                                                                            ['{FIRSTNAME}', '{LASTNAME}', '{AMOUNT}', '{METHOD}', '{CURRENCY}', '{CRYPTO}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'],
                                                                            [$data['user']['firstname'], $data['user']['lastname'], $amount, $data['payment-method']['name'], $data['user']['currency'], $balance, $siteName, $siteLogo, $siteUrl, $dateNow],
                                                                            $depositTemplate['body']
                                                                        );

                                                                        $recipientEmail = $data['user']['email'];
                                                                        $subject = $depositTemplate['subject'];
                                                                        $body = $depositTemplate['body'];

                                                                        // Send deposit email
                                                                        $depositEmailSent = EmailHelper::sendEmail($data['settings'], $recipientEmail, $subject, $body);
                                                                    }

                                                                    // admin template is enabled
                                                                    if ($adminDepositTemplate !== null && $adminDepositTemplate['status'] == 1) {

                                                                        // Replace placeholders in admin deposit email body
                                                                        $adminDepositBody = str_replace(
                                                                            ['{FIRSTNAME}', '{LASTNAME}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'],
                                                                            [$data['user']['firstname'], $data['user']['lastname'], $siteName, $siteLogo, $siteUrl, $dateNow],
                                                                            $adminDepositTemplate['body']
                                                                        );

                                                                        $adminEmail = $data['settings']['smtp_username'];
                                                                        $adminSubject = $adminDepositTemplate['subject'];

                                                                        // Send admin email
                                                                        $adminEmailSent = EmailHelper::sendEmail($data['settings'], $adminEmail, $adminSubject, $adminDepositBody);
                                                                    }

                                                                    if ($depositEmailSent && $adminEmailSent) {
                                                                        $response = [
                                                                            'status' => 'success',
                                                                            'redirect' => 'user/deposit/success/' . $depositId. '/' .$method_code
                                                                        ];
                                                                    } else {
                                                                        $response = [
                                                                            'status' => 'error',
                                                                            'message' => 'Unfortunately, we encountered an issue while sending you a notification.',
                                                                            'redirect' => 'user/deposit/success/' . $depositId. '/' .$method_code
                                                                        ];
                                                                    }
                                                                } else {
                                                                    $response = [
                                                                        'status' => 'success',
                                                                        'redirect' => 'user/deposit/success/' . $depositId. '/' .$method_code
                                                                    ];
                                                                }
                                                            } else {
                                                                $response = [
                                                                    'status' => 'error',
                                                                    'redirect' => 'payment/failed'
                                                                ];
                                                            }
                                                        } else {
                                                            $response = [
                                                                'status' => 'warning',
                                                                'message' => 'Unable to upload the document, please try again.'
                                                            ];
                                                        }

                                                        // Free up memory
                                                        imagedestroy($image);
                                                        imagedestroy($resized_image);           
                                                    } else {
                                                        // File size exceeds the limit
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
                                                'status' => 'warning',
                                                'message' => 'No document selected, please choose a document and try again.'
                                            ];
                                        }
                                    }
                                }elseif ($data['payment-method']['proof_type'] == "text") {

                                    // Get input data
                                    $balance = $input->get('balance');

                                    if (!empty($input->get('hashID'))) {
                                        $hashID = $input->get('hashID');
                                    } else {
                                        $response = [
                                            'status' => 'warning',
                                            'message' => 'Please enter a transaction ID and try again',
                                        ];
                                        echo json_encode($response);
                                        exit;
                                    }

                                    // update deposit details
                                    $update = $userModel->updateDeposit($depositId, $data['user']['userid'], $balance, $hashID, $data['payment-method']['name']);

                                    if ($update === 1) {
                                        
                                        // email notification is enabled
                                        if ($data['settings']["email_notification"] == 1) {

                                            $siteName = $data['settings']['sitename'];
                                            $siteLogo = $data['settings']['logo'];
                                            $siteUrl = getenv('URL_PATH');
                                            $dateNow = date('Y');

                                            // deposit template is enabled
                                            if ($depositTemplate !== null && $depositTemplate['status'] == 1) {

                                                // Replace placeholders in email body
                                                $depositTemplate['body'] = str_replace(
                                                    ['{FIRSTNAME}', '{LASTNAME}', '{AMOUNT}', '{METHOD}', '{CURRENCY}', '{CRYPTO}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'],
                                                    [$data['user']['firstname'], $data['user']['lastname'], $amount, $data['payment-method']['name'], $data['user']['currency'], $balance, $siteName, $siteLogo, $siteUrl, $dateNow],
                                                    $depositTemplate['body']
                                                );

                                                $recipientEmail = $data['user']['email'];
                                                $subject = $depositTemplate['subject'];
                                                $body = $depositTemplate['body'];

                                                // Send deposit email
                                                $depositEmailSent = EmailHelper::sendEmail($data['settings'], $recipientEmail, $subject, $body);
                                            }

                                            // admin template is enabled
                                            if ($adminDepositTemplate !== null && $adminDepositTemplate['status'] == 1) {

                                                // Replace placeholders in admin deposit email body
                                                $adminDepositBody = str_replace(
                                                    ['{FIRSTNAME}', '{LASTNAME}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'],
                                                    [$data['user']['firstname'], $data['user']['lastname'], $siteName, $siteLogo, $siteUrl, $dateNow],
                                                    $adminDepositTemplate['body']
                                                );

                                                $adminEmail = $data['settings']['smtp_username'];
                                                $adminSubject = $adminDepositTemplate['subject'];

                                                // Send admin email
                                                $adminEmailSent = EmailHelper::sendEmail($data['settings'], $adminEmail, $adminSubject, $adminDepositBody);
                                            }

                                            if ($depositEmailSent && $adminEmailSent) {
                                                $response = [
                                                    'status' => 'success',
                                                    'redirect' => 'user/deposit/success/' . $depositId. '/' .$method_code
                                                ];
                                            } else {
                                                $response = [
                                                    'status' => 'error',
                                                    'message' => 'Unfortunately, we encountered an issue while sending you a notification.',
                                                    'redirect' => 'user/deposit/success/' . $depositId. '/' .$method_code
                                                ];
                                            }
                                        } else {
                                            $response = [
                                                'status' => 'success',
                                                'redirect' => 'user/deposit/success/' . $depositId. '/' .$method_code
                                            ];
                                        }
                                    } else {
                                        $response = [
                                            'status' => 'error',
                                            'redirect' => 'payment/failed'
                                        ];
                                    }
                                }
                            }else{

                                // Check if payment method exists
                                if (!$has) {
                                    // Payment method does not exist error
                                    $response = [
                                        'status' => 'warning',
                                        'message' => 'This deposit method does not exist. Please try again.'
                                    ];
                                } else {

                                    // Get input data
                                    $balance = $input->get('balance');

                                    // update deposit details
                                    $update = $userModel->updateDeposit($depositId, $data['user']['userid'], $balance, null, $data['payment-method']['name']);

                                    if ($update === 1) {

                                        // email notification is enabled
                                        if ($data['settings']["email_notification"] == 1) {

                                            $siteName = $data['settings']['sitename'];
                                            $siteLogo = $data['settings']['logo'];
                                            $siteUrl = getenv('URL_PATH');
                                            $dateNow = date('Y');

                                            // deposit template is enabled
                                            if ($depositTemplate !== null && $depositTemplate['status'] == 1) {

                                                // Replace placeholders in email body
                                                $depositTemplate['body'] = str_replace(
                                                    ['{FIRSTNAME}', '{LASTNAME}', '{AMOUNT}', '{METHOD}', '{CURRENCY}', '{CRYPTO}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'],
                                                    [$data['user']['firstname'], $data['user']['lastname'], $amount, $data['payment-method']['name'], $data['user']['currency'], $balance, $siteName, $siteLogo, $siteUrl, $dateNow],
                                                    $depositTemplate['body']
                                                );

                                                $recipientEmail = $data['user']['email'];
                                                $subject = $depositTemplate['subject'];
                                                $body = $depositTemplate['body'];

                                                // Send deposit email
                                                $depositEmailSent = EmailHelper::sendEmail($data['settings'], $recipientEmail, $subject, $body);
                                            }

                                            // admin template is enabled
                                            if ($adminDepositTemplate !== null && $adminDepositTemplate['status'] == 1) {

                                                // Replace placeholders in admin deposit email body
                                                $adminDepositBody = str_replace(
                                                    ['{FIRSTNAME}', '{LASTNAME}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'],
                                                    [$data['user']['firstname'], $data['user']['lastname'], $siteName, $siteLogo, $siteUrl, $dateNow],
                                                    $adminDepositTemplate['body']
                                                );

                                                $adminEmail = $data['settings']['smtp_username'];
                                                $adminSubject = $adminDepositTemplate['subject'];

                                                // Send admin email
                                                $adminEmailSent = EmailHelper::sendEmail($data['settings'], $adminEmail, $adminSubject, $adminDepositBody);
                                            }

                                            if ($depositEmailSent && $adminEmailSent) {
                                                $response = [
                                                    'status' => 'success',
                                                    'redirect' => 'user/deposit/success/' . $depositId. '/' .$method_code
                                                ];
                                            } else {
                                                $response = [
                                                    'status' => 'error',
                                                    'message' => 'Unfortunately, we encountered an issue while sending you a notification.',
                                                    'redirect' => 'user/deposit/success/' . $depositId. '/' .$method_code
                                                ];
                                            }
                                        } else {
                                            $response = [
                                                'status' => 'success',
                                                'redirect' => 'user/deposit/success/' . $depositId. '/' .$method_code
                                            ];
                                        }
                                    } else {
                                        $response = [
                                            'status' => 'error',
                                            'redirect' => 'payment/failed'
                                        ];
                                    }
                                }
                            }
                        } catch (Exception $e) {
                            // Error response if an exception occurs during profile update
                            $response = [
                                'status' => 'warning',
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
                            'status' => 'warning',
                            'message' => $errorMessages
                        ];
                    }

                    // Send the JSON response
                    header('Content-Type: application/json');
                    echo json_encode($response);
                    exit;
                }
            }

            // Default: Render deposit-confirm view
            return ['content' => $this->view->render($data, 'user/deposits/deposit-confirm')];
        }elseif (isset($this->url[2]) && $this->url[2] == 'payout') {

            if (!isset($this->url[3]) || !intval($this->url[3]) || !$userModel->hasWithdrawalId($this->url[3])) {
                $_SESSION['message'][] = ['error', 'Failed to fetch withdrawal details. Please try again later.'];
                redirect('payout/failed');
            }

            if (!isset($this->url[4]) || !intval($this->url[4]) || !$userModel->hasWithdrawalMethod($this->url[4])) {
                $_SESSION['message'][] = ['error', 'Please choose a withdrawal method and try again.'];
                redirect('user/payout');
            }

            $data['withdrawal-details'] = $userModel->getWithdrawal($this->url[3]);

            // Retrieve the session variables
            $withdraw_code = $this->url[4];
            $amount = $data['withdrawal-details']['amount'];
            $withdrawId = $this->url[3];

            $data['withdraw-amount'] = $amount;
            $data['withdraw-method'] = $userModel->getWithdrawMethod($withdraw_code);

            // Check if the request method is POST
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                // Check if input exists
                if ($input->exists()) {

                    $validator = $this->library('Validator');

                    // Validate input data
                    $validation = $validator->check($_POST, [
                        'balance' => [
                            'required' => true
                        ],
                        'wallet' => [
                            'required' => true
                        ]
                    ]);

                    if (!$validation->fails()) {
                        try {

                            // Get input data
                            $balance = $input->get('balance');
                            $wallet = $input->get('wallet');

                            // update payout details
                            $update = $userModel->updatePayout($withdrawId, $data['user']['userid'], $balance, $wallet, $data['withdraw-method']['name']);

                            if ($update === 1) {
                                                                
                                // Initialize variables for email notifications
                                $withdrawEmailSent = false;
                                $adminWithdrawalEmailSent = false;

                                // email notification is enabled
                                if ($data['settings']["email_notification"] == 1) {

                                    $siteName = $data['settings']['sitename'];
                                    $siteLogo = $data['settings']['logo'];
                                    $siteUrl = getenv('URL_PATH');
                                    $dateNow = date('Y');

                                    // withdraw template is enabled
                                    if ($withdrawTemplate !== null && $withdrawTemplate['status'] == 1) {

                                        // Replace placeholders in email body
                                        $withdrawTemplate['body'] = str_replace(
                                            ['{FIRSTNAME}', '{LASTNAME}', '{AMOUNT}', '{METHOD}', '{CURRENCY}', '{CRYPTO}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'],
                                            [$data['user']['firstname'], $data['user']['lastname'], $amount, $data['withdraw-method']['name'], $data['user']['currency'], $balance, $siteName, $siteLogo, $siteUrl, $dateNow],
                                            $withdrawTemplate['body']
                                        );
                                        
                                        $recipientEmail = $data['user']['email'];
                                        $subject = $withdrawTemplate['subject'];
                                        $body = $withdrawTemplate['body'];

                                        // Send deposit email
                                        $withdrawEmailSent = EmailHelper::sendEmail($data['settings'], $recipientEmail, $subject, $body);
                                    }

                                    // admin template is enabled
                                    if ($adminWithdrawalTemplate !== null && $adminWithdrawalTemplate['status'] == 1) {

                                        // Replace placeholders in admin deposit email body
                                        $adminWithdrawalBody = str_replace(
                                            ['{FIRSTNAME}', '{LASTNAME}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'],
                                            [$data['user']['firstname'], $data['user']['lastname'], $siteName, $siteLogo, $siteUrl, $dateNow],
                                            $adminWithdrawalTemplate['body']
                                        );

                                        $adminWithdrawalEmail = $data['settings']['smtp_username'];
                                        $adminWithdrawalSubject = $adminWithdrawalTemplate['subject'];

                                        // Send admin email
                                        $adminWithdrawalEmailSent = EmailHelper::sendEmail($data['settings'], $adminWithdrawalEmail, $adminWithdrawalSubject, $adminWithdrawalBody);
                                    }

                                    if ($withdrawEmailSent && $adminWithdrawalEmailSent) {
                                        $response = [
                                            'status' => 'success',
                                            'redirect' => 'user/payout/success/' . $withdrawId. '/' .$withdraw_code
                                        ];
                                    } else {
                                        $response = [
                                            'status' => 'error',
                                            'message' => 'Unfortunately, we encountered an issue while sending you a notification.',
                                            'redirect' => 'user/payout/success/' . $withdrawId. '/' .$withdraw_code
                                        ];
                                    }
                                } else {
                                    $response = [
                                        'status' => 'success',
                                        'redirect' => 'user/payout/success/' . $withdrawId. '/' .$withdraw_code
                                    ];
                                }
                            } else {
                                $response = [
                                    'status' => 'error',
                                    'redirect' => 'payment/failed'
                                ];
                            }
                            
                        } catch (Exception $e) {
                            // Error response if an exception occurs during profile update
                            $response = [
                                'status' => 'warning',
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
                            'status' => 'warning',
                            'message' => $errorMessages
                        ];
                    }

                    // Set response headers and output JSON response
                    header('Content-Type: application/json');
                    echo json_encode($response);
                    exit;
                }
            }

            // Render the success view with the data
            return ['content' => $this->view->render($data, 'user/payouts/payout-confirm')];
        } else {
            // redirect to the user dashboard page
            redirect('user/dashboard');
        }

        // return an empty array
        return [];
    }

    /**
     * plans
     */
    public function plans()
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];

        // Load User model and retrieve user data
        $user = $this->library('User');
        $data['user'] = $user->data();

        // Redirect if a user is not logged in or is blocked
        if (!$user->isLoggedIn()) {
            redirect('login');
        } elseif($data['user']['status'] == 2) {
            redirect('blocked');
        }

        /* Use Models */
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();

        if ($data['settings']["twofa_status"] == 1) {
            // check if twofactor check was initiated
            if ($user->data()["twofactor_flag"] == 1) {
                redirect('twofa');
            }
        }

        // Retrieve gateway data
        $data['get-gateway'] = $settingsModel->getGateways(); 

        /* Use Input Library */
        $input = $this->library('Input');

        // Use Models
        $userModel = $this->model('User');

        // Retrieve plans and time settings
        $data['plans'] = $userModel->plans();
        $data['times'] = $userModel->times();

        // get the referral settings
        $data['referral-settings'] = $userModel->referralSettings();

        $session = $this->library('Session');

        // Process form submission if method is POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $validator = $this->library('Validator');
            $validation = $validator->check($_POST, [
                'planId' => [
                    'required' => true,
                    'digit' => true
                ]
            ]);

            // If validation fails, return error response
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

                // Validate and process plan ID
                $planId = $input->get('planId');

                if (empty($planId)) {
                    $response = [
                        'status' => 'error',
                        'message' => 'Failed to process investment. Please try again later.'
                    ];
                } else {

                    // Check if the plan exists
                    $hasPlan = $userModel->hasPlanId($planId);

                    if (!$hasPlan) {
                        $response = [
                            'status' => 'error',
                            'message' => 'Failed to process investment. Please try again later.'
                        ];
                    } else {
                        $response = [
                            'status' => 'success',
                            'redirect' => 'user/schemes/invest/' . $planId
                        ];
                    }
                }
            }

            // Send JSON response and exit
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }

        if (isset($this->url[2]) && $this->url[2] == 'invested') {

            if (!isset($_SESSION['planId'])) {
                // Error response if session data not set
                redirect('investment/failed');
            } else {
                try {
                    // Retrieve the session variables
                    $planId = $session->get("planId");
                    $investId = $session->get("investId");
                    $amount = $session->get("amount");

                    // check if the plan has a valid planId
                    $planIdExists = $userModel->hasPlanId($planId);

                    // if planId isn't valid throw error
                    if (!$planIdExists) {
                        redirect('investment/failed');
                    } else {

                        // Check if the investment exists
                        $hasInvestment = $userModel->hasInvestment($investId);

                        // if investId isn't valid throw error
                        if ($hasInvestment) {

                            $data['invested-amount'] = $amount;
                            $data['plan-details'] = $userModel->planDetails($planId);
                            $data['investment-details'] = $userModel->investmentDetails($investId);
                            $data['interest'] = $amount + $data['investment-details']['interest'];

                            // Render the success view with the data
                            return ['content' => $this->view->render($data, 'user/investments/investment-success')];
                        }else{
                            redirect('investment/failed');
                        }
                    }
                } catch (Exception $e) {
                    // Error response if an exception occurs during deposit update
                    $_SESSION['message'][] = ['error', $e->getMessage()];
                    redirect('investment/failed');
                }
            }
        }

        // unset the sessions and redirect
        $session->delete('referral_id');
        $session->delete('planId');
        $session->delete('amount');
        $session->delete("investId");
        $session->delete('access_token');

        // Render the plans view with the retrieved data
        return ['content' => $this->view->render($data, 'user/plans')];
    }

    /**
     * schemes
     */
    public function schemes(): array
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];

        // Load User model and retrieve user data
        $user = $this->library('User');
        $data['user'] = $user->data();

        // Redirect if a user is not logged in or is blocked
        if (!$user->isLoggedIn()) {
            redirect('login');
        } elseif($data['user']['status'] == 2) {
            redirect('blocked');
        }

        /* Use Models */
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();

        if ($data['settings']["twofa_status"] == 1) {
            // check if twofactor check was initiated
            if ($user->data()["twofactor_flag"] == 1) {
                redirect('twofa');
            }
        }

        // Retrieve gateway data
        $data['get-gateway'] = $settingsModel->getGateways(); 

        /* Use Input Library */
        $input = $this->library('Input');

        // Use Models
        $userModel = $this->model('User');

        /* Use Referral Model */
        $referralModel = $this->model('Referral');

        // Fetch the email template with id = 11 & 12
        $data['email-templates'] = $settingsModel->getEmailTemplate();
        $referralTemplate = $data['email-templates'][11] ?? null;
        $investmentTemplate = $data['email-templates'][12] ?? null;

        $data['times'] = $userModel->times();

        $data['ranks'] = $referralModel->getRanks();
        $data['count-invests'] = $referralModel->countInvestments($data['user']['userid']);
        $data['count-referrals'] = $referralModel->countReferrals($data['user']['userid']);

        // Determine next rank id
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

        $session = $this->library('Session');

        if (isset($this->url[2]) && $this->url[2] == 'invest') {

            if (!isset($this->url[3]) || !intval($this->url[3])|| !$userModel->hasPlanId($this->url[3])) {
                $_SESSION['message'][] = ['error', 'Please choose a plan and try again.'];
                redirect('user/plans');
            }

            // set the planId variables
            $planId = $this->url[3];

            // get the plan details using the planId
            $data['plan-details'] = $userModel->planDetails($planId);

            $response = []; // Initialize the $response variable

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $validator = $this->library('Validator');
                $validation = $validator->check($_POST, [
                    'custom-amount' => [
                        'required' => true, 
                        'float' => true
                    ],
                    'method' => [
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
                        'status' => 'warning',
                        'message' => $errorMessages
                    ];
                }else{
                    try {

                        $depositId = $this->uniqueid();
                        $investId = $this->investmentId();

                        $amount = $input->get('custom-amount');
                        $method = $input->get('method');
                        $hours = $data['plan-details']['times'];

                        $session->putMultiple([
                            "planId" => $planId,
                            "amount" => $amount,
                            "investId" => $investId,
                        ]);

                        // validate the payment methods
                        $allowedMethods = ['deposit', 'interest_wallet'];

                        if (!in_array($method, $allowedMethods)) {
                            $response = [
                                'status' => 'info',
                                'redirect' => 'investment/failed'
                            ];
                            // Send the JSON response
                            $this->sendJsonResponse($response);
                            exit;
                        }

                        // check if the plan doesn't have a fixed amount
                        if ($data['plan-details']['fixed_amount'] == 0) {
                            if ($amount < $data['plan-details']['minimum']) {
                                $response = [
                                    'status' => 'warning',
                                    'message' => 'Please follow the minimum investment limit, and try again.'
                                ];
                                // Send the JSON response
                                $this->sendJsonResponse($response);
                                exit;
                            }elseif ($amount > $data['plan-details']['maximum']) {
                                $response = [
                                    'status' => 'warning',
                                    'message' => 'Please follow the maximum investment limit, and try again.'
                                ];
                                // Send the JSON response
                                $this->sendJsonResponse($response);
                                exit;
                            }
                            // Check the method type
                            if ($method == "deposit") {
                                // Initiating deposit transaction
                                $insert = $userModel->initiate($depositId, $planId, $data['user']['userid'], $amount, $method, "+");

                                if ($insert == 1) {
                                    $response = [
                                        'status' => 'success',
                                        'redirect' => 'user/checkout/deposit/' . $depositId
                                    ];
                                } else {
                                    $response = [
                                        'status' => 'info',
                                        'redirect' => 'investment/failed'
                                    ];
                                    // Send the JSON response
                                    $this->sendJsonResponse($response);
                                    exit;
                                }
                            }elseif ($method == "interest_wallet") {

                                //check if the amount entered is greater than the user's balance
                                if ($data['user']['interest_wallet'] == 0.00) {
                                    $response = [
                                        'status' => 'warning',
                                        'message' => 'You can\'t invest from an empty balance, please deposit.'
                                    ];
                                } else {

                                    //check if the amount entered is greater than the user's balance
                                    if ($amount > $data['user']['interest_wallet']) {
                                        $response = [
                                            'status' => 'warning',
                                            'message' => 'You have an insufficient balance, please deposit.'
                                        ];
                                    } else {
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
                                                    $referrerFirstName = $data['referrer']['firstname'];
                                                    $referrerLastName = $data['referrer']['lastname'];

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

                                                        // check if the user ranking is enabled
                                                        if ($data['settings']["user_ranking"] == 1) {
                                                            // Determine next rank id
                                                            $userModel->updateRank($data['user']['userid'], $data['bonus'], $data['user_ranking_id']);
                                                        }

                                                        // Initialize variables for email notifications
                                                        $referralEmailSent = false;
                                                        $investmentEmailSent = false;

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
                                                                $referralEmailSent = EmailHelper::sendEmail($data['settings'], $referralEmail, $referralSubject, $referralBody);
                                                            }

                                                            // investment template is enabled
                                                            if ($investmentTemplate !== null && $investmentTemplate['status'] == 1) {

                                                                // Replace placeholders in investment email body
                                                                $investmentBody = str_replace(
                                                                    ['{FIRSTNAME}', '{LASTNAME}', '{AMOUNT}', '{INTEREST}', '{CURRENCY}', '{PLAN}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'],
                                                                    [$data['user']['firstname'], $data['user']['lastname'], $amount, $interest_amount, $data['user']['currency'], $data['plan-details']['name'], $siteName, $siteLogo, $siteUrl, $dateNow],
                                                                    $investmentTemplate['body']
                                                                );

                                                                $recipientEmail = $data['user']['email'];
                                                                $investmentSubject = $investmentTemplate['subject'];

                                                                // Send plan purchase email
                                                                $investmentEmailSent = EmailHelper::sendEmail($data['settings'], $recipientEmail, $investmentSubject, $investmentBody);
                                                            }

                                                            if ($referralEmailSent && $investmentEmailSent) {
                                                                $response = [
                                                                    'status' => 'success',
                                                                    'redirect' => 'user/plans/invested'
                                                                ];
                                                            } else {
                                                                $response = [
                                                                    'status' => 'error',
                                                                    'message' => 'Failed to send notification emails.',
                                                                    'redirect' => 'user/plans/invested'
                                                                ];
                                                            }
                                                        } else {
                                                            $response = [
                                                                'status' => 'success',
                                                                'redirect' => 'user/plans/invested'
                                                            ];
                                                        }
                                                    } else {
                                                        $response = [
                                                            'status' => 'info',
                                                            'redirect' => 'investment/failed'
                                                        ];
                                                        // Send the JSON response
                                                        $this->sendJsonResponse($response);
                                                        exit;
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
                        }else{
                            // if the plan has a fixed amount, and it isn't equal to the amount passed throw warning
                            if ($amount != $data['plan-details']['fixed_amount']) {
                                $response = [
                                    'status' => 'warning',
                                    'message' => 'Please follow the fixed investment limit, and try again.'
                                ];

                                // Send the JSON response
                                $this->sendJsonResponse($response);
                                exit;
                            }else{
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

                                            // add to a database
                                            $insert = $userModel->planPurchase($investId, $data['user']['userid'], $planId, $amount_new, $interest_amount, $repeat_time, $hours, $amount, $method, $details, $from_id, $to_id, $referralAmount, $referralPercentage, $new_balance, $title, $trx_type, $capital_back_status);

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
                                                        $referralEmailSent = EmailHelper::sendEmail($data['settings'], $referralEmail, $referralSubject, $referralBody);
                                                    }

                                                    // investment template is enabled
                                                    if ($investmentTemplate !== null && $investmentTemplate['status'] == 1) {

                                                        // Replace placeholders in investment email body
                                                        $investmentBody = str_replace(
                                                            ['{FIRSTNAME}', '{LASTNAME}', '{AMOUNT}', '{INTEREST}', '{CURRENCY}', '{PLAN}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'],
                                                            [$data['user']['firstname'], $data['user']['lastname'], $amount, $interest_amount, $data['user']['currency'], $data['plan-details']['name'], $siteName, $siteLogo, $siteUrl, $dateNow],
                                                            $investmentTemplate['body']
                                                        );

                                                        $recipientEmail = $data['user']['email'];
                                                        $investmentSubject = $investmentTemplate['subject'];

                                                        // Send plan purchase email
                                                        $investmentEmailSent = EmailHelper::sendEmail($data['settings'], $recipientEmail, $investmentSubject, $investmentBody);
                                                    }

                                                    if ($referralEmailSent && $investmentEmailSent) {
                                                        $response = [
                                                            'status' => 'success',
                                                            'redirect' => 'user/plans/invested'
                                                        ];
                                                    } else {
                                                        $response = [
                                                            'status' => 'error',
                                                            'message' => 'Failed to send notification emails.',
                                                            'redirect' => 'user/plans/invested'
                                                        ];
                                                    }
                                                } else {
                                                    $response = [
                                                        'status' => 'success',
                                                        'redirect' => 'user/plans/invested'
                                                    ];
                                                }
                                            } else {
                                                $response = [
                                                    'status' => 'info',
                                                    'redirect' => 'investment/failed'
                                                ];
                                                // Send the JSON response
                                                $this->sendJsonResponse($response);
                                                exit;
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
                    }catch (Exception $e) {
                        // Error response if an exception occurs
                        $response = [
                            'status' => 'error',
                            'message' => $e->getMessage()
                        ];
                    }
                }

                // Send the JSON response
                $this->sendJsonResponse($response);
                exit;
            }

            return ['content' => $this->view->render($data, 'user/schemes')];
        }else{
            $_SESSION['message'][] = ['error', 'Please choose a plan and try again.'];
            redirect('user/plans');
        }

        // return an empty array
        return [];
    }

    /**
     * checkout
     */
    public function checkout(): array
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];

        // Load User model and retrieve user data
        $user = $this->library('User');
        $data['user'] = $user->data();

        // Redirect if a user is not logged in or is blocked
        if (!$user->isLoggedIn()) {
            redirect('login');
        } elseif($data['user']['status'] == 2) {
            redirect('blocked');
        }

        /* Use Models */
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();

        if ($data['settings']["twofa_status"] == 1) {
            // check if twofactor check was initiated
            if ($user->data()["twofactor_flag"] == 1) {
                redirect('twofa');
            }
        }

        /* Use Input Library */
        $input = $this->library('Input');

        /* Use User Model */
        $userModel = $this->model('User');

        $data['get-gateway'] = $settingsModel->getGateways();

        if (isset($this->url[2]) && $this->url[2] == 'deposit') {

            if (!isset($this->url[3]) || !intval($this->url[3])|| !$userModel->hasDepositId($this->url[3])) {
                $_SESSION['message'][] = ['error', 'Failed to fetch deposit details. Please try again later.'];
                redirect('user/plans');
            }

            // set the variables
            $depositId = $this->url[3];

            $data['deposit-details'] = $userModel->getDepositAmount($depositId);

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $validator = $this->library('Validator');
                $validation = $validator->check($_POST, [
                    'method_code' => [
                        'required' => true,
                        'digit' => true
                    ]
                ]);

                // If validation fails, return error response
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

                    // Validate and process plan ID
                    $method_code = $input->get('method_code');

                    if (empty($method_code)) {
                        $response = [
                            'status' => 'error',
                            'message' => 'Please choose a deposit method and try again.',
                        ];
                    } else {

                        // Check if the method exists
                        $has_method = $userModel->hasMethod($method_code);

                        if (!$has_method) {
                            $response = [
                                'status' => 'error',
                                'message' => 'Please choose a deposit method and try again.'
                            ];
                        } else {

                            $userModel->addMethod($depositId, $method_code);

                            $response = [
                                'status' => 'success',
                                'redirect' => 'user/complete/deposit/' . $depositId . '/' . $data['deposit-details']['planId'] . '/' . $method_code
                            ];
                        }
                    }
                }

                // Send the JSON response
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }

            return ['content' => $this->view->render($data, 'user/checkout')];
        }else{
            $_SESSION['message'][] = ['error', 'Please choose a plan and try again.'];
            redirect('user/plans');
        }

        // return an empty array
        return [];
    }

    /**
     * complete
     */
    public function complete(): array
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];

        // Load User model and retrieve user data
        $user = $this->library('User');
        $data['user'] = $user->data();

        // Redirect if a user is not logged in or is blocked
        if (!$user->isLoggedIn()) {
            redirect('login');
        } elseif($data['user']['status'] == 2) {
            redirect('blocked');
        }

        /* Use Models */
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();

        if ($data['settings']["twofa_status"] == 1) {
            // check if twofactor check was initiated
            if ($user->data()["twofactor_flag"] == 1) {
                redirect('twofa');
            }
        }

        /* Use Input Library */
        $input = $this->library('Input');

        /* Use User Model */
        $userModel = $this->model('User');

        // Fetch the email template with id = 7
        $data['email-templates'] = $settingsModel->getEmailTemplate();
        $depositTemplate = $data['email-templates'][7] ?? null;

        if (isset($this->url[2]) && $this->url[2] == 'deposit') {

            if (!isset($this->url[3]) || !intval($this->url[3]) || !$userModel->hasDepositId($this->url[3])) {
                $_SESSION['message'][] = ['error', 'Failed to fetch deposit details. Please try again later.'];
                redirect('user/plans');
            }

            if (!isset($this->url[4]) || !intval($this->url[4]) || !$userModel->hasPlanId($this->url[4])) {
                $_SESSION['message'][] = ['error', 'Failed to fetch plan details. Please try again later.'];
                redirect('user/plans');
            }

            if (!isset($this->url[5]) || !intval($this->url[5]) || !$userModel->hasMethod($this->url[5])) {
                $_SESSION['message'][] = ['error', 'Please choose a method and try again.'];
                redirect('user/plans');
            }

            $data['deposit'] = $userModel->getDepositAmount($this->url[3]);

            // Retrieve the session variables
            $method_code = $this->url[5];
            $depositId = $this->url[3];
            $planId = $this->url[4];
            $amount = $data['deposit']['amount'];

            $data['payment-amount'] = $data['deposit']['amount'];
            $data['payment-method'] = $userModel->depositDetails($method_code);
            $data['deposit-details'] = $userModel->getDeposit($depositId);

            // get the plan details using the id
            $data['plan-details'] = $userModel->planDetails($planId);

            $hours = $data['plan-details']['times'];

            // Check if the request method is POST
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                $response = [];

                // Check if input exists
                if ($input->exists()) {

                    $validator = $this->library('Validator');

                    // Validate input data
                    $validation = $validator->check($_POST, [
                        'balance' => [
                            'required' => true
                        ]
                    ]);

                    if (!$validation->fails()) {
                        try {
                            if ($data['payment-method']['need_proof'] == 1) {

                                // check the type of proof needed
                                if ($data['payment-method']['proof_type'] == "image") {

                                    // File formats
                                    $validFormats = ["jpg", "jpeg", "png"];

                                    $name = $_FILES['photoimg']['name'];

                                    if (!empty($name)) {
                                        $fileFormat = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                                        $size = $_FILES['photoimg']['size'];

                                        if ($_FILES['photoimg']['error'] === UPLOAD_ERR_OK) {
                                            // Check if a file format is valid
                                            if (in_array($fileFormat, $validFormats)) {
                                                // Check if the file size is within the limit (2MB)
                                                if ($size <= 2097152) { // 2MB in bytes
                                                    $fileName = $this->rando() . '.' . $fileFormat;

                                                    $path = sprintf('%s/../../%s/%s/deposit-proof/', __DIR__, PUBLIC_PATH, UPLOADS_PATH);

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

                                                    if (imagejpeg($resized_image, $path . $fileName)) {
                                                        // Get input data
                                                        $balance = $input->get('balance');

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
                                                        
                                                        $update = $userModel->planPurchaseDeposit($depositId, $data['user']['userid'], $balance, $fileName, $data['payment-method']['name'], $planId, $amount, $interest_amount, $repeat_time, $hours, $capital_back_status);

                                                        if ($update === 1) {
                                                            // email notification is enabled
                                                            if ($data['settings']["email_notification"] == 1) {

                                                                $siteName = $data['settings']['sitename'];
                                                                $siteLogo = $data['settings']['logo'];
                                                                $siteUrl = getenv('URL_PATH');
                                                                $dateNow = date('Y');

                                                                // deposit template is enabled
                                                                if ($depositTemplate !== null && $depositTemplate['status'] == 1) {

                                                                    // Replace placeholders in email body
                                                                    $depositTemplate['body'] = str_replace(
                                                                        ['{FIRSTNAME}', '{LASTNAME}', '{AMOUNT}', '{METHOD}', '{CURRENCY}', '{CRYPTO}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'],
                                                                        [$data['user']['firstname'], $data['user']['lastname'], $amount, $data['payment-method']['name'], $data['user']['currency'], $balance, $siteName, $siteLogo, $siteUrl, $dateNow],
                                                                        $depositTemplate['body']
                                                                    );
                                                                    
                                                                    $recipientEmail = $data['user']['email'];
                                                                    $subject = $depositTemplate['subject'];
                                                                    $body = $depositTemplate['body'];

                                                                    // Send deposit email
                                                                    if (EmailHelper::sendEmail($data['settings'], $recipientEmail, $subject, $body)) {
                                                                        $response = [
                                                                            'status' => 'success',
                                                                            'redirect' => 'user/deposit/success/' . $depositId. '/' .$method_code
                                                                        ];
                                                                    } else {
                                                                        $response = [
                                                                            'status' => 'error',
                                                                            'message' => 'Unfortunately, we encountered an issue while sending you a notification.',
                                                                            'redirect' => 'user/deposit/success/' . $depositId. '/' .$method_code
                                                                        ];
                                                                    }
                                                                }else{
                                                                    $response = [
                                                                        'status' => 'success',
                                                                        'redirect' => 'user/deposit/success/' . $depositId. '/' .$method_code
                                                                    ];
                                                                }
                                                            }else{
                                                                $response = [
                                                                    'status' => 'success',
                                                                    'redirect' => 'user/deposit/success/' . $depositId. '/' .$method_code
                                                                ];   
                                                            }
                                                        } else {
                                                            $response = [
                                                                'status' => 'error',
                                                                'redirect' => 'payment/failed'
                                                            ];
                                                        }
                                                    } else {
                                                        $response = [
                                                            'status' => 'warning',
                                                            'message' => 'Unable to upload the document, please try again.'
                                                        ];
                                                    }

                                                    // Free up memory
                                                    imagedestroy($image);
                                                    imagedestroy($resized_image);
                                                } else {
                                                    // File size exceeds the limit
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
                                            'status' => 'warning',
                                            'message' => 'No document selected, please choose a document and try again.'
                                        ];
                                    }
                                }elseif ($data['payment-method']['proof_type'] == "text") {
                                    // Get input data
                                    $balance = $input->get('balance');

                                    if (!empty($input->get('hashID'))) {
                                        $hashID = $input->get('hashID');
                                    } else {
                                        $response = [
                                            'status' => 'warning',
                                            'message' => 'Please enter a transaction ID and try again',
                                        ];
                                        echo json_encode($response);
                                        exit;
                                    }

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

                                    $update = $userModel->planPurchaseDeposit($depositId, $data['user']['userid'], $balance, $hashID, $data['payment-method']['name'], $planId, $amount, $interest_amount, $repeat_time, $hours, $capital_back_status);

                                    if ($update === 1) {

                                        // email notification is enabled
                                        if ($data['settings']["email_notification"] == 1) {

                                            $siteName = $data['settings']['sitename'];
                                            $siteLogo = $data['settings']['logo'];
                                            $siteUrl = getenv('URL_PATH');
                                            $dateNow = date('Y');

                                            // deposit template is enabled
                                            if ($depositTemplate !== null && $depositTemplate['status'] == 1) {

                                                // Replace placeholders in email body
                                                $depositTemplate['body'] = str_replace(
                                                    ['{FIRSTNAME}', '{LASTNAME}', '{AMOUNT}', '{METHOD}', '{CURRENCY}', '{CRYPTO}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'],
                                                    [$data['user']['firstname'], $data['user']['lastname'], $amount, $data['payment-method']['name'], $data['user']['currency'], $balance, $siteName, $siteLogo, $siteUrl, $dateNow],
                                                    $depositTemplate['body']
                                                );
                                                
                                                $recipientEmail = $data['user']['email'];
                                                $subject = $depositTemplate['subject'];
                                                $body = $depositTemplate['body'];

                                                // Send deposit email
                                                if (EmailHelper::sendEmail($data['settings'], $recipientEmail, $subject, $body)) {
                                                    $response = [
                                                        'status' => 'success',
                                                        'redirect' => 'user/deposit/success/' . $depositId. '/' .$method_code
                                                    ];
                                                } else {
                                                    $response = [
                                                        'status' => 'error',
                                                        'message' => 'Unfortunately, we encountered an issue while sending you a notification.',
                                                        'redirect' => 'user/deposit/success/' . $depositId. '/' .$method_code
                                                    ];
                                                }
                                            }else{
                                                $response = [
                                                    'status' => 'success',
                                                    'redirect' => 'user/deposit/success/' . $depositId. '/' .$method_code
                                                ];
                                            }
                                        }else{
                                            $response = [
                                                'status' => 'success',
                                                'redirect' => 'user/deposit/success/' . $depositId. '/' .$method_code
                                            ];   
                                        }
                                    } else {
                                        $response = [
                                            'status' => 'error',
                                            'redirect' => 'payment/failed'
                                        ];
                                    }
                                }
                            }else{
                                // Get input data
                                $balance = $input->get('balance');

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

                                $update = $userModel->planPurchaseDeposit($depositId, $data['user']['userid'], $balance, null, $data['payment-method']['name'], $planId, $amount, $interest_amount, $repeat_time, $hours, $capital_back_status);

                                if ($update === 1) {

                                    // email notification is enabled
                                    if ($data['settings']["email_notification"] == 1) {

                                        $siteName = $data['settings']['sitename'];
                                        $siteLogo = $data['settings']['logo'];
                                        $siteUrl = getenv('URL_PATH');
                                        $dateNow = date('Y');

                                        // deposit template is enabled
                                        if ($depositTemplate !== null && $depositTemplate['status'] == 1) {

                                            // Replace placeholders in email body
                                            $depositTemplate['body'] = str_replace(
                                                ['{FIRSTNAME}', '{LASTNAME}', '{AMOUNT}', '{METHOD}', '{CURRENCY}', '{CRYPTO}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'],
                                                [$data['user']['firstname'], $data['user']['lastname'], $amount, $data['payment-method']['name'], $data['user']['currency'], $balance, $siteName, $siteLogo, $siteUrl, $dateNow],
                                                $depositTemplate['body']
                                            );
                                            
                                            $recipientEmail = $data['user']['email'];
                                            $subject = $depositTemplate['subject'];
                                            $body = $depositTemplate['body'];

                                            // Send deposit email
                                            if (EmailHelper::sendEmail($data['settings'], $recipientEmail, $subject, $body)) {
                                                $response = [
                                                    'status' => 'success',
                                                    'redirect' => 'user/deposit/success/' . $depositId. '/' .$method_code
                                                ];
                                            } else {
                                                $response = [
                                                    'status' => 'error',
                                                    'message' => 'Unfortunately, we encountered an issue while sending you a notification.',
                                                    'redirect' => 'user/deposit/success/' . $depositId. '/' .$method_code
                                                ];
                                            }
                                        }else{
                                            $response = [
                                                'status' => 'success',
                                                'redirect' => 'user/deposit/success/' . $depositId. '/' .$method_code
                                            ];
                                        }
                                    }else{
                                        $response = [
                                            'status' => 'success',
                                            'redirect' => 'user/deposit/success/' . $depositId. '/' .$method_code
                                        ];   
                                    }
                                } else {
                                    $response = [
                                        'status' => 'error',
                                        'redirect' => 'payment/failed'
                                    ];
                                }
                            }
                        } catch (Exception $e) {
                            // Error response if an exception occurs during profile update
                            $response = [
                                'status' => 'warning',
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
                            'status' => 'warning',
                            'message' => $errorMessages
                        ];
                    }

                    // Send the JSON response
                    header('Content-Type: application/json');
                    echo json_encode($response);
                    exit;
                }
            }

            return ['content' => $this->view->render($data, 'user/complete')];
        } else {
            $_SESSION['message'][] = ['error', 'Please choose a plan and try again.'];
            redirect('user/plans');
        }

        // return an empty array
        return [];
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

        /* Use User Model */
        $user = $this->library('User');
        $data['user'] = $user->data();

        // Redirect if a user is not logged in or is blocked
        if (!$user->isLoggedIn()) {
            redirect('login');
        } elseif($data['user']['status'] == 2) {
            redirect('blocked');
        }

        /* Use Models */
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();

        if ($data['settings']["twofa_status"] == 1) {
            // check if twofactor check was initiated
            if ($user->data()["twofactor_flag"] == 1) {
                redirect('twofa');
            }
        }

        /* Use User Model */
        $userModel = $this->model('User');

        // Retrieve plans and time settings
        $data['plans'] = $userModel->plans();
        $data['times'] = $userModel->times();

        // unset the sessions and redirect
        $session = $this->library('Session');
        $session->delete('referral_id');
        $session->delete('planId');
        $session->delete('amount');
        $session->delete("investId");
        $session->delete('access_token');

        /* Use Input Library */
        $input = $this->library('Input');

        if ($input->isAjax() && $input->get('page')) {
            $page = $input->get('page');
            $investments = $userModel->investments_limits($data['user']['userid'], $page);

            // Return the JSON response
            header('Content-Type: application/json');
            echo json_encode(['investments' => $investments]);
            exit();
        } else {

            $data['get-investments'] = $userModel->getInvests($data['user']['userid']);
        }

        if (isset($this->url[2]) && $this->url[2] == 'investment-details') {

            if (!isset($this->url[3]) || !intval($this->url[3])|| !$userModel->hasInvestment($this->url[3])) {
                $_SESSION['message'][] = ['error', 'Failed to fetch investment details. Please try again later.'];
                redirect('user/investments');
            }

            $data['investment-details'] = $userModel->investmentDetails($this->url[3]);

            $data['receivable -amount'] = $data['investment-details']['interest'] + $data['investment-details']['amount'];

            // If the investment exists, return the content
            return ['content' => $this->view->render($data, 'user/investments/investment-details')];
        }elseif (isset($this->url[2]) && $this->url[2] == 'completed') {
            $data['get-investments'] = $userModel->getInvestsCompleted($data['user']['userid']);
            // If the investment exists, return the content
            return ['content' => $this->view->render($data, 'user/investments/investment-completed')];
        }elseif (isset($this->url[2]) && $this->url[2] == 'pending') {
            $data['get-investments'] = $userModel->getInvestsPending($data['user']['userid']);
            // If the investment exists, return the content
            return ['content' => $this->view->render($data, 'user/investments/investment-pending')];
        }elseif (isset($this->url[2]) && $this->url[2] == 'initiated') {
            $data['get-investments'] = $userModel->getInvestsInitiated($data['user']['userid']);
            // If the investment exists, return the content
            return ['content' => $this->view->render($data, 'user/investments/investment-initiated')];
        }elseif (isset($this->url[2]) && $this->url[2] == 'cancelled') {
            $data['get-investments'] = $userModel->getInvestsCancelled($data['user']['userid']);
            // If the investment exists, return the content
            return ['content' => $this->view->render($data, 'user/investments/investment-cancelled')];
        }

        // Default: Render investments view
        return ['content' => $this->view->render($data, 'user/investments/investment-history')];
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

        /* Use User Model */
        $user = $this->library('User');
        $data['user'] = $user->data();

        // Redirect if a user is not logged in or is blocked
        if (!$user->isLoggedIn()) {
            redirect('login');
        } elseif($data['user']['status'] == 2) {
            redirect('blocked');
        }

        /* Use Models */
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();

        if ($data['settings']["twofa_status"] == 1) {
            // check if twofactor check was initiated
            if ($user->data()["twofactor_flag"] == 1) {
                redirect('twofa');
            }
        }

        /* Use Deposit Model */
        $depositModel = $this->model('Deposit');

        $data['gateways'] = $settingsModel->getGateways(); 

        // unset the sessions and redirect
        $session = $this->library('Session');
        $session->delete('referral_id');
        $session->delete('planId');
        $session->delete('amount');
        $session->delete("investId");
        $session->delete('access_token');

        /* Use Input Library */
        $input = $this->library('Input');

        if ($input->isAjax() && $input->get('page')) {
            $page = $input->get('page');
            $deposits = $depositModel->deposits_limits($data['user']['userid'], $page);

            // Return the JSON response
            header('Content-Type: application/json');
            echo json_encode(['deposits' => $deposits]);
            exit();
        } else {

            $data['get-deposits'] = $depositModel->getDeposits($data['user']['userid']);
        }

        if (isset($this->url[2]) && $this->url[2] == 'completed') {
            $data['get-deposits'] = $depositModel->getDepositsCompleted($data['user']['userid']);
            // If the deposit exists, return the content
            return ['content' => $this->view->render($data, 'user/deposits/deposit-completed')];
        }elseif (isset($this->url[2]) && $this->url[2] == 'pending') {
            $data['get-deposits'] = $depositModel->getDepositsPending($data['user']['userid']);
            // If the deposit exists, return the content
            return ['content' => $this->view->render($data, 'user/deposits/deposit-pending')];
        }elseif (isset($this->url[2]) && $this->url[2] == 'initiated') {
            $data['get-deposits'] = $depositModel->getDepositsInitiated($data['user']['userid']);
            // If the deposit exists, return the content
            return ['content' => $this->view->render($data, 'user/deposits/deposit-initiated')];
        }elseif (isset($this->url[2]) && $this->url[2] == 'cancelled') {
            $data['get-deposits'] = $depositModel->getDepositsCancelled($data['user']['userid']);
            // If the deposit exists, return the content
            return ['content' => $this->view->render($data, 'user/deposits/deposit-cancelled')];
        }

        // Default: Render investments view
        return ['content' => $this->view->render($data, 'user/deposits/deposit-history')];
    }

    /**
     * payouts
     */
    public function payouts(): array
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];

        /* Use User Model */
        $user = $this->library('User');
        $data['user'] = $user->data();

        // Redirect if a user is not logged in or is blocked
        if (!$user->isLoggedIn()) {
            redirect('login');
        } elseif($data['user']['status'] == 2) {
            redirect('blocked');
        }

        /* Use Models */
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();

        if ($data['settings']["twofa_status"] == 1) {
            // check if twofactor check was initiated
            if ($user->data()["twofactor_flag"] == 1) {
                redirect('twofa');
            }
        }

        /* Use Withdrawal Model */
        $withdrawalModel = $this->model('Withdrawal');

        $data['gateways'] = $settingsModel->getWithdraws(); 

        // unset the sessions and redirect
        $session = $this->library('Session');
        $session->delete('referral_id');
        $session->delete('planId');
        $session->delete('amount');
        $session->delete("investId");
        $session->delete('access_token');

        /* Use Input Library */
        $input = $this->library('Input');

        if ($input->isAjax() && $input->get('page')) {
            $page = $input->get('page');
            $withdrawals = $withdrawalModel->withdrawals_limits($data['user']['userid'], $page);

            // Return the JSON response
            header('Content-Type: application/json');
            echo json_encode(['withdrawals' => $withdrawals]);
            exit();
        } else {

            $data['get-withdrawals'] = $withdrawalModel->getWithdrawals($data['user']['userid']);
        }

        if (isset($this->url[2]) && $this->url[2] == 'completed') {
            $data['get-withdrawals'] = $withdrawalModel->getWithdrawalsCompleted($data['user']['userid']);
            // If the payout exists, return the content
            return ['content' => $this->view->render($data, 'user/payouts/payout-completed')];
        }elseif (isset($this->url[2]) && $this->url[2] == 'pending') {
            $data['get-withdrawals'] = $withdrawalModel->getWithdrawalsPending($data['user']['userid']);
            // If the payout exists, return the content
            return ['content' => $this->view->render($data, 'user/payouts/payout-pending')];
        }elseif (isset($this->url[2]) && $this->url[2] == 'initiated') {
            $data['get-withdrawals'] = $withdrawalModel->getWithdrawalsInitiated($data['user']['userid']);
            // If the payout exists, return the content
            return ['content' => $this->view->render($data, 'user/payouts/payout-initiated')];
        }elseif (isset($this->url[2]) && $this->url[2] == 'cancelled') {
            $data['get-withdrawals'] = $withdrawalModel->getWithdrawalsCancelled($data['user']['userid']);
            // If the payout exists, return the content
            return ['content' => $this->view->render($data, 'user/payouts/payout-cancelled')];
        }

        // Default: Render investments view
        return ['content' => $this->view->render($data, 'user/payouts/payout-history')];
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

        /* Use User Model */
        $user = $this->library('User');
        $data['user'] = $user->data();

        // Redirect if a user is not logged in or is blocked
        if (!$user->isLoggedIn()) {
            redirect('login');
        } elseif($data['user']['status'] == 2) {
            redirect('blocked');
        }

        /* Use Models */
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();

        if ($data['settings']["twofa_status"] == 1) {
            // check if twofactor check was initiated
            if ($user->data()["twofactor_flag"] == 1) {
                redirect('twofa');
            }
        }

        /* Use User Model */
        $userModel = $this->model('User');

        // unset the sessions and redirect
        $session = $this->library('Session');
        $session->delete('referral_id');
        $session->delete('planId');
        $session->delete('amount');
        $session->delete("investId");
        $session->delete('access_token');

        /* Use Input Library */
        $input = $this->library('Input');

        if ($input->isAjax() && $input->get('page')) {
            $page = $input->get('page');
            $transactions = $userModel->transactions_limits($data['user']['userid'], $page);

            // Return the JSON response
            header('Content-Type: application/json');
            echo json_encode(['transactions' => $transactions]);
            exit();
        } else {

            $data['get-transactions'] = $userModel->getTransactions($data['user']['userid']);
        }

        // Default: Render transactions view
        return ['content' => $this->view->render($data, 'user/transactions')];
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

        /* Use User Model */
        $user = $this->library('User');
        $data['user'] = $user->data();

        // Redirect if a user is not logged in or is blocked
        if (!$user->isLoggedIn()) {
            redirect('login');
        } elseif($data['user']['status'] == 2) {
            redirect('blocked');
        }

        /* Use Models */
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();

        if ($data['settings']["twofa_status"] == 1) {
            // check if twofactor check was initiated
            if ($user->data()["twofactor_flag"] == 1) {
                redirect('twofa');
            }
        }

        /* Use Referral Model */
        $referralModel = $this->model('Referral');

        // unset the sessions and redirect
        $session = $this->library('Session');
        $session->delete('referral_id');
        $session->delete('planId');
        $session->delete('amount');
        $session->delete("investId");
        $session->delete('access_token');

        $data['ranks'] = $referralModel->getRanks();
        $data['count-invests'] = $referralModel->countInvestments($data['user']['userid']);
        $data['commissions'] = $referralModel->commissions($data['user']['userid']);
        $data['count-referrals'] = $referralModel->countReferrals($data['user']['userid']);
        $data['get-referrals'] = $referralModel->getReferredUsers($data['user']['userid']);

        // Set default rank name
        $data['next-ranking']['name'] = '';
        $data['current-ranking']['name'] = '';

        $data['ranking-bonus']['bonus'] = '';

        $data['ranking-invest']['min_invest'] = '';
        $data['ranking-referral']['min_referral'] = '';

        $data['ranking-remaining']['remaining'] = '';
        $data['referrals-remaining']['remaining'] = '';

        // Determine next rank name
        foreach ($data['ranks'] as $rank) {
            if ($rank['id'] > $data['user']['user_ranking_id']) {
                $data['next-ranking']['name'] = $rank['name'];

                // get rank details
                $data['ranking-bonus']['bonus'] = $rank['bonus'];

                $data['ranking-remaining']['remaining'] = $rank['min_invest'] - $data['count-invests'];
                $data['referrals-remaining']['remaining'] = $rank['min_referral'] - $data['count-referrals'];

                $data['ranking-invest']['min_invest'] = $rank['min_invest'];
                $data['ranking-referral']['min_referral'] = $rank['min_referral'];
                break;
            }
        }

        // Determine current rank name
        foreach ($data['ranks'] as $rank) {
            if ($rank['id'] == $data['user']['user_ranking_id']) {
                $data['current-ranking']['name'] = $rank['name'];
                break;
            }
        }

        // Default: Render investments view
        return ['content' => $this->view->render($data, 'user/referrals/referrals-history')];
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

        /* Use User Model */
        $user = $this->library('User');
        $data['user'] = $user->data();

        // Redirect if a user is not logged in or is blocked
        if (!$user->isLoggedIn()) {
            redirect('login');
        } elseif($data['user']['status'] == 2) {
            redirect('blocked');
        }

        /* Use Models */
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();

        if ($data['settings']["twofa_status"] == 1) {
            // check if twofactor check was initiated
            if ($user->data()["twofactor_flag"] == 1) {
                redirect('twofa');
            }
        }

        /* Use Referral Model */
        $referralModel = $this->model('Referral');

        // unset the sessions and redirect
        $session = $this->library('Session');
        $session->delete('referral_id');
        $session->delete('planId');
        $session->delete('amount');
        $session->delete("investId");
        $session->delete('access_token');

        $data['ranks'] = $referralModel->getRanks();
        $data['count-invests'] = $referralModel->countInvestments($data['user']['userid']);
        $data['count-referrals'] = $referralModel->countReferrals($data['user']['userid']);

        // Set default rank name
        $data['next-ranking']['name'] = '';
        $data['current-ranking']['name'] = '';
        $data['current-ranking']['id'] = '';

        $data['ranking-bonus']['bonus'] = '';

        $data['ranking-invest']['min_invest'] = '';
        $data['ranking-referral']['min_referral'] = '';

        $data['ranking-remaining']['remaining'] = '';
        $data['referrals-remaining']['remaining'] = '';

        // Determine next rank name
        foreach ($data['ranks'] as $rank) {
            if ($rank['id'] > $data['user']['user_ranking_id']) {
                $data['next-ranking']['name'] = $rank['name'];

                // get rank details
                $data['ranking-bonus']['bonus'] = $rank['bonus'];

                $data['ranking-remaining']['remaining'] = $rank['min_invest'] - $data['count-invests'];
                $data['referrals-remaining']['remaining'] = $rank['min_referral'] - $data['count-referrals'];

                $data['ranking-invest']['min_invest'] = $rank['min_invest'];
                $data['ranking-referral']['min_referral'] = $rank['min_referral'];
                break;
            }
        }

        // Determine current rank name
        foreach ($data['ranks'] as $rank) {
            if ($rank['id'] == $data['user']['user_ranking_id']) {
                $data['current-ranking']['name'] = $rank['name'];
                $data['current-ranking']['id'] = $rank['id'];
                break;
            }
        }

        // Default: Render investments view
        return ['content' => $this->view->render($data, 'user/referrals/ranking')];
    }

    /**
     * send
     */
    public function send(): array
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];

        /* Use User Model */
        $user = $this->library('User');
        $data['user'] = $user->data();

        // Redirect if a user is not logged in or is blocked
        if (!$user->isLoggedIn()) {
            redirect('login');
        } elseif($data['user']['status'] == 2) {
            redirect('blocked');
        }

        /* Use Models */
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();

        if ($data['settings']['b_transfer'] == 2) {
            redirect('user/dashboard');
        }

        if ($data['settings']["twofa_status"] == 1) {
            // check if twofactor check was initiated
            if ($user->data()["twofactor_flag"] == 1) {
                redirect('twofa');
            }
        }

        /* Use User Model */
        $userModel = $this->model('User');

        /* Use Input Library */
        $input = $this->library('Input');

        // Fetch the email template with id = 14 & 15
        $data['email-templates'] = $settingsModel->getEmailTemplate();
        $senderTemplate = $data['email-templates'][14] ?? null;
        $receiverTemplate = $data['email-templates'][15] ?? null;

        // unset the sessions and redirect
        $session = $this->library('Session');
        $session->delete('referral_id');
        $session->delete('planId');
        $session->delete('amount');
        $session->delete("investId");
        $session->delete('access_token');

        if (isset($this->url[2]) && $this->url[2] == 'confirm') {
            
            // check if the url is set and the transferId exisits
            if (!isset($this->url[3]) || !intval($this->url[3]) || !$userModel->hasTransferId($this->url[3])) {
                $_SESSION['message'][] = ['error', 'Failed to fetch transfer details. Please try again later.'];
                redirect('user/send');
            }

            // Retrieve transfer details using transferId
            $data['transfer-details'] = $userModel->getTransfer($this->url[3]);

            // Check if transfer is completed
            if ($data['transfer-details']['status'] == 1) {
                $_SESSION['message'][] = ['error', 'This transfer has already been marked as completed'];
                redirect('user/send');
            }

            // Retrieve receiver associated with the transfer details using their email
            $receiver = $userModel->getEmail($data['transfer-details']['receiver_email']);

            // Set receiver variables
            $receiverFirstName = $receiver['firstname'];
            $receiverLastName = $receiver['lastname'];
            $receiverId = $receiver['userid'];

            // fetch users' wallets
            $senderWallet = $data['user']['interest_wallet'];
            $receiverWallet = $receiver['interest_wallet'];

            // set the IDs
            $transferId = $data['transfer-details']['transferId'];
            $senderId = $data['user']['userid'];

            // retrieve transfer amount
            $amount = $data['transfer-details']['amount'];

            // Process form submission if method is POST
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Check if input exists
                if ($input->exists()) {
                    try {
                        // Update sends details
                        $update = $userModel->updateSend($transferId, $senderId, $receiverId, $amount, $receiverFirstName, $receiverLastName, $data['user']['firstname'], $data['user']['lastname'], $senderWallet, $receiverWallet);

                        // update was successful
                        if ($update === 1) {
                            // Initialize variables for email notifications
                            $senderEmailSent = false;
                            $receiverEmailSent = false;

                            // email notification is enabled
                            if ($data['settings']["email_notification"] == 1) {

                                // prepare email templates
                                $siteName = $data['settings']['sitename'];
                                $siteLogo = $data['settings']['logo'];
                                $siteUrl = getenv('URL_PATH');
                                $dateNow = date('Y');

                                // Replace placeholders in sender email body
                                $senderBody = str_replace(
                                    ['{FIRSTNAME}', '{LASTNAME}', '{RECEIVERFIRSTNAME}', '{RECEIVERLASTNAME}', '{AMOUNT}', '{CURRENCY}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'],
                                    [$data['user']['firstname'], $data['user']['lastname'], $receiverFirstName, $receiverLastName, $amount, $data['user']['currency'], $siteName, $siteLogo, $siteUrl, $dateNow],
                                    $senderTemplate['body']
                                );

                                $senderEmail = $data['user']['email'];
                                $senderSubject = $senderTemplate['subject'];

                                // sender template is enabled
                                if ($senderTemplate !== null && $senderTemplate['status'] == 1) {
                                    // Send sender email
                                    $senderEmailSent = EmailHelper::sendEmail($data['settings'], $senderEmail, $senderSubject, $senderBody);
                                }

                                // Replace placeholders in receiver email body
                                $receiverBody = str_replace(
                                    ['{FIRSTNAME}', '{LASTNAME}', '{SENDERFIRSTNAME}', '{SENDERLASTNAME}', '{AMOUNT}', '{CURRENCY}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'],
                                    [$receiver['firstname'], $receiver['lastname'], $data['user']['firstname'], $data['user']['lastname'], $amount, $receiver['currency'], $siteName, $siteLogo, $siteUrl, $dateNow],
                                    $receiverTemplate['body']
                                );

                                $receiverEmail = $receiver['email'];
                                $receiverSubject = $receiverTemplate['subject'];

                                // receiver template is enabled
                                if ($receiverTemplate !== null && $receiverTemplate['status'] == 1) {
                                    // Send receiver email
                                    $receiverEmailSent = EmailHelper::sendEmail($data['settings'], $receiverEmail, $receiverSubject, $receiverBody);
                                }

                                if ($senderEmailSent && $receiverEmailSent) {
                                    $response = [
                                        'status' => 'success',
                                        'redirect' => 'user/send/success/' . $transferId
                                    ];
                                } else {
                                    $response = [
                                        'status' => 'warning',
                                        'message' => 'Failed to send emails.',
                                        'redirect' => 'user/send/success/' . $transferId
                                    ];
                                }
                            } else {
                                $response = [
                                    'status' => 'success',
                                    'redirect' => 'user/send/success/' . $transferId
                                ];
                            }
                        } else {
                            $response = [
                                'status' => 'error',
                                'message' => 'We failed to process your transfer. Please try again later.'
                            ];
                        }
                    } catch (Exception $e) {
                        // Error response if an exception occurs during profile update
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
            }

            // Default: Render confirm-send view
            return ['content' => $this->view->render($data, 'user/send-money/confirm-send')];
        }elseif (isset($this->url[2]) && $this->url[2] == 'success') {

            // check if the url is set and the transferId exisits
            if (!isset($this->url[3]) || !intval($this->url[3]) || !$userModel->hasTransferId($this->url[3])) {
                $_SESSION['message'][] = ['error', 'Failed to fetch transfer details. Please try again later.'];
                redirect('user/send');
            }

            // Retrieve transfer details using transferId
            $data['transfer-details'] = $userModel->getTransfer($this->url[3]);

            // Check if transfer is not completed
            if ($data['transfer-details']['status'] != 1) {
                redirect('user/send/confirm/' . $this->url[3]);
            }

            // Retrieve receiver associated with the transfer details using their email
            $receiver = $userModel->getEmail($data['transfer-details']['receiver_email']);

            // fetch receiver's profile picture
            $data['receiver']['imagelocation'] = $receiver['imagelocation'];

            // Default: Render sent-successfully view
            return ['content' => $this->view->render($data, 'user/send-money/sent-successfully')];
        }

        // Process form submission if method is POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Check if input exists
            if ($input->exists()) {
                $validator = $this->library('Validator');

                // Validate input data
                $validation = $validator->check($_POST, [
                    'receiver' => [
                        'required' => true, 
                        'email' => true
                    ],
                    'amount' => [
                        'required' => true,
                        'float' => true
                    ],
                    'note' => [
                        'required' => true
                    ]
                ]);

                if (!$validation->fails()) {
                    try {
                        // check if balance transfer is enabled 
                        if ($data['settings']['b_transfer'] == 1) {
                            // Get input data
                            $receiver_email = $input->get('receiver');
                            $amount = $input->get('amount');
                            $note = $input->get('note');

                            // Check if user has sufficient funds
                            if ($data['user']['interest_wallet'] <= 0.00) {
                                // Empty balance warning
                                $response = [
                                    'status' => 'warning',
                                    'message' => 'You can\'t send from an empty balance.'
                                ];
                            } elseif ($amount > $data['user']['interest_wallet']) {
                                // Insufficient funds warning
                                $response = [
                                    'status' => 'warning',
                                    'message' => 'You have insufficient funds to send.'
                                ];
                            } elseif (strtolower($receiver_email) == strtolower($data['user']['email'])) {
                                // Same email warning
                                $response = [
                                    'status' => 'error',
                                    'message' => 'You can\'t send funds to yourself.'
                                ];
                            } else {

                                // check if the email exisits
                                $has = $userModel->hasEmail($receiver_email);

                                // email doesn't exisit throw error
                                if (!$has) {
                                    $response = [
                                        'status' => 'error',
                                        'message' => 'This receiver email does not exist in our database.'
                                    ];
                                }else{

                                    /* Unique ID */
                                    $transferId = $this->uniqueid();

                                    // Generate a unique transaction ID
                                    $trx = $this->generateTransactionID();

                                    $receiver = $userModel->getEmail($receiver_email);
                                    $receiverId = $receiver['userid'];
                                    $senderId = $data['user']['userid'];

                                    // Insert sends details
                                    $insert = $userModel->send($transferId, $senderId, $receiverId, $receiver_email, $amount, $note, $trx);

                                    if ($insert === 1) {
                                        // Success response
                                        $response = [
                                            'status' => 'success',
                                            'redirect' => 'user/send/confirm/' . $transferId
                                        ];
                                    } else {
                                        $response = [
                                            'status' => 'error',
                                            'message' => 'We failed to process your transfer. Please try again later.'
                                        ];
                                    }
                                }
                            }
                        }else{
                            $response = [
                                'status' => 'error',
                                'message' => 'Transfers are currently disabled. Please try again later.'
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

        // Default: Render send-money view
        return ['content' => $this->view->render($data, 'user/send-money/send')];
    }

    /**
     * request
     */
    public function request(): array
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];

        /* Use User Model */
        $user = $this->library('User');
        $data['user'] = $user->data();

        // Redirect if a user is not logged in or is blocked
        if (!$user->isLoggedIn()) {
            redirect('login');
        } elseif($data['user']['status'] == 2) {
            redirect('blocked');
        }

        /* Use Models */
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();

        if ($data['settings']['b_request'] == 2) {
            redirect('user/dashboard');
        }

        if ($data['settings']["twofa_status"] == 1) {
            // check if twofactor check was initiated
            if ($user->data()["twofactor_flag"] == 1) {
                redirect('twofa');
            }
        }

        /* Use User Model */
        $userModel = $this->model('User');

        /* Use Input Library */
        $input = $this->library('Input');

        // Fetch the email template with id = 14, 15, 16 & 17
        $data['email-templates'] = $settingsModel->getEmailTemplate();
        $senderTemplate = $data['email-templates'][14] ?? null;
        $receiverTemplate = $data['email-templates'][15] ?? null;
        $requestTemplate = $data['email-templates'][16] ?? null;
        $rejectTemplate = $data['email-templates'][17] ?? null;

        // unset the sessions and redirect
        $session = $this->library('Session');
        $session->delete('referral_id');
        $session->delete('planId');
        $session->delete('amount');
        $session->delete("investId");
        $session->delete('access_token');

        if (isset($this->url[2]) && $this->url[2] == 'confirm') {

            // check if the url is set and the requestId exisits
            if (!isset($this->url[3]) || !intval($this->url[3]) || !$userModel->hasRequestId($this->url[3])) {
                $_SESSION['message'][] = ['error', 'Failed to fetch request details. Please try again later.'];
                redirect('user/request');
            }

            // Retrieve request details using requestId
            $data['request-details'] = $userModel->getRequest($this->url[3]);

            // Check if payment request is completed
            if ($data['request-details']['status'] == 1) {
                $_SESSION['message'][] = ['error', 'This payment request is already complete'];
                redirect('user/request');
            }

            // Check if payment request is pending
            if ($data['request-details']['status'] == 2) {
                $_SESSION['message'][] = ['error', 'This payment request is already in progress'];
                redirect('user/request');
            }

            // Check if payment request is rejected
            if ($data['request-details']['status'] == 3) {
                $_SESSION['message'][] = ['error', 'This payment request is already rejected'];
                redirect('user/request');
            }

            // Retrieve sender associated with the request details using their email
            $sender = $userModel->getEmail($data['request-details']['sender_email']);

            // Set sender variables
            $senderFirstName = $sender['firstname'];
            $senderLastName = $sender['lastname'];
            $senderId = $sender['userid'];

            // set the IDs
            $requestId = $data['request-details']['requestId'];

            // retrieve payment request amount
            $amount = $data['request-details']['amount'];

            // Process form submission if method is POST
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Check if input exists
                if ($input->exists()) {
                    try {
                        // Update request details
                        $update = $userModel->updateRequest($requestId, $data['user']['userid'], $senderId, $amount, $senderFirstName, $senderLastName, $data['user']['firstname'], $data['user']['lastname']);

                        // update was successful
                        if ($update === 1) {

                            // email notification is enabled
                            if ($data['settings']["email_notification"] == 1) {

                                $siteName = $data['settings']['sitename'];
                                $siteLogo = $data['settings']['logo'];
                                $siteUrl = getenv('URL_PATH');
                                $dateNow = date('Y');

                                // request template is enabled
                                if ($requestTemplate !== null && $requestTemplate['status'] == 1) {

                                    // Replace placeholders in request email body
                                    $requestBody = str_replace(
                                        ['{REQUESTID}', '{SENDERFIRSTNAME}', '{SENDERLASTNAME}', '{FIRSTNAME}', '{LASTNAME}', '{AMOUNT}', '{CURRENCY}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'],
                                        [$requestId, $senderFirstName, $senderLastName, $data['user']['firstname'], $data['user']['lastname'], $amount, $data['user']['currency'], $siteName, $siteLogo, $siteUrl, $dateNow],
                                        $requestTemplate['body']
                                    );

                                    $requestEmail = $sender['email'];
                                    $requestSubject = $requestTemplate['subject'];

                                    // Send email
                                    if (EmailHelper::sendEmail($data['settings'], $requestEmail, $requestSubject, $requestBody)) {
                                        $response = [
                                            'status' => 'success',
                                            'redirect' => 'user/request/success/' . $requestId
                                        ];
                                    }else{
                                        $response = [
                                            'status' => 'error',
                                            'message' => 'Failed to send emails.',
                                            'redirect' => 'user/request/success/' . $requestId
                                        ];
                                    }
                                }else{
                                    $response = [
                                        'status' => 'success',
                                        'redirect' => 'user/request/success/' . $requestId
                                    ];
                                }
                            }else{
                                $response = [
                                    'status' => 'success',
                                    'redirect' => 'user/request/success/' . $requestId
                                ];
                            }
                        }else{
                            $response = [
                                'status' => 'warning',
                                'message' => 'We failed to process your request. Please try again later.'
                            ];
                        }
                    } catch (Exception $e) {
                        // Error response if an exception occurs during profile update
                        $response = [
                            'status' => 'warning',
                            'message' => $e->getMessage()
                        ];
                    }

                    // Send the JSON response
                    header('Content-Type: application/json');
                    echo json_encode($response);
                    exit;
                }
            }

            // Default: Render confirm-request view
            return ['content' => $this->view->render($data, 'user/request-money/confirm-request')];
        }elseif (isset($this->url[2]) && $this->url[2] == 'success') {

            // check if the url is set and the requestId exisits
            if (!isset($this->url[3]) || !intval($this->url[3]) || !$userModel->hasRequestId($this->url[3])) {
                $_SESSION['message'][] = ['error', 'Failed to fetch request details. Please try again later.'];
                redirect('user/request');
            }

            // Retrieve request details using requestId
            $data['request-details'] = $userModel->getRequest($this->url[3]);

            // Check if request is not in progress
            if ($data['request-details']['status'] != 2) {
                redirect('user/request/confirm/' . $this->url[3]);
            }

            // Retrieve sender associated with the request details using their email
            $sender = $userModel->getEmail($data['request-details']['sender_email']);

            // fetch sender's profile picture
            $data['sender']['imagelocation'] = $sender['imagelocation'];

            // Default: Render request-successfully view
            return ['content' => $this->view->render($data, 'user/request-money/request-successfully')];
        }elseif (isset($this->url[2]) && $this->url[2] == 'review') {

            // check if the url is set and the requestId exisits
            if (!isset($this->url[3]) || !intval($this->url[3]) || !$userModel->hasRequestId($this->url[3])) {
                $_SESSION['message'][] = ['error', 'Failed to fetch request details. Please try again later.'];
                redirect('user/request');
            }

            // Retrieve request details using the provided request ID
            $data['request-details'] = $userModel->getRequest($this->url[3]);

            // Check if the senderId in the request details matches the userId of the user who's reviewing and fulfilling the request
            if ($data['request-details']['senderId'] != $data['user']['userid']) {
                $_SESSION['message'][] = ['error', 'Sorry, you are not assigned to review and fulfill this payment request.'];
                redirect('user/request');
            }

            // Check if the request was just initiated
            if ($data['request-details']['status'] == 0) {
                redirect('user/request/confirm/' . $this->url[3]);
            }

            // Check if the request is complete or rejected
            if ($data['request-details']['status'] == 1 || $data['request-details']['status'] == 3) {
                redirect('user/request');
            }

            // Retrieve user associated with the request details
            $receiver = $userModel->getUser($data['request-details']['receiverId']);

            // fetch receiver's profile picture and email
            $data['receiver']['imagelocation'] = $receiver['imagelocation'];
            $data['receiver']['email'] = $receiver['email'];

            // If all checks pass, render the review-request view
            return ['content' => $this->view->render($data, 'user/request-money/review-request')];
        }elseif (isset($this->url[2]) && $this->url[2] == 'approve') {

            // check if the input is ajax and input contains parameter 'id'
            if ($input->isAjax() && $input->get('id')) {

                // set requestId variable
                $requestId = $input->get('id');

                // check if requestId exisits 
                $hasRequestId = $userModel->hasRequestId($requestId);

                // if it doesn't exisit throw error
                if (!$hasRequestId) {
                    $response = [
                        'status' => 'error',
                        'message' => 'We failed to process your request.'
                    ];
                } else {

                    // Retrieve request details using requestId
                    $request = $userModel->getRequest($requestId);

                    // Retrieve receiver details associated with the request
                    $receiver = $userModel->getReceiver($request['receiverId']);

                    // Set variables
                    $receiverFirstName = $receiver['firstname'];
                    $receiverLastName = $receiver['lastname'];
                    $receiverId = $receiver['userid'];

                    // fetch wallets 
                    $senderWallet = $data['user']['interest_wallet'];
                    $receiverWallet = $receiver['interest_wallet'];

                    // Retrieve request amount
                    $amount = $request['amount'];

                    // Check if user has sufficient funds
                    if ($data['user']['interest_wallet'] <= 0.00) {
                        // Empty balance warning
                        $response = [
                            'status' => 'error',
                            'message' => 'You can\'t send from an empty balance.'
                        ];
                    } elseif ($amount > $data['user']['interest_wallet']) {
                        // Insufficient funds warning
                        $response = [
                            'status' => 'error',
                            'message' => 'You have insufficient funds to send.'
                        ];
                    }else{

                        try {
                            // approve the request
                            $approve = $userModel->approve($requestId, $receiverId, $data['user']['userid'], $amount, $receiverFirstName, $receiverLastName, $data['user']['firstname'], $data['user']['lastname'], $senderWallet, $receiverWallet);

                            // if approve is successful
                            if ($approve == 1) {
                                // Initialize variables for email notifications
                                $senderEmailSent = false;
                                $receiverEmailSent = false;

                                // email notification is enabled
                                if ($data['settings']["email_notification"] == 1) {
                                    
                                    // prepare email templates
                                    $siteName = $data['settings']['sitename'];
                                    $siteLogo = $data['settings']['logo'];
                                    $siteUrl = getenv('URL_PATH');
                                    $dateNow = date('Y');

                                    // Replace placeholders in sender email body
                                    $senderBody = str_replace(
                                        ['{FIRSTNAME}', '{LASTNAME}', '{RECEIVERFIRSTNAME}', '{RECEIVERLASTNAME}', '{AMOUNT}', '{CURRENCY}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'],
                                        [$data['user']['firstname'], $data['user']['lastname'], $receiverFirstName, $receiverLastName, $amount, $data['user']['currency'], $siteName, $siteLogo, $siteUrl, $dateNow],
                                        $senderTemplate['body']
                                    );

                                    $senderEmail = $data['user']['email'];
                                    $senderSubject = $senderTemplate['subject'];

                                    // sender template is enabled
                                    if ($senderTemplate !== null && $senderTemplate['status'] == 1) {
                                        // Send sender email
                                        $senderEmailSent = EmailHelper::sendEmail($data['settings'], $senderEmail, $senderSubject, $senderBody);
                                    }

                                    // Replace placeholders in receiver email body
                                    $receiverBody = str_replace(
                                        ['{FIRSTNAME}', '{LASTNAME}', '{SENDERFIRSTNAME}', '{SENDERLASTNAME}', '{AMOUNT}', '{CURRENCY}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'],
                                        [$receiver['firstname'], $receiver['lastname'], $data['user']['firstname'], $data['user']['lastname'], $amount, $receiver['currency'], $siteName, $siteLogo, $siteUrl, $dateNow],
                                        $receiverTemplate['body']
                                    );

                                    $receiverEmail = $receiver['email'];
                                    $receiverSubject = $receiverTemplate['subject'];

                                    // receiver template is enabled
                                    if ($receiverTemplate !== null && $receiverTemplate['status'] == 1) {
                                        // Send receiver email
                                        $receiverEmailSent = EmailHelper::sendEmail($data['settings'], $receiverEmail, $receiverSubject, $receiverBody);
                                    }

                                    // Check if both emails were sent successfully
                                    if ($senderEmailSent && $receiverEmailSent) {
                                        $response = [
                                            'status' => 'success',
                                            'redirect' => 'user/request/approved/' . $requestId
                                        ];
                                    } else {
                                        // Failed to send one or both emails
                                        $response = [
                                            'status' => 'warning',
                                            'redirect' => 'user/request/approved/' . $requestId,
                                            'message' => 'Approved, but we failed to send an email notification'
                                        ];
                                    }
                                } else {
                                    // Email notification is disabled
                                    $response = [
                                        'status' => 'success',
                                        'redirect' => 'user/request/approved/' . $requestId
                                    ];
                                }
                            } else {
                                // Failed to approve
                                $response = [
                                    'status' => 'error',
                                    'message' => 'We failed to process your request.'
                                ];
                            }
                        } catch (Exception $e) {
                            // Error response if an exception occurs during profile update
                            $response = [
                                'status' => 'error',
                                'message' => $e->getMessage()
                            ];
                        }
                    }
                }

                // Send the JSON response
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }else{
                redirect('user/request');
            }
        }elseif (isset($this->url[2]) && $this->url[2] == 'approved') {

            // check if the url is set and the requestId exisits
            if (!isset($this->url[3]) || !intval($this->url[3]) || !$userModel->hasRequestId($this->url[3])) {
                $_SESSION['message'][] = ['error', 'Failed to fetch request details. Please try again later.'];
                redirect('user/request');
            }

            // Retrieve request details using requestId
            $data['request-details'] = $userModel->getRequest($this->url[3]);

            // Check if the request is not approved
            if ($data['request-details']['status'] != 1) {
                redirect('user/request/review/' . $this->url[3]);
            }

            // Convert the 'updated_at' timestamp from the data into a Unix timestamp
            $updatedAt = strtotime($data['request-details']['updated_at']);

            // Get the current Unix timestamp
            $currentDateTime = time();

            // Calculate the timestamp representing 2 minutes ago
            $twoMinutesAgo = $currentDateTime - (2 * 60); // 2 minutes in seconds

            // Check if the payment request was updated more than 2 minutes ago
            if ($updatedAt <= $twoMinutesAgo) {
                $_SESSION['message'][] = ['error', 'This payment request has expired.'];
                redirect('user/request');
            }

            // Retrieve receiver details associated with the request
            $data['receiver-details'] = $userModel->getReceiver($data['request-details']['receiverId']);

            // Default: Render approved view
            return ['content' => $this->view->render($data, 'user/request-money/approved')];
        }elseif (isset($this->url[2]) && $this->url[2] == 'reject') {

            // check if the input is ajax and input contains parameter 'id'
            if ($input->isAjax() && $input->get('id')) {

                // set requestId variable
                $requestId = $input->get('id');

                // check if requestId exisits 
                $hasRequestId = $userModel->hasRequestId($requestId);

                // if it doesn't exisit throw error
                if (!$hasRequestId) {
                    $response = [
                        'status' => 'error',
                        'message' => 'We failed to process your request.'
                    ];
                } else {

                    // Retrieve request details using requestId
                    $request = $userModel->getRequest($requestId);

                    // Retrieve receiver details associated with the request
                    $receiver = $userModel->getReceiver($request['receiverId']);

                    // Set variables
                    $receiverFirstName = $receiver['firstname'];
                    $receiverLastName = $receiver['lastname'];
                    $receiverId = $receiver['userid'];

                    // Retrieve request amount
                    $amount = $request['amount'];

                    try {
                        // reject the request
                        $reject = $userModel->reject($requestId, $receiverId, $data['user']['userid'], $amount, $receiverFirstName, $receiverLastName, $data['user']['firstname'], $data['user']['lastname']);

                        if ($reject == 1) {

                            // email notification is enabled
                            if ($data['settings']["email_notification"] == 1) {

                                $siteName = $data['settings']['sitename'];
                                $siteLogo = $data['settings']['logo'];
                                $siteUrl = getenv('URL_PATH');
                                $dateNow = date('Y');

                                // reject template is enabled
                                if ($rejectTemplate !== null && $rejectTemplate['status'] == 1) {

                                    // Replace placeholders in reject email body
                                    $rejectBody = str_replace(
                                        ['{FIRSTNAME}', '{LASTNAME}', '{SENDERFIRSTNAME}', '{SENDERLASTNAME}', '{AMOUNT}', '{CURRENCY}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'],
                                        [$receiver['firstname'], $receiver['lastname'], $data['user']['firstname'], $data['user']['lastname'], $amount, $receiver['currency'], $siteName, $siteLogo, $siteUrl, $dateNow],
                                        $rejectTemplate['body']
                                    );

                                    $rejectEmail = $receiver['email'];
                                    $rejectSubject = $rejectTemplate['subject'];

                                    // Send email
                                    if (EmailHelper::sendEmail($data['settings'], $rejectEmail, $rejectSubject, $rejectBody)) {
                                        // Email sent successfully
                                        $response = [
                                            'status' => 'success',
                                            'redirect' => 'user/request/rejected/' . $requestId
                                        ];
                                    } else {
                                        // Failed to send email
                                        $response = [
                                            'status' => 'warning',
                                            'redirect' => 'user/request/rejected/' . $requestId,
                                            'message' => 'Rejected, but we failed to send an email notification'
                                        ];
                                    }
                                } else {
                                    // Reject template is disabled
                                    $response = [
                                        'status' => 'success',
                                        'redirect' => 'user/request/rejected/' . $requestId
                                    ]; 
                                }
                            } else {
                                // Email notification is disabled
                                $response = [
                                    'status' => 'success',
                                    'redirect' => 'user/request/rejected/' . $requestId
                                ];
                            }
                        } else {
                            $response = [
                                'status' => 'error',
                                'message' => 'We failed to process your request.'
                            ];
                        }
                    } catch (Exception $e) {
                        // Error response if an exception occurs during profile update
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
                redirect('user/request');
            }
        }elseif (isset($this->url[2]) && $this->url[2] == 'rejected') {

            // check if the url is set and the requestId exisits
            if (!isset($this->url[3]) || !intval($this->url[3]) || !$userModel->hasRequestId($this->url[3])) {
                $_SESSION['message'][] = ['error', 'Failed to fetch request details. Please try again later.'];
                redirect('user/request');
            }

            // Retrieve request details using requestId
            $data['request-details'] = $userModel->getRequest($this->url[3]);

            // Check if the request is not rejected
            if ($data['request-details']['status'] != 3) {
                redirect('user/request/review/' . $this->url[3]);
            }

            // Convert the 'updated_at' timestamp from the data into a Unix timestamp
            $updatedAt = strtotime($data['request-details']['updated_at']);

            // Get the current Unix timestamp
            $currentDateTime = time();

            // Calculate the timestamp representing 2 minutes ago
            $twoMinutesAgo = $currentDateTime - (2 * 60); // 2 minutes in seconds

            // Check if the payment request was updated more than 2 minutes ago
            if ($updatedAt <= $twoMinutesAgo) {
                $_SESSION['message'][] = ['error', 'This payment request has expired.'];
                redirect('user/request');
            }

            // Retrieve receiver details associated with the request
            $data['receiver-details'] = $userModel->getReceiver($data['request-details']['receiverId']);

            // Default: Render rejected view
            return ['content' => $this->view->render($data, 'user/request-money/rejected')];
        }

        // Process form submission if method is POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Check if input exists
            if ($input->exists()) {
                $validator = $this->library('Validator');

                // Validate input data
                $validation = $validator->check($_POST, [
                    'sender' => [
                        'required' => true, 
                        'email' => true
                    ],
                    'amount' => [
                        'required' => true,
                        'float' => true
                    ],
                    'note' => [
                        'required' => true
                    ]
                ]);

                if (!$validation->fails()) {
                    try {
                        // check if balance transfer is enabled 
                        if ($data['settings']['b_request'] == 1) {
                            // Get input data
                            $sender_email = $input->get('sender');
                            $amount = $input->get('amount');
                            $note = $input->get('note');

                            if (strtolower($sender_email) == strtolower($data['user']['email'])) {
                                $response = [
                                    'status' => 'error',
                                    'message' => 'You can\'t request funds from yourself.'
                                ];
                            } else {
                                $has = $userModel->hasEmail($sender_email);

                                if (!$has) {
                                    $response = [
                                        'status' => 'warning',
                                        'message' => 'This receiver email does not exist in our database.'
                                    ];
                                }else{

                                    /* Unique ID */
                                    $requestId = $this->uniqueid();

                                    // Generate a unique transaction ID
                                    $trx = $this->generateTransactionID();

                                    $receiverId = $data['user']['userid'];
                                    $sender = $userModel->getEmail($sender_email);
                                    $senderId = $sender['userid'];

                                    // Insert request details
                                    $insert = $userModel->request($requestId, $receiverId, $senderId, $sender_email, $amount, $note, $trx);

                                    if ($insert === 1) {
                                        // Success response
                                        $response = [
                                            'status' => 'success',
                                            'redirect' => 'user/request/confirm/' . $requestId
                                        ];
                                    } else {
                                        $response = [
                                            'status' => 'error',
                                            'message' => 'We failed to process your request. Please try again later.'
                                        ];
                                    }
                                }
                            }
                        }else{
                            $response = [
                                'status' => 'error',
                                'message' => 'The feature to request money is currently disabled.'
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

        // Default: Render send-money view
        return ['content' => $this->view->render($data, 'user/request-money/request')];
    }

    /**
     * support
     */
    public function support(): array
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];

        /* Use User Model */
        $user = $this->library('User');
        $data['user'] = $user->data();

        // Redirect if a user is not logged in or is blocked
        if (!$user->isLoggedIn()) {
            redirect('login');
        } elseif($data['user']['status'] == 2) {
            redirect('blocked');
        }

        /* Use Models */
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();

        if ($data['settings']["twofa_status"] == 1) {
            // check if twofactor check was initiated
            if ($user->data()["twofactor_flag"] == 1) {
                redirect('twofa');
            }
        }

        /* Use Input Library */
        $input = $this->library('Input');

        // Fetch the email template with id = 19
        $data['email-templates'] = $settingsModel->getEmailTemplate();
        $contactTemplate = $data['email-templates'][19] ?? null;

        // unset the sessions and redirect
        $session = $this->library('Session');
        $session->delete('referral_id');
        $session->delete('planId');
        $session->delete('amount');
        $session->delete("investId");
        $session->delete('access_token');

        // Check if the request method is POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Check if input exists
            if ($input->exists()) {

                $validator = $this->library('Validator');

                // Validate input data
                $validation = $validator->check($_POST, [
                    'subject' => [
                        'required' => true
                    ],
                    'message' => [
                        'required' => true
                    ]
                ]);

                if (!$validation->fails()) {
                    try {

                        // store variables
                        $subject = $input->get('subject');
                        $description = $input->get('description');

                        // email notification is enabled
                        if ($data['settings']["email_notification"] == 1) {

                            $siteName = $data['settings']['sitename'];
                            $siteLogo = $data['settings']['logo'];
                            $siteUrl = getenv('URL_PATH');
                            $dateNow = date('Y');

                            // contact template is enabled
                            if ($contactTemplate !== null && $contactTemplate['status'] == 1) {

                                $contactTemplate['body'] = str_replace(
                                    ['{FIRSTNAME}', '{LASTNAME}', '{SUBJECT}', '{MESSAGE}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'], [$data['user']['firstname'], $data['user']['lastname'], $subject, $description, $siteName, $siteLogo, $siteUrl, $dateNow],
                                    $contactTemplate['body']
                                );

                                // Send email with notification to the user
                                $recipientEmail = $data['settings']['email_address'];
                                $body = $contactTemplate['body'];

                                if (EmailHelper::sendEmail($data['settings'], $recipientEmail, $subject, $body)) {
                                    // Email sent successfully
                                    $response = [
                                        'status' => 'success',
                                        'message' => 'Your contact email has been sent successfully',
                                        'redirect' => 'user/support'
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

        // Default: Render investments view
        return ['content' => $this->view->render($data, 'user/support/contact-us')];
    }

    /**
     * loan
     */
    public function loan(): array
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];

        /* Use User Model */
        $user = $this->library('User');
        $data['user'] = $user->data();

        // Redirect if a user is not logged in or is blocked
        if (!$user->isLoggedIn()) {
            redirect('login');
        } elseif($data['user']['status'] == 2) {
            redirect('blocked');
        }

        /* Use Models */
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();

        if ($data['settings']["twofa_status"] == 1) {
            // check if twofactor check was initiated
            if ($user->data()["twofactor_flag"] == 1) {
                redirect('twofa');
            }
        }

        /* Use User Model */
        $userModel = $this->model('User');

        /* Use Input Library */
        $input = $this->library('Input');

        // unset the sessions and redirect
        $session = $this->library('Session');
        $session->delete('referral_id');
        $session->delete('planId');
        $session->delete('amount');
        $session->delete("investId");
        $session->delete('access_token');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $validator = $this->library('Validator');
            $validationRules = [
                'amount' => ['required' => true],
                'loan_remarks' => ['required' => true],
                'loan_term' => ['required' => true],
                'repayment_plan' => ['required' => true],
                'collateral' => ['required' => true],
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
                    
                    $userid = $data['user']['userid'];
                    $loan_reference_id = $this->uniqueid();

                    $insert = $userModel->loan($userid, $loan_reference_id, $input->get('amount'), $input->get('loan_remarks'), $input->get('loan_term'), $input->get('repayment_plan'), $input->get('collateral'));

                    if ($insert == 1) {

                        // email notification is enabled
                        if ($data['settings']["email_notification"] == "1") {

                            $siteName = $data['settings']['sitename'];
                            $siteUrl = getenv('URL_PATH');
                            $dateNow = date('Y');

                            // Fetch the email template with id = 35
                            $data['email-templates'] = $settingsModel->getEmailTemplate();
                            $updateLoanTemplate = $data['email-templates'][35] ?? null;

                            $updateLoanTemplate['body'] = str_replace(
                                ['{FIRSTNAME}', '{LASTNAME}', '{AMOUNT}', '{SITENAME}', '{URL}', '{DATENOW}'],
                                [$data['user']['firstname'], $data['user']['lastname'], $input->get('amount'), $siteName, $siteUrl, $dateNow],
                                $updateLoanTemplate['body']
                            );

                            // Send email with notification to the user
                            $recipientEmail = $data['user']['email'];
                            $subject = $updateLoanTemplate['subject'];
                            $body = $updateLoanTemplate['body'];

                            if (EmailHelper::sendEmail($data['settings'], $recipientEmail, $subject, $body)) {
                                $response = [
                                    'status' => 'success',
                                    'message' => 'We have received your loan application.',
                                    'redirect' => 'user/loan'
                                ];
                            } else {
                                $response = [
                                    'status' => 'error',
                                    'message' => 'We failed to send you a notification. Please try again.',
                                    'redirect' => 'user/loan'
                                ];
                            }
                        }else{

                            // email notification is disabled
                            $response = [
                                'status' => 'success',
                                'message' => 'We have received your loan application.',
                                'redirect' => 'user/loan'
                            ];
                        }
                    } else {
                        $response = [
                            'status' => 'error',
                            'message' => 'Error encountered. Please try again later.',
                            'redirect' => 'user/loan'
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

        // Default: Render investments view
        return ['content' => $this->view->render($data, 'user/loan/request-loan')];
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

        /* Use User Model */
        $user = $this->library('User');
        $data['user'] = $user->data();

        // Redirect if a user is not logged in or is blocked
        if (!$user->isLoggedIn()) {
            redirect('login');
        } elseif($data['user']['status'] == 2) {
            redirect('blocked');
        }

        /* Use Models */
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();

        if ($data['settings']["twofa_status"] == 1) {
            // check if twofactor check was initiated
            if ($user->data()["twofactor_flag"] == 1) {
                redirect('twofa');
            }
        }

        /* Use User Model */
        $userModel = $this->model('User');

        // unset the sessions and redirect
        $session = $this->library('Session');
        $session->delete('referral_id');
        $session->delete('planId');
        $session->delete('amount');
        $session->delete("investId");
        $session->delete('access_token');

        /* Use Input Library */
        $input = $this->library('Input');

        $data['get-loans'] = $userModel->getLoans($data['user']['userid']);

        // Default: Render investments view
        return ['content' => $this->view->render($data, 'user/loan/loan-history')];
    }

    /**
     * handle-plan-purchase
     */
    public function handlePlanPurchase($investId, $data, $planId, $amount, $interest_amount, $repeat_time, $hours, $method, $details, $trx_type, $capital_back_status, $insertType): void
    {

        // Retrieve settings and gateway data
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();

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

        // Fetch the email template with id = 12
        $data['email-templates'] = $settingsModel->getEmailTemplate();
        $investmentTemplate = $data['email-templates'][12] ?? null;

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

            // email notification is enabled
            if ($data['settings']["email_notification"] == 1) {

                $siteName = $data['settings']['sitename'];
                $siteLogo = $data['settings']['logo'];
                $siteUrl = getenv('URL_PATH');
                $dateNow = date('Y');

                // investment template is enabled
                if ($investmentTemplate !== null && $investmentTemplate['status'] == 1) {

                    // Replace placeholders in investment email body
                    $investmentTemplate['body'] = str_replace(
                        ['{FIRSTNAME}', '{LASTNAME}', '{AMOUNT}', '{INTEREST}', '{CURRENCY}', '{PLAN}', '{SITENAME}', '{SITELOGO}', '{URL}', '{DATENOW}'],
                        [$data['user']['firstname'], $data['user']['lastname'], $amount, $interest_amount, $data['user']['currency'], $data['plan-details']['name'], $siteName, $siteLogo, $siteUrl, $dateNow],
                        $investmentTemplate['body']
                    );

                    $recipientEmail = $data['user']['email'];
                    $subject = $investmentTemplate['subject'];
                    $body = $investmentTemplate['body'];

                    // Send plan purchase email
                    if (EmailHelper::sendEmail($data['settings'], $recipientEmail, $subject, $body)) {
                        $response = [
                            'status' => 'success',
                            'redirect' => 'user/plans/invested'
                        ];
                    } else {
                        $response = [
                            'status' => 'error',
                            'message' => 'We failed to send investment notification.',
                            'redirect' => 'user/plans/invested'
                        ];
                    }
                }else{
                    $response = [
                        'status' => 'success',
                        'redirect' => 'user/plans/invested'
                    ];
                }
            }else{
                $response = [
                    'status' => 'success',
                    'redirect' => 'user/plans/invested'
                ];
            }
        } else {
            $response = [
                'status' => 'info',
                'redirect' => 'investment/failed'
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
        $user = $this->library('User');
        $user->logout();
        redirect('login');
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

    //Random String
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
     * @return string
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
     * Generate a unique ID for deposit
     *
     * @return string
     */
    private function uniqueid(): string
    {
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