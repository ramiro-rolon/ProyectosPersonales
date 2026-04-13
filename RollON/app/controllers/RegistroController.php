<?php
require_once __DIR__ . '/../models/UsuarioModel.php';

class RegistroController {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function showRegistro() {
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
            if ($_SESSION['user_rol'] === 'admin') {
                header('Location: /admin/dashboard');
            } else {
                header('Location: /cliente/cotizador');
            }
            exit;
        }
        require_once __DIR__ . '/../views/auth/registro.php';
    }

    public function registrar() {
        $nombre = trim($_POST['nombre'] ?? '');
        $apellido = trim($_POST['apellido'] ?? '');
        $contacto = trim($_POST['contacto'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        if (empty($nombre) || empty($apellido) || empty($email) || empty($password)) {
            $_SESSION['registro_error'] = 'Todos los campos son requeridos';
            header('Location: /auth/registro');
            exit;
        }

        if ($password !== $passwordConfirm) {
            $_SESSION['registro_error'] = 'Las contraseñas no coinciden';
            header('Location: /auth/registro');
            exit;
        }

        if (strlen($password) < 6) {
            $_SESSION['registro_error'] = 'La contraseña debe tener al menos 6 caracteres';
            header('Location: /auth/registro');
            exit;
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        try {
            $sql = "CALL sp_registrar_usuario(?, ?, ?, ?, ?, 'cliente', @id)";
            $this->db->query($sql, [$nombre, $apellido, $contacto, $email, $passwordHash]);
            $result = $this->db->fetchOne("SELECT @id AS id");
            
            if ($result && $result['id']) {
                $_SESSION['registro_success'] = 'Tu cuenta ha sido creada. Será revisada por la administración.';
                header('Location: /auth/login');
                exit;
            } else {
                $_SESSION['registro_error'] = 'El email ya está registrado';
                header('Location: /auth/registro');
                exit;
            }
        } catch (Exception $e) {
            $_SESSION['registro_error'] = 'Error al registrar: ' . $e->getMessage();
            header('Location: /auth/registro');
            exit;
        }
    }
}