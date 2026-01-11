<?php

namespace Fir\Controllers;

class Initiated extends Controller 
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

        $depositsProcessed = $cronModel->initiated();

        // Check if any deposits were processed
        if ($depositsProcessed > 0) {
            $response = [
                'status' => 'error',
                'message' => 'Cron job executed successfully.'
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'No deposits were processed.'
            ];
        }

        // Send the JSON response
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
}