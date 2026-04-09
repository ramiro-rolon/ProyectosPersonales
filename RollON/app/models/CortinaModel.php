<?php
require_once __DIR__ . '/Model.php';

class CortinaModel extends Model {

    public function calcularValor($idTela, $ancho, $largo) {
        return $this->db->callFunction('fn_calcular_valor_cortina', [$idTela, $ancho, $largo]);
    }

    public function validarFactibilidad($idTela, $ancho) {
        return $this->db->callFunction('fn_validar_factibilidad', [$idTela, $ancho]);
    }

    public function guardar($idPedido, $idTela, $ancho, $largo) {
        $sql = "CALL sp_cargar_cortina_a_pedido(?, ?, ?, ?)";
        $this->db->query($sql, [$idPedido, $idTela, $ancho, $largo]);
        return $this->db->lastInsertId();
    }
}
