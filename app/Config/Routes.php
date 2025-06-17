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

// Grupo de rutas para turnos quirúrgicos - NUEVO
$routes->group('turnos', function($routes) {
    $routes->get('/', 'Turnos::index');                             // Listado de turnos
    $routes->get('crear', 'Turnos::crear');                         // Formulario para crear turno
    $routes->post('crear', 'Turnos::crear');                        // Procesar creación de turno
    $routes->get('editar/(:num)', 'Turnos::editar/$1');            // Formulario para editar turno
    $routes->post('editar/(:num)', 'Turnos::editar/$1');           // Procesar edición de turno
    $routes->get('ver/(:num)', 'Turnos::ver/$1');                  // Ver detalles del turno
    $routes->get('eliminar/(:num)', 'Turnos::eliminar/$1');        // Cancelar turno (cambiar estado)
    $routes->get('eliminarDefinitivo/(:num)', 'Turnos::eliminarDefinitivo/$1'); // Eliminar definitivamente
    
    // Rutas AJAX
    $routes->post('cambiarEstado', 'Turnos::cambiarEstado');        // Cambiar estado del turno (AJAX)
    $routes->get('getProcedimientos/(:num)', 'Turnos::getProcedimientos/$1'); // Obtener procedimientos por especialidad
    $routes->post('buscarInsumos', 'Turnos::buscarInsumos');        // Buscar insumos (AJAX)
});

// Grupo de rutas para insumos
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

// Pacientes
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

// Cirujanos
$routes->group('cirujanos', function($routes) {
    $routes->get('/', 'Cirujanos::index');
    $routes->get('disponibles', 'Cirujanos::disponibles');
    $routes->get('crear', 'Cirujanos::crear');
    $routes->post('crear', 'Cirujanos::crear');
    $routes->get('editar/(:num)', 'Cirujanos::editar/$1');
    $routes->post('editar/(:num)', 'Cirujanos::editar/$1');
    $routes->get('ver/(:num)', 'Cirujanos::ver/$1');
    $routes->get('eliminar/(:num)', 'Cirujanos::eliminar/$1');
});

// Instrumentista
$routes->group('instrumentista', function($routes) {
    $routes->get('/', 'Instrumentista::index');                      // Listado de instrumentistas
    $routes->get('crear', 'Instrumentista::crear');                  // Formulario para crear instrumentista
    $routes->post('guardar', 'Instrumentista::guardar');             // Acción para guardar instrumentista
    $routes->get('editar/(:num)', 'Instrumentista::editar/$1');      // Formulario para editar instrumentista
    $routes->post('actualizar/(:num)', 'Instrumentista::actualizar/$1'); // Acción para actualizar instrumentista
    $routes->get('eliminar/(:num)', 'Instrumentista::eliminar/$1');  // Acción para eliminar instrumentista
    $routes->get('ver/(:num)', 'Instrumentista::ver/$1');            // Ver detalles del instrumentista (opcional)
});

// Usuarios
$routes->group('usuarios', function($routes) {
    $routes->get('/', 'Usuarios::index');                    // Listar usuarios
    $routes->get('crear', 'Usuarios::crear');                // Mostrar formulario crear
    $routes->post('add', 'Usuarios::add');                   // Procesar creación
    $routes->get('edit/(:num)', 'Usuarios::edit/$1');        // Mostrar formulario editar
    $routes->post('update/(:num)', 'Usuarios::update/$1');   // Procesar actualización
    $routes->get('view/(:num)', 'Usuarios::view/$1');        // Ver detalles
    $routes->get('delete/(:num)', 'Usuarios::delete/$1');    // Eliminar usuario
    $routes->get('toggle/(:num)', 'Usuarios::toggle_status/$1'); // Activar/Desactivar
});

// Grupo de rutas para turnos con autenticación
$routes->group('turnos', ['filter' => 'auth'], static function ($routes) {
    // Ruta principal - lista de turnos
    $routes->get('/', 'Turnos::index');
    
    // Rutas para crear turno
    $routes->get('crear', 'Turnos::crear');
    $routes->post('crear', 'Turnos::crear');
    
    // Rutas para editar turno
    $routes->get('editar/(:num)', 'Turnos::editar/$1');
    $routes->post('editar/(:num)', 'Turnos::editar/$1');
    
    // Ruta para ver detalles del turno
    $routes->get('ver/(:num)', 'Turnos::ver/$1');
    
    // Rutas para eliminar
    $routes->get('eliminar/(:num)', 'Turnos::eliminar/$1');
    $routes->post('eliminar/(:num)', 'Turnos::eliminar/$1');
    $routes->delete('eliminar-definitivo/(:num)', 'Turnos::eliminarDefinitivo/$1');
    
    // Rutas AJAX
    $routes->post('cambiar-estado', 'Turnos::cambiarEstado');
    $routes->get('get-procedimientos/(:num)', 'Turnos::getProcedimientos/$1');
    $routes->post('buscar-insumos', 'Turnos::buscarInsumos');
    $routes->post('verificar-disponibilidad', 'Turnos::verificarDisponibilidad'); // Esta ruta falta en tu controlador
    
    // Rutas para manejo de cirugías
    $routes->post('iniciar-cirugia/(:num)', 'Turnos::iniciarCirugia/$1');
    $routes->post('finalizar-cirugia/(:num)', 'Turnos::finalizarCirugia/$1');
    
    // Rutas adicionales
    $routes->get('calendario', 'Turnos::calendario');
    $routes->get('reportes', 'Turnos::reportes');
});

// Rutas existentes para reportes
$routes->group('reportes', ['filter' => 'auth'], static function ($routes) {
    $routes->get('/', 'Reportes::index');
    $routes->get('turnos', 'Reportes::turnos');
    $routes->get('insumos', 'Reportes::insumos');
    $routes->get('estadisticas', 'Reportes::estadisticas');
    $routes->get('crear', 'Reportes::crear');
});