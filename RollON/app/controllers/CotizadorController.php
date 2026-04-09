<?php
require_once __DIR__ . '/../models/CortinaModel.php';
require_once __DIR__ . '/../models/TelaModel.php';
require_once __DIR__ . '/../models/DispositivoModel.php';
require_once __DIR__ . '/../models/ExtraModel.php';
require_once __DIR__ . '/../models/PedidoModel.php';

class CotizadorController {
    private $cortinaModel;
    private $telaModel;
    private $dispositivoModel;
    private $extraModel;
    private $pedidoModel;

    public function __construct() {
        $this->cortinaModel = new CortinaModel();
        $this->telaModel = new TelaModel();
        $this->dispositivoModel = new DispositivoModel();
        $this->extraModel = new ExtraModel();
        $this->pedidoModel = new PedidoModel();
    }

    public function index() {
        $telas = $this->telaModel->getAll();
        $extras = $this->extraModel->getAll();
        require_once __DIR__ . '/../views/cotizador/index.php';
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
            echo json_encode([
                'error' => $validacion,
                'valido' => false
            ]);
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
        
        $nombreCliente = trim($_POST['nombre_cliente'] ?? '');
        $cortinas = json_decode($_POST['cortinas'] ?? '[]', true);
        $extras = json_decode($_POST['extras'] ?? '[]', true);

        if (empty($nombreCliente) || empty($cortinas)) {
            echo json_encode(['error' => 'Datos incompletos']);
            return;
        }

        try {
            $idPedido = $this->pedidoModel->crear($nombreCliente);

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
