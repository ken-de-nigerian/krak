<?php

declare(strict_types=1);

namespace KenDeNigerian\Krak\controllers;

use KenDeNigerian\Krak\core\Controller;

/**
 * Home Controller - Refactored to use services (SRP)
 */
class home extends Controller
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
     * Index - Refactored to use services
     */
    public function index(): array
    {
        // Initialize an empty data array
        $data = [];
        
        /*Use User Library*/
        $user = $this->library('User');
        $data['user'] = $user->data();
        $data['user_isloggedin'] = $user->isLoggedIn();

        // Use Services instead of direct model access (SRP)
        try {
            if ($this->container) {
                $planService = $this->service('PlanService');
                $data['plans'] = $planService->getAllPlans();
                
                // Still use model for methods not yet in services (backward compatible)
                $userModel = $this->model('User');
                $data['times'] = $userModel->times();
                $data['referral-settings'] = $userModel->referralSettings();
                $data['services'] = $userModel->services();
            } else {
                // Fallback to old way if container not available
                $userModel = $this->model('User');
                $data['plans'] = $userModel->plans();
                $data['times'] = $userModel->times();
                $data['referral-settings'] = $userModel->referralSettings();
                $data['services'] = $userModel->services();
            }
        } catch (\Exception $e) {
            // Fallback to old way on error
            $userModel = $this->model('User');
            $data['plans'] = $userModel->plans();
            $data['times'] = $userModel->times();
            $data['referral-settings'] = $userModel->referralSettings();
            $data['services'] = $userModel->services();
        }

        return ['content' => $this->view->render($data, 'home/homepage')];
    }
}
