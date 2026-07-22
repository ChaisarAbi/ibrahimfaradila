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
    $routes->get('dashboard/chart-data', 'Dashboard::chartData');
    
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
    
    // Reports
    $routes->get('reports', 'Reports::index');
    $routes->get('reports/certificate', 'Reports::certificate');
    $routes->get('reports/certificate/(:num)', 'Reports::certificate/$1');
    $routes->get('reports/invitation', 'Reports::invitation');
    $routes->get('reports/invitation/(:num)', 'Reports::invitation/$1');
    $routes->get('reports/detail-pemesanan', 'Reports::detailPemesanan');
    $routes->get('reports/detail-pemesanan/(:num)', 'Reports::detailPemesanan/$1');
    $routes->get('reports/order-report', 'Reports::orderReport');
    $routes->get('reports/order-report/(:any)', 'Reports::orderReport/$1');
    $routes->get('reports/order-report/pdf/(:num)', 'Reports::orderReportPdf/$1');
    $routes->get('reports/kitchen-sheet', 'Reports::kitchenSheet');
    $routes->get('reports/kitchen-sheet/(:any)', 'Reports::kitchenSheet/$1');
    
    // Kitchen (Admin & Dapur only)
    $routes->get('kitchen', 'Kitchen::index', ['filter' => 'dapur']);
    $routes->get('kitchen/(:any)', 'Kitchen::index/$1', ['filter' => 'dapur']);
    $routes->post('kitchen/update-status/(:num)', 'Kitchen::updateStatus/$1', ['filter' => 'dapur']);
    $routes->post('kitchen/update-stock', 'Kitchen::updateStock', ['filter' => 'dapur']);
    
    // Stock Management (All roles)
    $routes->get('stock', 'Stock::index');
    $routes->get('stock/create', 'Stock::create');
    $routes->post('stock/store', 'Stock::store');
    $routes->get('stock/edit/(:num)', 'Stock::edit/$1');
    $routes->post('stock/update/(:num)', 'Stock::update/$1');
    $routes->get('stock/delete/(:num)', 'Stock::delete/$1');
    
    // Scheduler (Admin & RPH only)
    $routes->get('scheduler/run', 'Scheduler::run', ['filter' => 'rph']);
    
    // Calendar (Admin & RPH only)
    $routes->get('calendar/events', 'Calendar::getEvents', ['filter' => 'rph']);
    
    // Notifications
    $routes->get('notifications/send-recap-today', 'Notification::sendTodayRecap');
    $routes->get('notifications/send-preview-tomorrow', 'Notification::sendTomorrowPreview');
    $routes->get('notifications/send-hplus1', 'Notification::sendHPlus1');
    $routes->get('notifications/send-stock-alert', 'Notification::sendStockAlert');
    $routes->post('notifications/send-custom', 'Notification::sendCustom');
    $routes->get('notifications/test', 'Notification::test');
    $routes->get('notifications/history', 'Notification::history');
    $routes->get('notifications/manual', 'Notification::manual');
    
    // Telegram Recipients Management
    $routes->get('notifications/recipients', 'Notification::recipients');
    $routes->post('notifications/add-recipient', 'Notification::addRecipient');
    $routes->get('notifications/delete-recipient/(:num)', 'Notification::deleteRecipient/$1');
});
