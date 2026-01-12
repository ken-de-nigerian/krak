<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\controllers;

use KenDeNigerian\Krak\core\Controller;
class maintenance extends Controller
{
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

        // Use the Maintenance model to check if the site is in maintenance mode
        $maintenanceModel = $this->model('Maintenance');
        $data['maintenance'] = $maintenanceModel->underMaintenance();

        // if the maintenance isn't active, redirect to home
        if ($data['maintenance']['maintenance_mode'] == 2) {
            redirect();
        }

        return ['content' => $this->view->render($data, 'auth/maintenance')];
    }
}
