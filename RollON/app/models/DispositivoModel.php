<?php
require_once __DIR__ . '/Model.php';

class DispositivoModel extends Model {
    public function getAll() {
        return $this->db->fetchAll("SELECT * FROM Dispositivos ORDER BY ancho_minimo");
    }

    public function getById($id) {
        return $this->db->fetchOne("SELECT * FROM Dispositivos WHERE id_dispositivo = ?", [$id]);
    }

    public function getForAncho($ancho) {
        return $this->db->fetchOne(
            "SELECT * FROM Dispositivos WHERE ? >= ancho_minimo AND ? <= ancho_maximo LIMIT 1",
            [$ancho, $ancho]
        );
    }

    public function create($nombre, $anchoMin, $anchoMax, $precio) {
        $sql = "INSERT INTO Dispositivos (nombre_dispositivo, ancho_minimo, ancho_maximo, precio_dispositivo) VALUES (?, ?, ?, ?)";
        $this->db->query($sql, [$nombre, $anchoMin, $anchoMax, $precio]);
        return $this->db->lastInsertId();
    }

    public function update($id, $nombre, $anchoMin, $anchoMax, $precio) {
        $sql = "UPDATE Dispositivos SET nombre_dispositivo = ?, ancho_minimo = ?, ancho_maximo = ?, precio_dispositivo = ? WHERE id_dispositivo = ?";
        $this->db->query($sql, [$nombre, $anchoMin, $anchoMax, $precio, $id]);
    }

    public function delete($id) {
        $this->db->query("DELETE FROM Dispositivos WHERE id_dispositivo = ?", [$id]);
    }
}
