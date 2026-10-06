<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// ---------------------------------------------------------------------
// Public pages — activity picker and one page per activity
// ---------------------------------------------------------------------
$routes->get('/', 'Home::index');
$routes->get('tfa(:num)', 'Activities::show/$1');

// ---------------------------------------------------------------------
// TFA4 — Authentication (public)
// ---------------------------------------------------------------------
$routes->get('login', 'Auth::login');            // login form
$routes->post('login', 'Auth::attempt');         // verify username + password_verify()
$routes->post('logout', 'Auth::logout');         // destroy session → /login

// ---------------------------------------------------------------------
// Protected pages — the "auth" filter (App\Filters\AuthFilter) runs first
// on every route in this group, including the new and edit forms.
// ---------------------------------------------------------------------
$routes->group('', ['filter' => 'auth'], static function (RouteCollection $routes): void {
    // Customer Accounts
    $routes->get('customers', 'Customers::index');
    $routes->get('customers/new', 'Customers::new');
    $routes->post('customers', 'Customers::create');
    $routes->get('customers/(:num)/edit', 'Customers::edit/$1');
    $routes->post('customers/(:num)/update', 'Customers::update/$1');

    // User Accounts
    $routes->get('users', 'Users::index');
    $routes->get('users/new', 'Users::new');
    $routes->post('users', 'Users::create');
    $routes->get('users/(:num)/edit', 'Users::edit/$1');
    $routes->post('users/(:num)/update', 'Users::update/$1');
});
