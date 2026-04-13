<?php
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../models/TelaModel.php';
require_once __DIR__ . '/../models/DispositivoModel.php';
require_once __DIR__ . '/../models/ExtraModel.php';

class AdminCrudController extends AuthController {
    private $telaModel;
    private $dispositivoModel;
    private $extraModel;

    public function __construct() {
        parent::__construct();
        $this->requireAuth();
        
        $user = $this->getUser();
        if ($user['rol'] !== 'admin') {
            header('Location: /cliente/cotizador');
            exit;
        }
        
        $this->telaModel = new TelaModel();
        $this->dispositivoModel = new DispositivoModel();
        $this->extraModel = new ExtraModel();
    }

    public function telas() {
        $telas = $this->telaModel->getAll();
        require_once __DIR__ . '/../views/admin/telas.php';
    }

    public function saveTela() {
        header('Content-Type: application/json');
        
        $id = $_POST['id'] ?? null;
        $nombre = trim($_POST['nombre'] ?? '');
        $precio = (float)($_POST['precio'] ?? 0);
        $anchoMax = (float)($_POST['ancho_max'] ?? 3.00);

        if (empty($nombre) || $precio <= 0) {
            echo json_encode(['success' => false, 'error' => 'Datos inválidos']);
            return;
        }

        try {
            if ($id) {
                $this->telaModel->update($id, $nombre, $precio, $anchoMax);
                echo json_encode(['success' => true, 'message' => 'Tela actualizada']);
            } else {
                $this->telaModel->create($nombre, $precio, $anchoMax);
                echo json_encode(['success' => true, 'message' => 'Tela creada']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function deleteTela() {
        header('Content-Type: application/json');
        
        $id = (int)($_POST['id'] ?? 0);
        
        if ($id <= 0) {
            echo json_encode(['success' => false, 'error' => 'ID inválido']);
            return;
        }

        try {
            $this->telaModel->delete($id);
            echo json_encode(['success' => true, 'message' => 'Tela eliminada']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => 'No se puede eliminar. Verificar dependencias.']);
        }
    }

    public function dispositivos() {
        $dispositivos = $this->dispositivoModel->getAll();
        require_once __DIR__ . '/../views/admin/dispositivos.php';
    }

    public function saveDispositivo() {
        header('Content-Type: application/json');
        
        $id = $_POST['id'] ?? null;
        $nombre = trim($_POST['nombre'] ?? '');
        $anchoMin = (float)($_POST['ancho_min'] ?? 0);
        $anchoMax = (float)($_POST['ancho_max'] ?? 0);
        $precio = (float)($_POST['precio'] ?? 0);

        if (empty($nombre) || $precio <= 0 || $anchoMin <= 0 || $anchoMax <= 0 || $anchoMin >= $anchoMax) {
            echo json_encode(['success' => false, 'error' => 'Datos inválidos']);
            return;
        }

        require_once __DIR__ . '/../models/DispositivoModel.php';
        $model = new DispositivoModel();

        try {
            if ($id) {
                $model->update($id, $nombre, $anchoMin, $anchoMax, $precio);
                echo json_encode(['success' => true, 'message' => 'Dispositivo actualizado']);
            } else {
                $model->create($nombre, $anchoMin, $anchoMax, $precio);
                echo json_encode(['success' => true, 'message' => 'Dispositivo creado']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function deleteDispositivo() {
        header('Content-Type: application/json');
        
        $id = (int)($_POST['id'] ?? 0);
        
        if ($id <= 0) {
            echo json_encode(['success' => false, 'error' => 'ID inválido']);
            return;
        }

        try {
            require_once __DIR__ . '/../models/DispositivoModel.php';
            $model = new DispositivoModel();
            $model->delete($id);
            echo json_encode(['success' => true, 'message' => 'Dispositivo eliminado']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => 'No se puede eliminar. Verificar dependencias.']);
        }
    }

    public function extras() {
        $extras = $this->extraModel->getAll();
        require_once __DIR__ . '/../views/admin/extras.php';
    }

    public function saveExtra() {
        header('Content-Type: application/json');
        
        $id = $_POST['id'] ?? null;
        $descripcion = trim($_POST['descripcion'] ?? '');
        $precio = (float)($_POST['precio'] ?? 0);

        if (empty($descripcion) || $precio <= 0) {
            echo json_encode(['success' => false, 'error' => 'Datos inválidos']);
            return;
        }

        require_once __DIR__ . '/../models/ExtraModel.php';
        $model = new ExtraModel();

        try {
            if ($id) {
                $model->update($id, $descripcion, $precio);
                echo json_encode(['success' => true, 'message' => 'Extra actualizado']);
            } else {
                $model->create($descripcion, $precio);
                echo json_encode(['success' => true, 'message' => 'Extra creado']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function deleteExtra() {
        header('Content-Type: application/json');
        
        $id = (int)($_POST['id'] ?? 0);
        
        if ($id <= 0) {
            echo json_encode(['success' => false, 'error' => 'ID inválido']);
            return;
        }

        try {
            require_once __DIR__ . '/../models/ExtraModel.php';
            $model = new ExtraModel();
            $model->delete($id);
            echo json_encode(['success' => true, 'message' => 'Extra eliminado']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => 'No se puede eliminar. Verificar dependencias.']);
        }
    }
}
