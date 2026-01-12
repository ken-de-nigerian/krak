<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\controllers;
use KenDeNigerian\Krak\core\Controller;

use Exception;
class requests extends Controller
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
     * @var object
     */
    protected object $model;

    /**
     * Redirect to the home page.
     *
     * This method redirects the user to the home page.
     */
    public function index(): void
    {
        redirect();
    }

    /**
     * Delete User Account
     *
     * This method deletes the user account.
     */
    public function delete(): void
    {
        $model = $this->model('Requests');
        $input = $this->library('Input');

        $delete = $input->get('id');

        /* Use User Library */
        $user = $this->library('User');
        $data['user'] = $user->data();

        try {
            // Check if the user is deleting their own account
            if ($delete != $data['user']['userid']) {
                $response = [
                    'status' => 'error',
                    'message' => 'You can\'t delete an account that is not yours.'
                ];
            }else{

                // Delete the account
                $deleteResult = $model->deleteAccount($delete);

                if ($deleteResult == 1) {
                    $response = [
                        'status' => 'success',
                        'redirect' => 'login',
                        'message' => 'Your account has been deleted successfully.'
                    ];
                } else {
                    $response = [
                        'status' => 'error',
                        'message' => 'Unable to delete the account. Please try again.'
                    ];
                }
            }
        } catch (Exception $e) {
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

    /**
     * Delete User Account
     *
     * This method deletes the user account from admin dashboard.
     */
    public function delete_from_admin(): void
    {
        $model = $this->model('Requests');
        $input = $this->library('Input');

        $delete = $input->get('id');
        
        try {
            // Delete the account
            $deleteResult = $model->deleteAccount($delete);

            if ($deleteResult == 1) {
                $response = [
                    'status' => 'success',
                    'redirect' => 'admin/users',
                    'message' => 'Your account has been deleted successfully.'
                ];
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'Unable to delete the account. Please try again.'
                ];
            }
        } catch (Exception $e) {
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

    /**
     * Delete User Deposit Record
     *
     * This method deletes the user deposit record.
     */
    public function delete_deposit_record(): void
    {
        // Load the necessary models and libraries
        $model = $this->model('Requests');
        $input = $this->library('Input');

        // User Models
        $userModel = $this->model('User');

        // Check if the request method is POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Load the Validator library
            $validator = $this->library('Validator');

            // Validate the POST data
            $validation = $validator->check($_POST, [
                'depositId' => [
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
                    $depositId = $input->get('depositId');

                    // Check if payment method exists
                    $has = $userModel->hasDepositId($depositId);

                    if (!$has) {
                        // Payment method does not exist warning
                        $response = [
                            'status' => 'error',
                            'message' => 'This depositId does not exist. Please try again.'
                        ];
                    } else {

                        // delete deposit details
                        $delete = $model->deleteUserDeposit($depositId, $userid);

                        if ($delete === 1) {
                            // Success response
                            $response = [
                                'status' => 'success',
                                'message' => 'The deposit record has been deleted successfully.'
                            ];
                        } else {
                            // Deposit could not be deleted, prepare error response
                            $response = [
                                'status' => 'error',
                                'message' => 'An error occurred while deleting deposit record from the user account.',
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
    }

    /**
     * Delete User Withdrawal Record
     *
     * This method deletes the user withdrawal record.
     */
    public function delete_withdrawal_record(): void
    {
        // Load the necessary models and libraries
        $model = $this->model('Requests');
        $input = $this->library('Input');

        // User Models
        $userModel = $this->model('User');

        // Check if the request method is POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Load the Validator library
            $validator = $this->library('Validator');

            // Validate the POST data
            $validation = $validator->check($_POST, [
                'withdrawId' => [
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
                    $withdrawId = $input->get('withdrawId');

                    // Check if payment method exists
                    $has = $userModel->hasWithdrawalId($withdrawId);

                    if (!$has) {
                        // Payment method does not exist warning
                        $response = [
                            'status' => 'error',
                            'message' => 'This withdrawId does not exist. Please try again.'
                        ];
                    } else {

                        // delete deposit details
                        $delete = $model->deleteUserWithdrawal($withdrawId, $userid);

                        if ($delete === 1) {
                            // Success response
                            $response = [
                                'status' => 'success',
                                'message' => 'The withdrawal record has been deleted successfully.'
                            ];
                        } else {
                            // Deposit could not be deleted, prepare error response
                            $response = [
                                'status' => 'error',
                                'message' => 'An error occurred while deleting withdrawal record from the user account.',
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
    }

    /**
     * Delete User Investment Record
     *
     * This method deletes the user investment record.
     */
    public function delete_investment_record(): void
    {
        // Load the necessary models and libraries
        $model = $this->model('Requests');
        $input = $this->library('Input');

        // User Models
        $userModel = $this->model('User');

        // Check if the request method is POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Load the Validator library
            $validator = $this->library('Validator');

            // Validate the POST data
            $validation = $validator->check($_POST, [
                'investId' => [
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
                    $investId = $input->get('investId');

                    // Check if investment id exists
                    $has = $userModel->hasInvestment($investId);

                    if (!$has) {
                        // Payment method does not exist warning
                        $response = [
                            'status' => 'error',
                            'message' => 'This investId does not exist. Please try again.'
                        ];
                    } else {

                        // delete deposit details
                        $delete = $model->deleteUserInvestment($investId, $userid);

                        if ($delete === 1) {
                            // Success response
                            $response = [
                                'status' => 'success',
                                'message' => 'The investment record has been deleted successfully.'
                            ];
                        } else {
                            // Investment could not be deleted, prepare error response
                            $response = [
                                'status' => 'error',
                                'message' => 'An error occurred while deleting investment record from the user account.',
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
    }
}
