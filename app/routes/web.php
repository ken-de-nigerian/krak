<?php

use KenDeNigerian\Krak\core\Router;

/**
 * Web Routes
 * Define all web routes here
 */

return function (Router $router) {
    // Home routes
    $router->get('/', 'Home@index');
    $router->get('/home', 'Home@index');
    
    // Public routes
    $router->get('/about', 'About@index');
    $router->get('/services', 'Services@index');
    $router->get('/faqs', 'Faqs@index');
    $router->get('/policy', 'Policy@index');
    $router->get('/listings', 'Listings@index');
    $router->get('/coins', 'Coins@index');
    
    // Auth routes (no middleware for login/register)
    $router->group(['prefix' => '', 'middleware' => []], function ($router) {
        $router->get('/login', 'Login@index');
        $router->post('/login', 'Login@authenticate');
        $router->get('/register', 'Register@index');
        $router->post('/register', 'Register@create');
        $router->get('/forgot', 'Forgot@index');
        $router->post('/forgot', 'Forgot@send');
        $router->get('/reset', 'Reset@index');
        $router->post('/reset', 'Reset@update');
        $router->get('/twofa', 'Twofa@index');
        $router->post('/twofa', 'Twofa@verify');
        $router->get('/google', 'Google@index');
        $router->get('/meta', 'Meta@index');
    });

    // User routes (require authentication)
    $router->group(['prefix' => 'user', 'middleware' => ['Auth']], function ($router) {
        $router->get('/dashboard', 'User@dashboard');
        $router->get('/profile', 'User@profile');
        $router->get('/transactions', 'User@transactions');
        $router->get('/plans', 'User@plans');
        $router->get('/schemes', 'User@schemes');
        $router->get('/investments', 'User@investments');
        $router->get('/deposits', 'User@deposits');
        $router->get('/payouts', 'User@payouts');
        $router->get('/wallets', 'User@wallets');
        $router->get('/requests', 'User@requests');
        $router->get('/referrals', 'User@referrals');
        $router->get('/ranking', 'User@ranking');
    });

    // Payment routes
    $router->group(['prefix' => '', 'middleware' => ['CsrfToken']], function ($router) {
        $router->post('/payment', 'Payment@index');
        $router->get('/payment', 'Payment@index');
        $router->post('/investment', 'Investment@index');
        $router->get('/investment', 'Investment@index');
        $router->post('/payout', 'Payout@index');
        $router->get('/payout', 'Payout@index');
    });

    // Admin routes (require admin authentication)
    $router->group(['prefix' => 'admin', 'middleware' => ['AdminAuth']], function ($router) {
        $router->get('/dashboard', 'Admin@dashboard');
        $router->get('/users', 'Admin@users');
        $router->get('/settings', 'Admin@settings');
        $router->get('/reports', 'Admin@reports');
    });

    // Admin login (no auth required)
    $router->get('/admin/login', 'Admin@login');
    $router->post('/admin/login', 'Admin@authenticate');

    // System routes
    $router->get('/maintenance', 'Maintenance@index');
    $router->get('/blocked', 'Blocked@index');
    $router->get('/cron', 'Cron@index');
    $router->get('/initiated', 'Initiated@index');
    $router->get('/fetch', 'Fetch@index');
    $router->get('/requests', 'Requests@index');
};
