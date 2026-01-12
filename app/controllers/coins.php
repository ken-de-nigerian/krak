<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\controllers;

use KenDeNigerian\Krak\core\Controller;

class coins extends Controller
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
    public function index(): array
    {
        // Initialize an empty data array
        $data = [];
        
        /*Use User Library*/
        $user = $this->library('User');
        $data['user'] = $user->data();
        $data['user_isloggedin'] = $user->isLoggedIn();

        // Use Models
        $userModel = $this->model('User');

        // Retrieve plans and time settings
        $data['plans'] = $userModel->plans();
        $data['times'] = $userModel->times();

        // get the referral settings
        $data['referral-settings'] = $userModel->referralSettings();

        return ['content' => $this->view->render($data, 'coins/buy-coins')];
    }
}
