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

$routes->group('inicio', function($routes) {
    $routes->get('getStats', 'InicioController::getStats');
    $routes->get('getWeeklyStats', 'InicioController::getWeeklyStats');
    $routes->get('getSpecialtyStats', 'InicioController::getSpecialtyStats');
    $routes->get('getNotifications', 'InicioController::getNotifications');
});

    //Pacientes
   $routes->group('pacientes', function($routes) {
    $routes->get('/', 'Pacientes::index');                  // Listado de pacientes
    $routes->get('crear', 'Pacientes::crear');              // Formulario para crear paciente
    $routes->post('add', 'Pacientes::add');                 // Acción para agregar paciente
    $routes->get('editar/(:num)', 'Pacientes::edit/$1');    // Formulario para editar paciente
    $routes->post('update/(:num)', 'Pacientes::update/$1'); // Acción para actualizar paciente
    $routes->post('delete/(:num)', 'Pacientes::delete/$1'); // Acción para eliminar paciente
    $routes->get('view/(:num)', 'Pacientes::view/$1');      // Ver detalles paciente (en inglés)
    $routes->get('ver/(:num)', 'Pacientes::view/$1');       // Ver detalles paciente (en español)
});

/// Cirujanos
$routes->group('cirujanos', function($routes) {
    $routes->get('/', 'Cirujanos::index');
    $routes->get('disponibles', 'Cirujanos::disponibles');
    $routes->get('crear', 'Cirujanos::crear');
    $routes->post('crear', 'Cirujanos::crear');
    $routes->get('editar/(:num)', 'Cirujanos::editar/$1');
    $routes->post('editar/(:num)', 'Cirujanos::editar/$1');
    $routes->get('ver/(:num)', 'Cirujanos::ver/$1');
    
    // Rutas para eliminación - Ambas apuntan al mismo método
    $routes->post('delete/(:num)', 'Cirujanos::delete/$1');          // Para el modal (POST)
    $routes->post('eliminar/(:num)', 'Cirujanos::eliminar/$1');      // Para compatibilidad (POST)
    $routes->get('eliminar/(:num)', 'Cirujanos::eliminar/$1');       // Para compatibilidad con GET (si es necesario)
});

// Instrumentista - CORREGIDO
$routes->group('instrumentistas', function($routes) {
    $routes->get('/', 'Instrumentistas::index');
    $routes->get('crear', 'Instrumentistas::crear');
    $routes->post('add', 'Instrumentistas::add');
    $routes->get('editar/(:num)', 'Instrumentistas::editar/$1');
    $routes->post('update/(:num)', 'Instrumentistas::update/$1');
    $routes->get('ver/(:num)', 'Instrumentistas::ver/$1');
    $routes->get('disponibles', 'Instrumentistas::disponibles');
    $routes->post('eliminar/(:num)', 'Instrumentistas::eliminar/$1');
    $routes->get('search', 'Instrumentistas::search');
    $routes->get('api/disponibles', 'Instrumentistas::getDisponibles');
});

$routes->group('usuarios', function($routes) {
    $routes->get('/', 'Usuarios::index');
    $routes->match(['get', 'post'], 'crear', 'Usuarios::crear');
    $routes->match(['get', 'post'], 'editar/(:num)', 'Usuarios::editar/$1');
    $routes->get('ver/(:num)', 'Usuarios::ver/$1');
     $routes->post('cambiarEstado/(:num)', 'Usuarios::cambiarEstado/$1');
    $routes->post('eliminar/(:num)', 'Usuarios::eliminar/$1');
});
$routes->group('enfermeros', function($routes) {
    $routes->get('/', 'Enfermeros::index');
    $routes->get('crear', 'Enfermeros::crear');
    $routes->post('add', 'Enfermeros::add');
    $routes->get('editar/(:num)', 'Enfermeros::editar/$1');
    $routes->post('update/(:num)', 'Enfermeros::update/$1');
    $routes->get('ver/(:num)', 'Enfermeros::ver/$1');
    $routes->get('disponibles', 'Enfermeros::disponibles');
    $routes->post('eliminar/(:num)', 'Enfermeros::eliminar/$1');
    $routes->get('search', 'Enfermeros::search');
    $routes->get('api/disponibles', 'Enfermeros::getDisponibles');
});
$routes->group('anestesistas', function($routes) {
    $routes->get('/', 'Anestesistas::index');
    $routes->get('crear', 'Anestesistas::crear');
    $routes->post('add', 'Anestesistas::add');
    $routes->get('editar/(:num)', 'Anestesistas::editar/$1');
    $routes->post('update/(:num)', 'Anestesistas::update/$1');
    $routes->get('ver/(:num)', 'Anestesistas::ver/$1');
    $routes->get('disponibles', 'Anestesistas::disponibles');
    $routes->post('eliminar/(:num)', 'Anestesistas::eliminar/$1');
    $routes->get('search', 'Anestesistas::search');
    $routes->get('api/disponibles', 'Anestesistas::getDisponibles');
});
$routes->group('turnos', function($routes) {
    $routes->get('/', 'Turnos::index');
    $routes->get('crear', 'Turnos::crear');
    $routes->post('guardar', 'Turnos::guardar');
    $routes->get('editar/(:num)', 'Turnos::editar/$1');
    $routes->post('actualizar/(:num)', 'Turnos::actualizar/$1');
    $routes->get('ver/(:num)', 'Turnos::ver/$1');
    $routes->get('cancelar/(:num)', 'Turnos::cancelar/$1');
    $routes->get('eliminar/(:num)', 'Turnos::eliminar/$1');
    $routes->get('hoy', 'Turnos::turnosHoy');
    $routes->get('proximos', 'Turnos::proximosTurnos');
    $routes->post('cambiar-estado', 'Turnos::cambiarEstado');
    $routes->get('get-procedimientos/(:num)', 'Turnos::getProcedimientos/$1');
    $routes->post('buscar-insumos', 'Turnos::buscarInsumos');
});
$routes->group('insumos', function($routes) {
    $routes->get('/', 'Insumos::index');                    // Listado de insumos
    $routes->get('crear', 'Insumos::crear');                // Formulario para crear insumo
    $routes->post('add', 'Insumos::add');                   // Acción para agregar insumo
    $routes->get('editar/(:num)', 'Insumos::edit/$1');      // Formulario para editar insumo
    $routes->post('update/(:num)', 'Insumos::update/$1');   // Acción para actualizar insumo
    $routes->get('delete/(:num)', 'Insumos::delete/$1');    // Acción para eliminar insumo (CAMBIADO)
    $routes->get('eliminar/(:num)', 'Insumos::eliminar/$1'); // Método alternativo
    $routes->get('view/(:num)', 'Insumos::view/$1');         // Ver detalles del insumo
    $routes->get('search', 'Insumos::search');              // Búsqueda de insumos
});