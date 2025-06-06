<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'AuthController::login');

// Rutas de autenticación
$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::login');
$routes->get('logout', 'AuthController::logout');

// Rutas de inicio/dashboard
$routes->get('inicio', 'InicioController::index');
$routes->get('dashboard', 'InicioController::index'); // Alias para inicio
$routes->get('home', 'AuthController::home'); // Tu método home del AuthController

// Ruta AJAX para obtener estadísticas del dashboard
$routes->get('inicio/getStats', 'InicioController::getStats');

// Grupo de rutas para insumos
$routes->group('insumos', function($routes) {
    $routes->get('/', 'Insumos::index');
    $routes->get('insumos/crear', 'Insumos::crear');
    $routes->post('insumos/add', 'Insumos::add');
    $routes->get('edit/(:num)', 'Insumos::edit/$1');
    $routes->post('update/(:num)', 'Insumos::update/$1');
    $routes->get('delete/(:num)', 'Insumos::delete/$1');
    $routes->get('view/(:num)', 'Insumos::view/$1');
    $routes->get('search', 'Insumos::search');
});