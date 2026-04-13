<?php
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../models/UsuarioModel.php';

class AdminController extends AuthController {
    private $usuarioModel;
    
    public function __construct() {
        parent::__construct();
        $this->requireAuth();
        
        $user = $this->getUser();
        if ($user['rol'] !== 'admin') {
            header('Location: /cliente/cotizador');
            exit;
        }
        
        $this->usuarioModel = new UsuarioModel();
    }

    public function index() {
        $stats = [
            'pedidos' => $this->db->fetchOne("SELECT COUNT(*) as total FROM Pedidos")['total'],
            'telas' => $this->db->fetchOne("SELECT COUNT(*) as total FROM Telas")['total'],
            'dispositivos' => $this->db->fetchOne("SELECT COUNT(*) as total FROM Dispositivos")['total'],
            'extras' => $this->db->fetchOne("SELECT COUNT(*) as total FROM Extras")['total'],
            'solicitudes_pendientes' => $this->db->fetchOne("SELECT COUNT(*) as total FROM Clientes WHERE cuenta_activa = 'pendiente'")['total'],
        ];
        
        $pedidosRecientes = $this->db->fetchAll(
            "SELECT p.id_pedido, p.fecha_creacion, p.estado, p.total_pedido, 
                    CONCAT(c.nombre, ' ', c.apellido) as cliente_nombre
             FROM Pedidos p
             LEFT JOIN Clientes c ON p.id_cliente = c.id_cliente
             ORDER BY p.fecha_creacion DESC 
             LIMIT 10"
        );
        
        $solicitudesPendientes = $this->usuarioModel->getPendientes();
        
        require_once __DIR__ . '/../views/admin/dashboard.php';
    }

    public function solicitudes() {
        $solicitudes = $this->db->fetchAll(
            "SELECT id_cliente, nombre, apellido, email, contacto, fecha_registro, cuenta_activa 
             FROM Clientes 
             WHERE cuenta_activa = 'pendiente' 
             ORDER BY fecha_registro DESC"
        );
        
        require_once __DIR__ . '/../views/admin/solicitudes.php';
    }

    public function verDetalle($id = null) {
        $id = (int)($id ?? $_GET['id'] ?? 0);
        
        if ($id <= 0) {
            header('Location: /admin/dashboard');
            exit;
        }

        $detalle = $this->db->fetchAll("CALL sp_obtener_detalle_pedido(?)", [$id]);
        
        $pedido = $this->db->fetchOne("SELECT * FROM Pedidos WHERE id_pedido = ?", [$id]);
        
        $cliente = null;
        if ($pedido && isset($pedido['id_cliente'])) {
            $cliente = $this->db->fetchOne(
                "SELECT nombre, apellido, email, contacto FROM Clientes WHERE id_cliente = ?",
                [$pedido['id_cliente']]
            );
        }
        
        require_once __DIR__ . '/../views/admin/detalle_pedido.php';
    }

    public function aprobarSolicitud() {
        header('Content-Type: application/json');
        
        $id = (int)($_POST['id'] ?? 0);
        
        if ($id <= 0) {
            echo json_encode(['success' => false, 'error' => 'ID inválido']);
            return;
        }

        if ($this->usuarioModel->gestionarSolicitud($id, 'aprobado')) {
            echo json_encode(['success' => true, 'message' => 'Solicitud aprobada']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al aprobar']);
        }
    }

    public function rechazarSolicitud() {
        header('Content-Type: application/json');
        
        $id = (int)($_POST['id'] ?? 0);
        
        if ($id <= 0) {
            echo json_encode(['success' => false, 'error' => 'ID inválido']);
            return;
        }

        if ($this->usuarioModel->gestionarSolicitud($id, 'rechazado')) {
            echo json_encode(['success' => true, 'message' => 'Solicitud rechazada']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al rechazar']);
        }
    }

    public function procesarSolicitud() {
        $user = $this->getUser();
        
        if ($user['rol'] !== 'admin') {
            $_SESSION['error'] = 'No tenés permisos para realizar esta acción';
            header('Location: /admin/solicitudes');
            exit;
        }
        
        $idUsuario = (int)($_POST['id_usuario'] ?? 0);
        $accion = $_POST['accion'] ?? '';
        
        if ($idUsuario <= 0) {
            $_SESSION['error'] = 'ID de usuario inválido';
            header('Location: /admin/solicitudes');
            exit;
        }
        
        $estadosValidos = ['aprobado', 'rechazado'];
        if (!in_array($accion, $estadosValidos)) {
            $_SESSION['error'] = 'Acción inválida';
            header('Location: /admin/solicitudes');
            exit;
        }
        
        if ($this->usuarioModel->cambiarEstadoCuenta($idUsuario, $accion)) {
            $_SESSION['success'] = ($accion === 'aprobado') 
                ? 'Cliente aprobado correctamente' 
                : 'Cliente rechazado correctamente';
        } else {
            $_SESSION['error'] = 'Error al procesar la solicitud';
        }
        
        header('Location: /admin/solicitudes');
        exit;
    }

    public function resetearPassword() {
        header('Content-Type: application/json');
        
        $id = (int)($_POST['id'] ?? 0);
        
        if ($id <= 0) {
            echo json_encode(['success' => false, 'error' => 'ID inválido']);
            return;
        }

        $nuevaPassword = $this->usuarioModel->resetearPassword($id);
        
        if ($nuevaPassword) {
            echo json_encode([
                'success' => true, 
                'message' => 'Password reseteada a: ' . $nuevaPassword,
                'password' => $nuevaPassword
            ]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al resetear password']);
        }
    }
}
