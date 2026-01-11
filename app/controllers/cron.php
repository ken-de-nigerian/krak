<?php

namespace Fir\Controllers;

class Cron extends Controller 
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

	public function index(): void
    {

        // unset the sessions and redirect
        $session = $this->library('Session');
        $session->delete('access_token');

        /* Use Cron Model */
        $cronModel = $this->model('Cron');

        $investmentsProcessed = $cronModel->cron();

        // Check if any investments were processed
        if ($investmentsProcessed > 0) {
            $response = [
                'status' => 'error',
                'message' => 'Cron job executed successfully.'
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'No investments were processed.'
            ];
        }

        // Send the JSON response
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
}