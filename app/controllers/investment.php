<?php

namespace Fir\Controllers;

class Investment extends Controller
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
        redirect('user/plans');
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

        return ['content' => $this->view->render($data, 'error/investment-failed')];
    }

    /**
     * This method handles the AJAX request to fetch pending investments
     *
     * @return void JSON response containing pending investments
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
        
        /* Use User Model */
        $userModel = $this->model('User');

        if ($input->isAjax() && $input->get('page')) {
            $page = $input->get('page');
            $pending_investments = $userModel->pending_investments_limits($data['user']['userid'], $page);

            // Return the JSON response
            header('Content-Type: application/json');
            echo json_encode(['investments' => $pending_investments]);
            exit();
        }else{
            redirect('user/investments');
        }
    }

    /**
     * This method handles the AJAX request to fetch completed investments
     *
     * @return void JSON response containing completed investments
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
        
        /* Use User Model */
        $userModel = $this->model('User');

        if ($input->isAjax() && $input->get('page')) {
            $page = $input->get('page');
            $completed_investments = $userModel->completed_investments_limits($data['user']['userid'], $page);

            // Return the JSON response
            header('Content-Type: application/json');
            echo json_encode(['investments' => $completed_investments]);
            exit();
        }else{
            redirect('user/investments');
        }
    }

    /**
     * This method handles the AJAX request to fetch initiated investments
     *
     * @return void JSON response containing initiated investments
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
        
        /* Use User Model */
        $userModel = $this->model('User');

        if ($input->isAjax() && $input->get('page')) {
            $page = $input->get('page');
            $initiated_investments = $userModel->initiated_investments_limits($data['user']['userid'], $page);

            // Return the JSON response
            header('Content-Type: application/json');
            echo json_encode(['investments' => $initiated_investments]);
            exit();
        }else{
            redirect('user/investments');
        }
    }

    /**
     * This method handles the AJAX request to fetch canceled investments
     *
     * @return void JSON response containing canceled investments
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

        /* Use User Model */
        $userModel = $this->model('User');

        if ($input->isAjax() && $input->get('page')) {
            $page = $input->get('page');
            $cancelled_investments = $userModel->cancelled_investments_limits($data['user']['userid'], $page);

            // Return the JSON response
            header('Content-Type: application/json');
            echo json_encode(['investments' => $cancelled_investments]);
            exit();
        }else{
            redirect('user/investments');
        }
    }
}
