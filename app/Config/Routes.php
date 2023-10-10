<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
//Auth route
$routes->get('/', 'Home::index');

$routes->get('/auth/register', 'Home::register');

$routes->post('/auth/register', 'Auth::register');

$routes->post('/auth/login', 'Auth::login');

//Employee Dashboard
$routes->get('/dashboard', 'EmployeeController::index');

$routes->get('edit_employee/(:num)', 'EmployeeController::editEmployee/$1');

$routes->post('update_employee/(:num)', 'EmployeeController::updateEmployee/$1');

$routes->post('delete_employee/(:num)', 'EmployeeController::deleteEmployee/$1');

//export employee 
$routes->get('employeeController/export', 'EmployeeController::export');

//create employee with count 50
$routes->match(['get', 'post'], 'employeeController/importCsvToDb', 'EmployeeController::importCsvToDb');

//modify employee with excel
$routes->post('employeeController/modifyDatabaseWithExcel', 'EmployeeController::modifyDatabaseWithExcel');



