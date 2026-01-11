<?php

namespace Fir\Controllers;

class Fetch extends Controller
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
        redirect();
    }

    /**
     * routes
     */
    public function routes(): void
    {
        /* Use Input Library */
        $input = $this->library('Input');

        if ($input->isAjax()) {
            // Get the selected user ID from the AJAX request
            $selectedStatus = $_GET['status'];
            
            $response = [];

            if ($selectedStatus == "all") {
                $response = [
                    'status' => 'success',
                    'redirect' => 'user/investments'
                ];
            } elseif ($selectedStatus == "pending") {
                $response = [
                    'status' => 'success',
                    'redirect' => 'user/investments/pending'
                ];
            } elseif ($selectedStatus == "completed") {
                $response = [
                    'status' => 'success',
                    'redirect' => 'user/investments/completed'
                ];
            } elseif ($selectedStatus == "cancelled") {
                $response = [
                    'status' => 'success',
                    'redirect' => 'user/investments/cancelled'
                ];
            } elseif ($selectedStatus == "initiated") {
                $response = [
                    'status' => 'success',
                    'redirect' => 'user/investments/initiated'
                ];
            }

            // Send the JSON response
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }else{
            redirect('user/investments');
        }
    }

    /**
     * deposit_routes
     */
    public function deposit_routes(): void
    {
        /* Use Input Library */
        $input = $this->library('Input');

        if ($input->isAjax()) {
            // Get the selected user ID from the AJAX request
            $selectedStatus = $_GET['deposit'];
            $response = [];

            if ($selectedStatus == "all") {
                $response = [
                    'status' => 'success',
                    'redirect' => 'user/deposits'
                ];
            } elseif ($selectedStatus == "pending") {
                $response = [
                    'status' => 'success',
                    'redirect' => 'user/deposits/pending'
                ];
            } elseif ($selectedStatus == "completed") {
                $response = [
                    'status' => 'success',
                    'redirect' => 'user/deposits/completed'
                ];
            } elseif ($selectedStatus == "cancelled") {
                $response = [
                    'status' => 'success',
                    'redirect' => 'user/deposits/cancelled'
                ];
            } elseif ($selectedStatus == "initiated") {
                $response = [
                    'status' => 'success',
                    'redirect' => 'user/deposits/initiated'
                ];
            }

            // Send the JSON response
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }else{
            redirect('user/deposits');
        }
    }

    /**
     * withdrawal_routes
     */
    public function withdrawal_routes(): void
    {
        /* Use Input Library */
        $input = $this->library('Input');

        if ($input->isAjax()) {
            // Get the selected user ID from the AJAX request
            $selectedStatus = $_GET['withdrawal'];
            $response = [];

            if ($selectedStatus == "all") {
                $response = [
                    'status' => 'success',
                    'redirect' => 'user/payouts'
                ];
            } elseif ($selectedStatus == "pending") {
                $response = [
                    'status' => 'success',
                    'redirect' => 'user/payouts/pending'
                ];
            } elseif ($selectedStatus == "completed") {
                $response = [
                    'status' => 'success',
                    'redirect' => 'user/payouts/completed'
                ];
            } elseif ($selectedStatus == "cancelled") {
                $response = [
                    'status' => 'success',
                    'redirect' => 'user/payouts/cancelled'
                ];
            } elseif ($selectedStatus == "initiated") {
                $response = [
                    'status' => 'success',
                    'redirect' => 'user/payouts/initiated'
                ];
            }

            // Send the JSON response
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        }else{
            redirect('user/deposits');
        }
    }
}