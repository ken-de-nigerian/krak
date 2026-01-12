<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\controllers;

use KenDeNigerian\Krak\core\Controller;

class blocked extends Controller
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
     * This method handles the index page for blocked users.
     *
     * @return array
     */
    public function index(): array
    {
        /**
         * The $data array stores all the data passed to the views
         */
        $data = [];

        // unset the sessions and redirect
        $session = $this->library('Session');
        $session->delete('access_token');
        
        /* Use User Library */
        $user = $this->library('User');
        if ($user->isLoggedIn() && $user->data()['status'] == 2) {
            // The User is logged in, and an account is active, continue with code
            $data['user'] = $user->data();
        } elseif (!$user->isLoggedIn()) {
            // User is not logged in, redirect to homepage
            redirect();
        } else {
            // User is logged in, but an account is blocked, redirect to homepage
            redirect();
        }

        return ['content' => $this->view->render($data, 'auth/blocked')];
    }
}
