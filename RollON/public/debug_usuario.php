<?php
session_start();
require_once __DIR__ . '/../app/core/Database.php';

$db = Database::getInstance();

echo "<h3>Verificando usuario Daniel:</h3>";
$user = $db->fetchOne("SELECT id_cliente, nombre, email, rol, cuenta_activa FROM Clientes WHERE email = ?", ['danielrolon583@gmail.com']);

if ($user) {
    echo "<pre>";
    print_r($user);
    echo "</pre>";
    echo "<p><strong>Rol:</strong> " . $user['rol'] . "</p>";
    echo "<p><strong>Cuenta activa:</strong> " . $user['cuenta_activa'] . "</p>";
} else {
    echo "No se encontró el usuario";
}

echo "<hr>";

echo "<h3>Verificando SP sp_login_usuario:</h3>";
$sp = $db->fetchOne("SHOW CREATE PROCEDURE sp_login_usuario");
if ($sp) {
    echo "<pre>" . $sp['Create Procedure'] . "</pre>";
} else {
    echo "El SP no existe";
}

echo "<hr>";

echo "<h3>Todos los usuarios:</h3>";
$users = $db->fetchAll("SELECT id_cliente, nombre, email, rol, cuenta_activa FROM Clientes");
echo "<pre>";
print_r($users);
echo "</pre>";