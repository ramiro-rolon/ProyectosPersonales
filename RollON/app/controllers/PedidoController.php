<?php
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../models/PedidoModel.php';
require_once __DIR__ . '/../controllers/AuthController.php';

class PedidoController extends AuthController {
    private $pedidoModel;

    public function __construct() {
        parent::__construct();
        $this->requireAuth();
        $this->pedidoModel = new PedidoModel();
    }

    public function index() {
        $filtro = [
            'estado' => $_GET['estado'] ?? 'Todos',
            'buscar' => $_GET['buscar'] ?? ''
        ];
        
        $pedidos = $this->pedidoModel->getAll($filtro);
        
        foreach ($pedidos as &$pedido) {
            $detalles = $this->pedidoModel->getDetalles($pedido['id_pedido']);
            $pedido['cantidad_cortinas'] = count($detalles['cortinas']);
        }
        
        require_once __DIR__ . '/../views/admin/pedidos.php';
    }

    public function listar() {
        header('Content-Type: application/json');
        
        $filtro = [
            'estado' => $_GET['estado'] ?? 'Todos',
            'buscar' => $_GET['buscar'] ?? ''
        ];
        
        $pedidos = $this->pedidoModel->getAllConDetalles($filtro);
        echo json_encode($pedidos);
    }

    public function ver($id = null) {
        $id = (int)($id ?? $_GET['id'] ?? 0);
        if ($id <= 0) {
            header('Location: /admin/pedidos');
            exit;
        }
        
        $pedido = $this->pedidoModel->getById($id);
        if (!$pedido) {
            header('Location: /admin/pedidos');
            exit;
        }
        
        $detalles = $this->pedidoModel->getDetalles($id);
        
        $cliente = null;
        if (!empty($detalles['cortinas'])) {
            $primeraCortina = $detalles['cortinas'][0];
            $cliente = [
                'nombre' => $primeraCortina['nombre_tela'] ?? 'Cliente'
            ];
        }
        
        require_once __DIR__ . '/../views/admin/pedido_detalle.php';
    }

    public function actualizarEstado() {
        header('Content-Type: application/json');
        
        $id = (int)($_POST['id'] ?? 0);
        $estado = trim($_POST['estado'] ?? '');
        
        $estadosValidos = ['Presupuesto', 'En Produccion', 'Listo', 'Entregado', 'Cancelado'];
        
        if ($id <= 0 || !in_array($estado, $estadosValidos)) {
            echo json_encode(['success' => false, 'error' => 'Datos inválidos']);
            return;
        }

        try {
            $this->pedidoModel->updateEstado($id, $estado);
            echo json_encode(['success' => true, 'message' => 'Estado actualizado']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function eliminar() {
        header('Content-Type: application/json');
        
        $id = (int)($_POST['id'] ?? 0);
        
        if ($id <= 0) {
            echo json_encode(['success' => false, 'error' => 'ID inválido']);
            return;
        }

        try {
            $this->pedidoModel->delete($id);
            echo json_encode(['success' => true, 'message' => 'Pedido eliminado']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}