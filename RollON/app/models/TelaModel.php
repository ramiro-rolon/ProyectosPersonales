<?php
require_once __DIR__ . '/Model.php';

class TelaModel extends Model {
    public function getAll() {
        return $this->db->fetchAll("SELECT * FROM Telas ORDER BY nombre_tela");
    }

    public function getById($id) {
        return $this->db->fetchOne("SELECT * FROM Telas WHERE id_tela = ?", [$id]);
    }

    public function create($nombre, $precio, $anchoMax) {
        $sql = "INSERT INTO Telas (nombre_tela, precio_m2, ancho_maximo_tela) VALUES (?, ?, ?)";
        $this->db->query($sql, [$nombre, $precio, $anchoMax]);
        return $this->db->lastInsertId();
    }

    public function update($id, $nombre, $precio, $anchoMax) {
        $sql = "UPDATE Telas SET nombre_tela = ?, precio_m2 = ?, ancho_maximo_tela = ? WHERE id_tela = ?";
        $this->db->query($sql, [$nombre, $precio, $anchoMax, $id]);
    }

    public function delete($id) {
        $this->db->query("DELETE FROM Telas WHERE id_tela = ?", [$id]);
    }
}
