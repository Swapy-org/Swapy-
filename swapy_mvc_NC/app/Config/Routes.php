<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */


/*
|--------------------------------------------------------------------------
| INICIO
|--------------------------------------------------------------------------
*/

$routes->view('/', 'index');
$routes->get('informacion/(:segment)', 'InformacionController::pagina/$1');


/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

$routes->match(['get', 'post'], 'auth', 'AuthController::index');
$routes->post('auth/login', 'AuthController::login');
$routes->get('auth/logout', 'AuthController::logout');
$routes->post('auth/registrar', 'AuthController::registrar');
$routes->post('auth/verificarCodigo', 'AuthController::verificarCodigo');
$routes->post('auth/reenviarCodigo', 'AuthController::reenviarCodigo');


/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

$routes->get('admin', 'AdminController::index');
$routes->post('admin', 'AdminController::index');
$routes->post('admin/crear', 'AdminController::index/crear');
$routes->post('admin/editar', 'AdminController::index/editar');
$routes->get('admin/cambiar_estado', 'AdminController::index/cambiar_estado');
$routes->get('admin/eliminar', 'AdminController::index/eliminar');
$routes->get('admin_solicitudes', 'AdminController::solicitudes');
$routes->post('admin/responder_pregunta', 'AdminController::responderPregunta');
$routes->post('admin/responder_denuncia', 'AdminController::responderDenuncia');


/*
|--------------------------------------------------------------------------
| EMPLEADO
|--------------------------------------------------------------------------
*/

$routes->get('empleado', 'EmpleadoController::index');
$routes->post('empleado/login', 'EmpleadoController::login');
$routes->get('empleado/dashboard', 'EmpleadoController::dashboard');
$routes->get('empleado/logout', 'EmpleadoController::logout');
$routes->post('empleado/cambiarEstado', 'EmpleadoController::cambiarEstado');
$routes->post('empleado/intercambio/estado', 'EmpleadoController::actualizarEstadoIntercambio');


/*
|--------------------------------------------------------------------------
| VISTAS
|--------------------------------------------------------------------------
*/

$routes->view('spempleados', 'spempleados');
$routes->view('index2', 'index2');
$routes->get('indexx', static function () {
	return service('response')
		->setContentType('text/html')
		->setBody((string) file_get_contents(APPPATH . 'Views/indexx.html'));
});
$routes->view('verificar', 'verificar');


/*
|--------------------------------------------------------------------------
| PERFIL
|--------------------------------------------------------------------------
*/

$routes->get('perfil', 'Perfil::index');
$routes->get('editarperfil', 'Perfil::editar');
$routes->post('editarperfil', 'Perfil::actualizar');
$routes->get('perfil/editar', 'Perfil::editar');
$routes->post('perfil/actualizar', 'Perfil::actualizar');


/*
|--------------------------------------------------------------------------
| OTROS
|--------------------------------------------------------------------------
*/

$routes->view('chat', 'chat');
$routes->view('admin_premium', 'admin_premium');
$routes->view('admin_denuncias', 'admin_denuncias');
$routes->view('admin_preguntas', 'admin_preguntas');


/*
|--------------------------------------------------------------------------
| CLIENTE
|--------------------------------------------------------------------------
*/

$routes->get('cliente', 'ClienteController::index');


/*
|--------------------------------------------------------------------------
| API PERFIL
|--------------------------------------------------------------------------
*/

$routes->get('api/perfil/(:num)', 'Api\PerfilApi::mostrar/$1');
$routes->post('api/perfil/(:num)', 'Api\PerfilApi::actualizar/$1');
$routes->put('api/perfil/(:num)', 'Api\PerfilApi::actualizar/$1');


/*
|--------------------------------------------------------------------------
| PRODUCTOS
|--------------------------------------------------------------------------
*/

$routes->match(['get', 'post'], 'producto', 'ProductoController::index');


/*
|--------------------------------------------------------------------------
| REPORTES
|--------------------------------------------------------------------------
*/

$routes->get('reportes', 'ReporteController::index');
$routes->get('reportes/imprimir', 'ReporteController::imprimir');


/*
|--------------------------------------------------------------------------
| CHAT IA
|--------------------------------------------------------------------------
*/

$routes->post('chat-ia/responder', 'ChatIaController::responder');
$routes->post('conversaciones/abrir', 'ConversacionController::abrir');
$routes->get('conversaciones', 'ConversacionController::listar');
$routes->get('conversaciones/(:num)/mensajes', 'ConversacionController::mensajes/$1');
$routes->post('conversaciones/finalizar', 'ConversacionController::finalizar');
$routes->post('conversaciones/solicitud-entrega', 'ConversacionController::solicitudEntrega');
$routes->post('intercambio/confirmar', 'IntercambioController::confirmar');
$routes->post('intercambio/completar', 'IntercambioController::completar');
$routes->get('intercambio/estado/(:num)', 'IntercambioController::estado/$1');


/*
|--------------------------------------------------------------------------
| SOPORTE
|--------------------------------------------------------------------------
*/

$routes->post('soporte/enviar', 'SoporteController::enviar');
$routes->post('soporte/enviarPropuesta', 'SoporteController::enviarPropuesta');
$routes->post('soporte/actualizarEstadoSolicitud', 'SoporteController::actualizarEstadoSolicitud');