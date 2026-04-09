<?php
require_once __DIR__ . '/Model.php';

class ExtraModel extends Model {
    public function getAll() {
        return $this->db->fetchAll("SELECT * FROM Extras ORDER BY descripcion");
    }

    public function getById($id) {
        return $this->db->fetchOne("SELECT * FROM Extras WHERE id_extra = ?", [$id]);
    }

    public function create($descripcion, $precio) {
        $sql = "INSERT INTO Extras (descripcion, precio_fijo) VALUES (?, ?)";
        $this->db->query($sql, [$descripcion, $precio]);
        return $this->db->lastInsertId();
    }

    public function update($id, $descripcion, $precio) {
        $sql = "UPDATE Extras SET descripcion = ?, precio_fijo = ? WHERE id_extra = ?";
        $this->db->query($sql, [$descripcion, $precio, $id]);
    }

    public function delete($id) {
        $this->db->query("DELETE FROM Extras WHERE id_extra = ?", [$id]);
    }
}
