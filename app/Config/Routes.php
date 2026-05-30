<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'HomeController::index');
$routes->get('auth', 'AuthController::index');
$routes->post('auth', 'AuthController::login');
$routes->get('logout', 'AuthController::logout');
$routes->post('logout', 'AuthController::logout');


// Owner & Admin
$routes->group('', ['filter' => 'auth:owner,admin'], function ($routes) {
    $routes->get('owner-dashboard',        'OwnerController::index');
    $routes->get('owner-dashboard-data',   'OwnerController::dashboard_data');
    $routes->get('owner-revenue-data',     'OwnerController::revenue_data');
    $routes->get('owner-products-data',    'OwnerController::products_data');
    $routes->get('owner-customers-data',   'OwnerController::customers_data');
    $routes->get('owner-staff-data',       'OwnerController::staff_data');
    $routes->get('owner-financial-data',   'OwnerController::financial_data');
    $routes->get('owner-inventory-data',   'OwnerController::inventory_data');
    $routes->get('owner-performance-data', 'OwnerController::performance_data');
    $routes->get('owner-settings-data',    'OwnerController::settings_data');
    $routes->post('owner-settings-data',   'OwnerController::settings_update');
});

// Admin Only
$routes->group('', ['filter' => 'auth:admin'], function ($routes) {
    $routes->get('admin-dashboard',      'AdminController::index');
    $routes->get('admin-products',       'AdminController::products');
    $routes->get('admin-stock-logs',     'AdminController::stock_logs');
    $routes->get('admin-orders',         'AdminController::orders');
    $routes->get('admin-transactions',   'AdminController::transactions');
    $routes->get('admin-reports-data',   'AdminController::reports_data');
    $routes->get('admin-dashboard-data', 'AdminController::dashboard_data');
    $routes->get('admin-categories',     'AdminController::categories');

    $routes->get('admin-payments',           'AdminController::payments');
    $routes->post('admin-payments',          'AdminController::payment_add');
    $routes->put('admin-payment/(:num)',     'AdminController::payment_update/$1');
    $routes->delete('admin-payment/(:num)', 'AdminController::payment_delete/$1');

    $routes->get('admin-accounts',           'AdminController::accounts');
    $routes->post('admin-accounts',          'AdminController::account_add');
    $routes->put('admin-account/(:num)',     'AdminController::account_update/$1');
    $routes->delete('admin-account/(:num)', 'AdminController::account_delete/$1');

    $routes->post('admin-product/add',       'AdminController::product_add');
    $routes->put('admin-product/(:num)',     'AdminController::product_update/$1');
    $routes->delete('admin-product/(:num)', 'AdminController::product_delete/$1');

    $routes->get('admin-roles', 'AdminController::roles');
});

// Kasir & Admin
$routes->group('', ['filter' => 'auth:kasir,admin'], function ($routes) {
    $routes->get('kasir-dashboard',      'KasirController::index');
    $routes->get('kasir-products',       'KasirController::products');
    $routes->get('kasir-payments',       'KasirController::payments');
    $routes->get('kasir-transactions',   'KasirController::transactions');
    $routes->get('kasir-reports-data',   'KasirController::reports_data');
    $routes->get('kasir-settings-data',  'KasirController::settings_data');
    $routes->post('kasir-settings-data', 'KasirController::settings_update');
    $routes->post('kasir-checkout',      'KasirController::checkout');
});

// Kasir product CRUD — ikut group kasir,admin juga
$routes->group('', ['filter' => 'auth:kasir,admin'], function ($routes) {
    $routes->post('kasir-product/add',              'KasirController::product_add');
    $routes->post('kasir-product/(:num)',            'KasirController::product_update/$1');
    $routes->delete('kasir-product/(:num)',         'KasirController::product_delete/$1');
    $routes->post('kasir-product/(:num)/toggle',    'KasirController::product_toggle/$1');
    $routes->get('kasir-categories',                'KasirController::categories');
});