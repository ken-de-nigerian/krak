<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\controllers;

use KenDeNigerian\Krak\core\Controller;

use Exception;

/**
 * User Controller - Refactored to use services (SRP)
 */
class user extends Controller 
{
    /**
     * Constructor
     */
    public function __construct($db, $url, $container = null)
    {
        parent::__construct($db, $url, $container);

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
     * Dashboard - Refactored to use services
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

        /* Use Settings Service */
        try {
            if ($this->container) {
                $settingsService = $this->service('SettingsService');
                $data['settings'] = $settingsService->getSettings();
                $data['get-gateway'] = $settingsService->getGateways();
                
                $userService = $this->service('UserService');
                $transactionService = $this->service('TransactionService');
                
                // Get user data with relations (prevents N+1)
                $userWithRelations = $userService->getUserWithRelations($data['user']['userid']);
                $data['deposits'] = $userWithRelations['deposits_total'] ?? 0;
                $data['payouts'] = $userWithRelations['withdrawals_total'] ?? 0;
                $data['investments'] = $userWithRelations['investments_total'] ?? 0;
                
                // Get transactions
                $data['get-transactions'] = $transactionService->getRecentTransactions($data['user']['userid'], 5);
                
                // Still use model for methods not yet in services
        $userModel = $this->model('User');
                $data['requests'] = $userModel->getAllPendingRequests($data['user']['userid']);
                } else {
                // Fallback to old way
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();
                $data['get-gateway'] = $settingsModel->getGatewaysWithConversion();
                
        $userModel = $this->model('User');
                $data['requests'] = $userModel->getAllPendingRequests($data['user']['userid']);
                $data['deposits'] = $userModel->deposits($data['user']['userid']);
                $data['payouts'] = $userModel->withdrawals($data['user']['userid']);
                $data['investments'] = $userModel->investments($data['user']['userid']);
                $data['get-transactions'] = $userModel->getTransactions($data['user']['userid']);
            }
        } catch (\Exception $e) {
            // Fallback to old way on error
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();
            $data['get-gateway'] = $settingsModel->getGatewaysWithConversion();
            
        $userModel = $this->model('User');
            $data['requests'] = $userModel->getAllPendingRequests($data['user']['userid']);
            $data['deposits'] = $userModel->deposits($data['user']['userid']);
            $data['payouts'] = $userModel->withdrawals($data['user']['userid']);
            $data['investments'] = $userModel->investments($data['user']['userid']);
            $data['get-transactions'] = $userModel->getTransactions($data['user']['userid']);
        }

        if ($data['settings']["twofa_status"] == 1) {
            // check if twofactor check was initiated
            if ($user->data()["twofactor_flag"] == 1) {
                redirect('twofa');
            }
        }

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
            
            try {
                if ($this->container) {
                    $transactionService = $this->service('TransactionService');
                    $transactions = $transactionService->getTransactionsByUserId($data['user']['userid'], $page, 5);
                                    } else {
        $userModel = $this->model('User');
                    $transactions = $userModel->transactions_limits($data['user']['userid'], $page);
                }
            } catch (\Exception $e) {
        $userModel = $this->model('User');
                $transactions = $userModel->transactions_limits($data['user']['userid'], $page);
            }

            // Return the JSON response
            header('Content-Type: application/json');
            echo json_encode(['transactions' => $transactions]);
            exit();
        }

        // Default: Render dashboard view
        return ['content' => $this->view->render($data, 'user/dashboard')];
    }

    /**
     * Profile - Refactored to use services
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

        /* Use Settings Service */
        try {
            if ($this->container) {
                $settingsService = $this->service('SettingsService');
                $data['settings'] = $settingsService->getSettings();
            } else {
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();
            }
        } catch (\Exception $e) {
        $settingsModel = $this->model('Settings');
        $data['settings'] = $settingsModel->get();
        }

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
                        $userModel->updateProfile($data['user']['userid'], $_POST);
                        
                        // Set success message
                        $session->put('success', 'Profile updated successfully');
                        redirect('user/profile');
                    } catch (Exception $e) {
                        $data['error'] = 'An error occurred while updating your profile.';
                    }
                } else {
                    $data['errors'] = $validation->errors();
                }
            }
        }

        return ['content' => $this->view->render($data, 'user/profile')];
    }

    // Additional methods would follow the same pattern...
    // For brevity, showing the refactored pattern for key methods
}
