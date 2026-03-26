<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Super Admin Routes
$routes->group('super-admin', static function ($routes) {
    $routes->get('login', 'SuperAdminController::login');
    $routes->post('login', 'SuperAdminController::processLogin');
    $routes->get('logout', 'SuperAdminController::logout');
    
    $routes->get('dashboard', 'SuperAdminController::dashboard');
    $routes->get('companies', 'SuperAdminController::companies');
    $routes->post('companies/update-status/(:num)', 'SuperAdminController::updateCompanyStatus/$1');
});


// Company Auth & Dashboard Routes
$routes->group('company', static function ($routes) {
    // Auth
    $routes->get('login', 'CompanyAuthController::login');
    $routes->post('login', 'CompanyAuthController::processLogin');
    $routes->get('register', 'CompanyAuthController::register');
    $routes->post('register', 'CompanyAuthController::processRegister');
    $routes->get('logout', 'CompanyAuthController::logout');
    
    // Dashboard & Profile
    $routes->get('dashboard', 'CompanyDashboardController::dashboard');
    $routes->get('profile', 'CompanyDashboardController::profile');
    $routes->post('profile/update', 'CompanyDashboardController::updateProfile');

    // Project Types
    $routes->get('project-types', 'ProjectTypeController::index');
    $routes->get('project-types/create', 'ProjectTypeController::create');
    $routes->post('project-types/store', 'ProjectTypeController::store');
    $routes->get('project-types/edit/(:num)', 'ProjectTypeController::edit/$1');
    $routes->post('project-types/update/(:num)', 'ProjectTypeController::update/$1');
    $routes->get('project-types/delete/(:num)', 'ProjectTypeController::delete/$1');
    
    // Dynamic Fields for Project Types
    $routes->get('project-types/fields/(:num)', 'ProjectTypeController::fields/$1');
    $routes->post('project-types/fields/(:num)', 'ProjectTypeController::storeField/$1');
    $routes->get('project-types/fields/(:num)/delete/(:num)', 'ProjectTypeController::deleteField/$1/$2');

    // Projects
    $routes->get('projects', 'ProjectController::index');
    $routes->get('projects/create', 'ProjectController::create');
    $routes->post('projects/store', 'ProjectController::store');
    $routes->get('projects/view/(:num)', 'ProjectController::view/$1');
    $routes->get('projects/edit/(:num)', 'ProjectController::edit/$1');
    $routes->post('projects/update/(:num)', 'ProjectController::update/$1');
    $routes->get('projects/delete/(:num)', 'ProjectController::delete/$1');
    
    // Ajax route for dynamic fields
    $routes->get('projects/get-fields/(:num)', 'ProjectController::getDynamicFields/$1');

    // Apartment Units
    $routes->post('projects/units/store/(:num)', 'ProjectController::storeUnit/$1');
    $routes->get('projects/units/delete/(:num)', 'ProjectController::deleteUnit/$1');
    $routes->get('projects/units/delete-image/(:num)', 'ProjectController::deleteUnitImage/$1');

    // Bookings
    $routes->get('bookings', 'BookingController::index');
    $routes->post('bookings/update-status/(:num)', 'BookingController::updateStatus/$1');
    $routes->get('bookings/delete/(:num)', 'BookingController::delete/$1');
    $routes->post('bookings/create', 'BookingController::create'); // Manual insert
});
