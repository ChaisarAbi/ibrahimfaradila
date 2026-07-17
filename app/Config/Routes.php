<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Auth routes
$routes->get('/', 'Auth::index');
$routes->get('/login', 'Auth::index');
$routes->post('/auth/login', 'Auth::login');
$routes->get('/logout', 'Auth::logout');

// Admin group with auth filter
$routes->group('admin', ['filter' => 'auth'], function($routes) {
    // Dashboard
    $routes->get('dashboard', 'Dashboard::index');
    
    // Customers
    $routes->get('customers', 'Customers::index');
    $routes->get('customers/create', 'Customers::create');
    $routes->post('customers/store', 'Customers::store');
    $routes->get('customers/edit/(:num)', 'Customers::edit/$1');
    $routes->post('customers/update/(:num)', 'Customers::update/$1');
    $routes->get('customers/delete/(:num)', 'Customers::delete/$1');
    
    // Orders
    $routes->get('orders', 'Orders::index');
    $routes->get('orders/create', 'Orders::create');
    $routes->post('orders/store', 'Orders::store');
    $routes->get('orders/edit/(:num)', 'Orders::edit/$1');
    $routes->post('orders/update/(:num)', 'Orders::update/$1');
    $routes->get('orders/delete/(:num)', 'Orders::delete/$1');
    $routes->get('orders/getPackageInfo/(:num)', 'Orders::getPackageInfo/$1');
    $routes->get('orders/stats', 'Orders::stats');
    $routes->get('orders/pending-count', 'Orders::pendingCount');
    
    // Packages
    $routes->get('packages', 'Packages::index');
    $routes->get('packages/create', 'Packages::create');
    $routes->post('packages/store', 'Packages::store');
    $routes->get('packages/edit/(:num)', 'Packages::edit/$1');
    $routes->post('packages/update/(:num)', 'Packages::update/$1');
    $routes->get('packages/delete/(:num)', 'Packages::delete/$1');
    
    // Scheduler
    $routes->get('scheduler/run', 'Scheduler::run');
    
    // Calendar
    $routes->get('calendar/events', 'Calendar::getEvents');
    
    // Reports
    $routes->get('reports/certificate/(:num)', 'Reports::certificate/$1');
    $routes->get('reports/invitation/(:num)', 'Reports::invitation/$1');
    $routes->get('reports/order-report/(:any)', 'Reports::orderReport/$1');
    $routes->get('reports/kitchen-sheet', 'Reports::kitchenSheet');
    $routes->get('reports/kitchen-sheet/(:any)', 'Reports::kitchenSheet/$1');
    
    // Kitchen
    $routes->get('kitchen', 'Kitchen::index');
    $routes->get('kitchen/(:any)', 'Kitchen::index/$1');
    $routes->post('kitchen/update-status/(:num)', 'Kitchen::updateStatus/$1');
    $routes->post('kitchen/update-stock', 'Kitchen::updateStock');
    
    // Stock Management
    $routes->get('stock', 'Stock::index');
    $routes->get('stock/create', 'Stock::create');
    $routes->post('stock/store', 'Stock::store');
    $routes->get('stock/edit/(:num)', 'Stock::edit/$1');
    $routes->post('stock/update/(:num)', 'Stock::update/$1');
    $routes->get('stock/delete/(:num)', 'Stock::delete/$1');
    
    // Notifications
    $routes->get('notifications/send-reminders', 'Notification::send24hReminders');
    $routes->get('notifications/send-recap', 'Notification::sendDailyRecap');
    $routes->get('notifications/history', 'Notification::history');
});