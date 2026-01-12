<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\controllers;

use KenDeNigerian\Krak\core\Controller;
class payment extends Controller
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
        redirect('user/deposit');
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
     * This method handles the AJAX request to fetch pending deposits
     *
     * @return void JSON response containing pending deposits
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
        
        /* Use Deposit Model */
        $depositModel = $this->model('Deposit');

        if ($input->isAjax() && $input->get('page')) {
            $page = $input->get('page');
            $pending_deposits = $depositModel->pending_deposits_limits($data['user']['userid'], $page);

            // Return the JSON response
            header('Content-Type: application/json');
            echo json_encode(['deposits' => $pending_deposits]);
            exit();
        }else{
            redirect('user/deposits');
        }
    }

    /**
     * This method handles the AJAX request to fetch completed deposits
     *
     * @return void JSON response containing completed deposits
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
        
        /* Use Deposit Model */
        $depositModel = $this->model('Deposit');

        if ($input->isAjax() && $input->get('page')) {
            $page = $input->get('page');
            $completed_deposits = $depositModel->completed_deposits_limits($data['user']['userid'], $page);

            // Return the JSON response
            header('Content-Type: application/json');
            echo json_encode(['deposits' => $completed_deposits]);
            exit();
        }else{
            redirect('user/deposits');
        }
    }

    /**
     * This method handles the AJAX request to fetch initiated deposits
     *
     * @return void JSON response containing initiated deposits
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
        
        /* Use Deposit Model */
        $depositModel = $this->model('Deposit');

        if ($input->isAjax() && $input->get('page')) {
            $page = $input->get('page');
            $initiated_deposits = $depositModel->initiated_deposits_limits($data['user']['userid'], $page);

            // Return the JSON response
            header('Content-Type: application/json');
            echo json_encode(['deposits' => $initiated_deposits]);
            exit();
        }else{
            redirect('user/deposits');
        }
    }

    /**
     * This method handles the AJAX request to fetch canceled deposits
     *
     * @return void JSON response containing cancelled deposits
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

        /* Use Deposit Model */
        $depositModel = $this->model('Deposit');

        if ($input->isAjax() && $input->get('page')) {
            $page = $input->get('page');
            $cancelled_deposits = $depositModel->cancelled_deposits_limits($data['user']['userid'], $page);

            // Return the JSON response
            header('Content-Type: application/json');
            echo json_encode(['deposits' => $cancelled_deposits]);
            exit();
        }else{
            redirect('user/deposits');
        }
    }
}
