<?php
session_start();
require_once __DIR__ . '/../app/core/Database.php';

$db = Database::getInstance();

$password = 'Flora0612';
$hash = password_hash($password, PASSWORD_DEFAULT);

echo "Hash generado: " . $hash . "<br><br>";

$sql = "DELETE FROM Clientes WHERE email = ?";
$db->query($sql, ['danielrolon583@gmail.com']);

$sql = "INSERT INTO Clientes (nombre, apellido, contacto, email, password, rol, cuenta_activa) VALUES (?, ?, ?, ?, ?, ?, ?)";
$db->query($sql, ['Daniel', 'Rolon', '223586626', 'danielrolon583@gmail.com', $hash, 'admin', 'aprobado']);

echo "Usuario creado correctamente!<br>";
echo "Email: danielrolon583@gmail.com<br>";
echo "Password: Flora0612<br>";

$user = $db->fetchOne("SELECT id_cliente, nombre, email, rol, cuenta_activa FROM Clientes WHERE email = ?", ['danielrolon583@gmail.com']);
echo "<pre>";
print_r($user);
echo "</pre>";