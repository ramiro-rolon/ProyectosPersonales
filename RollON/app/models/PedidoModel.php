<?php
require_once __DIR__ . '/Model.php';

class PedidoModel extends Model {
    public function crear($idCliente) {
        $sql = "CALL sp_crear_pedido(?, @id)";
        $this->db->query($sql, [$idCliente]);
        $result = $this->db->fetchOne("SELECT @id AS id");
        return $result['id'];
    }

    public function agregarCortina($idPedido, $idTela, $ancho, $largo) {
        $sql = "CALL sp_cargar_cortina_a_pedido(?, ?, ?, ?)";
        $this->db->query($sql, [$idPedido, $idTela, $ancho, $largo]);
    }

    public function agregarExtra($idPedido, $idExtra, $cantidad = 1) {
        $sql = "CALL sp_cargar_extra_a_pedido(?, ?, ?)";
        $this->db->query($sql, [$idPedido, $idExtra, $cantidad]);
    }

    public function getTotal($idPedido) {
        return $this->db->callFunction('fn_total_pedido_con_extras', [$idPedido]);
    }

    public function getById($id) {
        return $this->db->fetchOne("SELECT * FROM Pedidos WHERE id_pedido = ?", [$id]);
    }

    public function getByCliente($idCliente) {
        return $this->db->fetchAll(
            "SELECT * FROM Pedidos WHERE id_cliente = ? ORDER BY fecha_creacion DESC",
            [$idCliente]
        );
    }

    public function getAll($filtro = []) {
        $sql = "SELECT id_pedido, fecha_creacion, estado, total_pedido, 
                    GROUP_CONCAT(CONCAT(cliente_nombre, ' ', cliente_apellido) SEPARATOR ', ') as cliente_nombre
             FROM vista_detalle_presupuesto 
             WHERE 1=1";
        $params = [];

        if (!empty($filtro['estado']) && $filtro['estado'] !== 'Todos') {
            $sql .= " AND estado = ?";
            $params[] = $filtro['estado'];
        }

        if (!empty($filtro['buscar'])) {
            $sql .= " AND (cliente_nombre LIKE ? OR cliente_apellido LIKE ? OR id_pedido = ?)";
            $params[] = '%' . $filtro['buscar'] . '%';
            $params[] = '%' . $filtro['buscar'] . '%';
            $params[] = (int)$filtro['buscar'];
        }

        $sql .= " GROUP BY id_pedido, fecha_creacion, estado, total_pedido";
        $sql .= " ORDER BY fecha_creacion DESC";

        return $this->db->fetchAll($sql, $params);
    }

    public function getAllConDetalles($filtro = []) {
        $pedidos = $this->getAll($filtro);
        
        foreach ($pedidos as &$pedido) {
            $detalles = $this->getDetalles($pedido['id_pedido']);
            $pedido['cantidad_cortinas'] = count($detalles['cortinas']);
        }
        
        return $pedidos;
    }

    public function getDetalles($idPedido) {
        $cortinas = $this->db->fetchAll(
            "SELECT c.*, t.nombre_tela, d.nombre_dispositivo 
             FROM Cortinas c 
             JOIN Telas t ON c.id_tela = t.id_tela 
             JOIN Dispositivos d ON c.id_dispositivo = d.id_dispositivo 
             WHERE c.id_pedido = ?",
            [$idPedido]
        );

        $extras = $this->db->fetchAll(
            "SELECT pe.*, e.descripcion 
             FROM Pedido_Extras pe 
             JOIN Extras e ON pe.id_extra = e.id_extra 
             WHERE pe.id_pedido = ?",
            [$idPedido]
        );

        return ['cortinas' => $cortinas, 'extras' => $extras];
    }

    public function updateEstado($id, $estado) {
        $sql = "UPDATE Pedidos SET estado = ? WHERE id_pedido = ?";
        $this->db->query($sql, [$estado, $id]);
    }

    public function delete($id) {
        $sql = "DELETE FROM Pedidos WHERE id_pedido = ?";
        $this->db->query($sql, [$id]);
    }
}
