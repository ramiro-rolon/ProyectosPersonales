<?php
require_once __DIR__ . '/../controllers/AuthController.php';

class AdminController extends AuthController {
    
    public function __construct() {
        parent::__construct();
        $this->requireAuth();
    }

    public function index() {
        $stats = [
            'pedidos' => $this->db->fetchOne("SELECT COUNT(*) as total FROM Pedidos")['total'],
            'telas' => $this->db->fetchOne("SELECT COUNT(*) as total FROM Telas")['total'],
            'dispositivos' => $this->db->fetchOne("SELECT COUNT(*) as total FROM Dispositivos")['total'],
            'extras' => $this->db->fetchOne("SELECT COUNT(*) as total FROM Extras")['total'],
        ];
        
        $pedidosRecientes = $this->db->fetchAll(
            "SELECT * FROM Pedidos ORDER BY fecha_creacion DESC LIMIT 5"
        );
        
        require_once __DIR__ . '/../views/admin/dashboard.php';
    }
}
