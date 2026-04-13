<?php
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../models/TelaModel.php';
require_once __DIR__ . '/../models/ExtraModel.php';
require_once __DIR__ . '/../models/PedidoModel.php';
require_once __DIR__ . '/../models/CortinaModel.php';
require_once __DIR__ . '/../models/DispositivoModel.php';

class ClienteController extends AuthController {
    private $telaModel;
    private $extraModel;
    private $pedidoModel;
    private $cortinaModel;
    private $dispositivoModel;

    public function __construct() {
        parent::__construct();
        $this->requireAuth();
        
        $this->telaModel = new TelaModel();
        $this->extraModel = new ExtraModel();
        $this->pedidoModel = new PedidoModel();
        $this->cortinaModel = new CortinaModel();
        $this->dispositivoModel = new DispositivoModel();
    }

    public function cotizador() {
        $user = $this->getUser();
        $telas = $this->telaModel->getAll();
        $extras = $this->extraModel->getAll();
        require_once __DIR__ . '/../views/cliente/cotizador.php';
    }

    public function misPedidos() {
        $user = $this->getUser();
        $pedidos = $this->pedidoModel->getByCliente($user['id']);
        $pedidoModel = $this->pedidoModel;
        require_once __DIR__ . '/../views/cliente/pedidos.php';
    }

    public function verPedido($id = null) {
        $user = $this->getUser();
        $id = (int)($id ?? $_GET['id'] ?? 0);
        
        $pedido = $this->pedidoModel->getById($id);
        
        if (!$pedido || $pedido['id_cliente'] != $user['id']) {
            header('Location: /cliente/pedidos');
            exit;
        }
        
        $detalles = $this->pedidoModel->getDetalles($id);
        require_once __DIR__ . '/../views/cliente/pedido_detalle.php';
    }

    public function calcular() {
        header('Content-Type: application/json');
        
        $idTela = (int)($_POST['id_tela'] ?? 0);
        $ancho = (float)($_POST['ancho'] ?? 0);
        $largo = (float)($_POST['largo'] ?? 0);

        if ($idTela <= 0 || $ancho <= 0 || $largo <= 0) {
            echo json_encode(['error' => 'Datos inválidos']);
            return;
        }

        $validacion = $this->cortinaModel->validarFactibilidad($idTela, $ancho);
        
        if ($validacion !== 'OK') {
            echo json_encode(['error' => $validacion, 'valido' => false]);
            return;
        }

        $valor = $this->cortinaModel->calcularValor($idTela, $ancho, $largo);
        $dispositivo = $this->dispositivoModel->getForAncho($ancho);

        echo json_encode([
            'valido' => true,
            'valor' => (float)$valor,
            'dispositivo' => $dispositivo ? $dispositivo['nombre_dispositivo'] : 'N/A'
        ]);
    }

    public function crearPedido() {
        header('Content-Type: application/json');
        
        $user = $this->getUser();
        $cortinas = json_decode($_POST['cortinas'] ?? '[]', true);
        $extras = json_decode($_POST['extras'] ?? '[]', true);

        if (empty($cortinas)) {
            echo json_encode(['error' => 'No hay cortinas para guardar']);
            return;
        }

        try {
            $idPedido = $this->pedidoModel->crear($user['id']);

            foreach ($cortinas as $cortina) {
                $this->pedidoModel->agregarCortina(
                    $idPedido,
                    (int)$cortina['id_tela'],
                    (float)$cortina['ancho'],
                    (float)$cortina['largo']
                );
            }

            foreach ($extras as $extra) {
                $this->pedidoModel->agregarExtra(
                    $idPedido,
                    (int)$extra['id_extra'],
                    (int)$extra['cantidad']
                );
            }

            $total = $this->pedidoModel->getTotal($idPedido);

            echo json_encode([
                'success' => true,
                'id_pedido' => $idPedido,
                'total' => (float)$total
            ]);
        } catch (Exception $e) {
            echo json_encode(['error' => 'Error al crear el pedido: ' . $e->getMessage()]);
        }
    }
}