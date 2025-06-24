<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');

$routes->group('admin', static function($routes) {

    $routes->group('',[],static function($routes){
        $routes->get('home', 'AdminController::index',['as'=>'admin.home']);
    });
    $routes->group('',[],static function($routes){
        // $routes->view('example-auth', 'example-auth');
        $routes->get('login', 'AuthController::loginForm',['as'=>'admin.login.form']);
    });
});
