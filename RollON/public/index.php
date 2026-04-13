<?php
session_start();

require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/core/Router.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/RegistroController.php';
require_once __DIR__ . '/../app/controllers/AdminController.php';
require_once __DIR__ . '/../app/controllers/AdminCrudController.php';
require_once __DIR__ . '/../app/controllers/CotizadorController.php';
require_once __DIR__ . '/../app/controllers/PedidoController.php';
require_once __DIR__ . '/../app/controllers/ClienteController.php';

function isPublicRoute($uri) {
    $publicRoutes = [
        '/auth/login',
        '/auth/logout',
        '/auth/registro',
        '/auth/registrar'
    ];
    
    foreach ($publicRoutes as $route) {
        if (strpos($uri, $route) === 0) {
            return true;
        }
    }
    return false;
}

if (!isPublicRoute($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] !== '/') {
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        header('Location: /auth/login');
        exit;
    }
}

$router = new Router();

$router->get('/auth/login', [AuthController::class, 'showLogin']);
$router->post('/auth/login', [AuthController::class, 'login']);
$router->get('/auth/logout', [AuthController::class, 'logout']);
$router->get('/auth/registro', [RegistroController::class, 'showRegistro']);
$router->post('/auth/registrar', [RegistroController::class, 'registrar']);

$router->get('/admin/dashboard', [AdminController::class, 'index']);
$router->get('/admin/solicitudes', [AdminController::class, 'solicitudes']);
$router->get('/admin/detalle/{id}', [AdminController::class, 'verDetalle']);
$router->post('/admin/aprobar-solicitud', [AdminController::class, 'aprobarSolicitud']);
$router->post('/admin/rechazar-solicitud', [AdminController::class, 'rechazarSolicitud']);
$router->post('/admin/procesar-solicitud', [AdminController::class, 'procesarSolicitud']);
$router->post('/admin/resetear-password', [AdminController::class, 'resetearPassword']);
$router->get('/admin', function() {
    header('Location: /admin/dashboard');
    exit;
});

$router->get('/admin/telas', [AdminCrudController::class, 'telas']);
$router->post('/admin-crud/saveTela', [AdminCrudController::class, 'saveTela']);
$router->post('/admin-crud/deleteTela', [AdminCrudController::class, 'deleteTela']);

$router->get('/admin/dispositivos', [AdminCrudController::class, 'dispositivos']);
$router->post('/admin-crud/saveDispositivo', [AdminCrudController::class, 'saveDispositivo']);
$router->post('/admin-crud/deleteDispositivo', [AdminCrudController::class, 'deleteDispositivo']);

$router->get('/admin/extras', [AdminCrudController::class, 'extras']);
$router->post('/admin-crud/saveExtra', [AdminCrudController::class, 'saveExtra']);
$router->post('/admin-crud/deleteExtra', [AdminCrudController::class, 'deleteExtra']);

$router->get('/admin/pedidos', [PedidoController::class, 'index']);
$router->get('/admin/pedidos/ver/{id}', [PedidoController::class, 'ver']);
$router->get('/pedido/listar', [PedidoController::class, 'listar']);
$router->post('/pedido/actualizarEstado', [PedidoController::class, 'actualizarEstado']);
$router->post('/pedido/eliminar', [PedidoController::class, 'eliminar']);

$router->get('/cliente/cotizador', [ClienteController::class, 'cotizador']);
$router->get('/cliente/pedidos', [ClienteController::class, 'misPedidos']);
$router->get('/cliente/pedidos/ver/{id}', [ClienteController::class, 'verPedido']);
$router->post('/cliente/calcular', [ClienteController::class, 'calcular']);
$router->post('/cliente/crearPedido', [ClienteController::class, 'crearPedido']);

$router->get('/', [AuthController::class, 'showLogin']);

$router->dispatch();