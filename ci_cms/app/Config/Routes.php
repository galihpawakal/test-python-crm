<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');

$routes->group('users', function($routes) {
    $routes->get('/', 'Users::index');
    $routes->get('create', 'Users::create');
    $routes->post('store', 'Users::store');
    $routes->get('edit/(:num)', 'Users::edit/$1');
    $routes->post('update/(:num)', 'Users::update/$1');
    $routes->get('delete/(:num)', 'Users::delete/$1');
});

$routes->group('products', function($routes) {
    $routes->get('/', 'Products::index');
    $routes->get('create', 'Products::create');
    $routes->post('store', 'Products::store');
    $routes->get('edit/(:num)', 'Products::edit/$1');
    $routes->post('update/(:num)', 'Products::update/$1');
    $routes->get('delete/(:num)', 'Products::delete/$1');
});

$routes->group('transactions', function($routes) {
    $routes->get('/', 'Transactions::index');
    $routes->get('create', 'Transactions::create');
    $routes->get('show/(:num)', 'Transactions::show/$1');
    $routes->post('store', 'Transactions::store');
    $routes->get('processing/(:num)', 'Transactions::processing/$1');
    $routes->get('payment/(:num)', 'Transactions::payment/$1');
    $routes->post('confirmPayment/(:num)', 'Transactions::confirmPayment/$1');
    $routes->get('result/(:num)', 'Transactions::result/$1');
    $routes->get('receipt/(:num)', 'Transactions::receipt/$1');
});
