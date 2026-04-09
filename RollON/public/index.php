<?php
session_start();

require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/core/Router.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/AdminController.php';
require_once __DIR__ . '/../app/controllers/AdminCrudController.php';
require_once __DIR__ . '/../app/controllers/CotizadorController.php';
require_once __DIR__ . '/../app/controllers/PedidoController.php';

$router = new Router();

$router->get('/', [CotizadorController::class, 'index']);

$router->get('/admin/login', [AuthController::class, 'showLogin']);
$router->post('/auth/login', [AuthController::class, 'login']);
$router->get('/auth/logout', [AuthController::class, 'logout']);

$router->get('/admin', [AdminController::class, 'index']);

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

$router->get('/cotizador', [CotizadorController::class, 'index']);
$router->post('/cotizador/calcular', [CotizadorController::class, 'calcular']);
$router->post('/cotizador/crearPedido', [CotizadorController::class, 'crearPedido']);

$router->dispatch();
