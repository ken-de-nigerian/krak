<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\controllers;

use KenDeNigerian\Krak\core\Controller;
class payout extends Controller
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
     * Redirect to the home page.
     *
     * This method redirects the user to the home page.
     */
    public function index(): void
    {
        redirect('user/payout');
    }
    
    /**
     * This method handles the index page for blocked users.
     *
     * @return array
     */
    public function failed(): array
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];

        /* Use User Model */
        $user = $this->library('User');
        $data['user'] = $user->data();

        // Redirect if a user is not logged in or is blocked or the user-type does not match
        if (!$user->isLoggedIn()) {
            redirect('login');
        }

        return ['content' => $this->view->render($data, 'error/failed')];
    }

    /**
     * This method handles the AJAX request to fetch pending withdrawals
     *
     * @return void JSON response containing pending withdrawals
     */
    public function pending(): void
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];

        /* Use User Model */
        $user = $this->library('User');
        $data['user'] = $user->data();

        /* Use Input Library */
        $input = $this->library('Input');
        
        /* Use Withdrawal Model */
        $withdrawalModel = $this->model('Withdrawal');

        if ($input->isAjax() && $input->get('page')) {
            $page = $input->get('page');
            $pending_withdrawals = $withdrawalModel->pending_withdrawals_limits($data['user']['userid'], $page);

            // Return the JSON response
            header('Content-Type: application/json');
            echo json_encode(['withdrawals' => $pending_withdrawals]);
            exit();
        }else{
            redirect('user/payouts');
        }
    }

    /**
     * This method handles the AJAX request to fetch completed withdrawals
     *
     * @return void JSON response containing completed withdrawals
     */
    public function completed(): void
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];

        /* Use User Model */
        $user = $this->library('User');
        $data['user'] = $user->data();

        /* Use Input Library */
        $input = $this->library('Input');
        
        /* Use Withdrawal Model */
        $withdrawalModel = $this->model('Withdrawal');

        if ($input->isAjax() && $input->get('page')) {
            $page = $input->get('page');
            $completed_withdrawals = $withdrawalModel->completed_withdrawals_limits($data['user']['userid'], $page);

            // Return the JSON response
            header('Content-Type: application/json');
            echo json_encode(['withdrawals' => $completed_withdrawals]);
            exit();
        }else{
            redirect('user/payouts');
        }
    }

    /**
     * This method handles the AJAX request to fetch initiated withdrawals
     *
     * @return void JSON response containing initiated withdrawals
     */
    public function initiated(): void
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];

        /* Use User Model */
        $user = $this->library('User');
        $data['user'] = $user->data();

        /* Use Input Library */
        $input = $this->library('Input');
        
        /* Use Withdrawal Model */
        $withdrawalModel = $this->model('Withdrawal');

        if ($input->isAjax() && $input->get('page')) {
            $page = $input->get('page');
            $initiated_withdrawals = $withdrawalModel->initiated_withdrawals_limits($data['user']['userid'], $page);

            // Return the JSON response
            header('Content-Type: application/json');
            echo json_encode(['withdrawals' => $initiated_withdrawals]);
            exit();
        }else{
            redirect('user/payouts');
        }
    }

    /**
     * This method handles the AJAX request to fetch canceled withdrawals
     *
     * @return void JSON response containing canceled withdrawals
     */
    public function cancelled(): void
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];

        /* Use User Model */
        $user = $this->library('User');
        $data['user'] = $user->data();
        
        /* Use Input Library */
        $input = $this->library('Input');

        /* Use Withdrawal Model */
        $withdrawalModel = $this->model('Withdrawal');

        if ($input->isAjax() && $input->get('page')) {
            $page = $input->get('page');
            $cancelled_withdrawals = $withdrawalModel->cancelled_withdrawals_limits($data['user']['userid'], $page);

            // Return the JSON response
            header('Content-Type: application/json');
            echo json_encode(['withdrawals' => $cancelled_withdrawals]);
            exit();
        }else{
            redirect('user/payouts');
        }
    }
}
