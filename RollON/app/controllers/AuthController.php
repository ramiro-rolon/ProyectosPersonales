<?php
require_once __DIR__ . '/../core/Database.php';

class AuthController {
    protected $db;

    public function __construct() {
        $this->db = Database::getInstance();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function showLogin() {
        if ($this->isAuthenticated()) {
            $this->redirectByRole();
        }
        require_once __DIR__ . '/../views/auth/login.php';
    }

    public function login() {
        $email = strtolower(trim($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $_SESSION['login_error'] = 'Email y contraseña son requeridos';
            header('Location: /auth/login');
            exit;
        }

        require_once __DIR__ . '/../models/UsuarioModel.php';
        $usuarioModel = new UsuarioModel();
        
        try {
            $result = $usuarioModel->login($email, $password);
        } catch (Exception $e) {
            $_SESSION['login_error'] = $e->getMessage();
            header('Location: /auth/login');
            exit;
        }

        if (isset($result['error'])) {
            $_SESSION['login_error'] = $result['error'];
            header('Location: /auth/login');
            exit;
        }

        if ($result) {
            session_regenerate_id(true);
            
            $_SESSION['id_cliente'] = $result['id_cliente'];
            $_SESSION['nombre'] = $result['nombre'];
            $_SESSION['apellido'] = $result['apellido'];
            $_SESSION['email'] = $result['email'];
            $_SESSION['rol'] = $result['rol'];
            $_SESSION['cuenta_activa'] = $result['cuenta_activa'];
            $_SESSION['logged_in'] = true;
            $_SESSION['login_time'] = time();
            $_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'] ?? '';

            $this->redirectByRole();
        } else {
            $_SESSION['login_error'] = 'Credenciales incorrectas';
            header('Location: /auth/login');
            exit;
        }
    }

    public function logout() {
        if (isset($_SESSION['email'])) {
            $key = 'login_attempts_' . md5($_SESSION['email']);
            unset($_SESSION[$key]);
        }
        session_unset();
        session_destroy();
        header('Location: /auth/login');
        exit;
    }

    public function isAuthenticated() {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }

    public function getUser() {
        if (!$this->isAuthenticated()) {
            return null;
        }
        return [
            'id' => $_SESSION['id_cliente'] ?? null,
            'nombre' => $_SESSION['nombre'] ?? '',
            'apellido' => $_SESSION['apellido'] ?? '',
            'email' => $_SESSION['email'] ?? '',
            'rol' => $_SESSION['rol'] ?? ''
        ];
    }

    public function requireAuth() {
        if (!$this->isAuthenticated()) {
            header('Location: /auth/login');
            exit;
        }
    }

    public function requireRole($roles) {
        $this->requireAuth();
        $user = $this->getUser();
        if (!in_array($user['rol'], (array)$roles)) {
            $this->redirectByRole();
        }
    }

    private function redirectByRole() {
        $user = $this->getUser();
        if ($user['rol'] === 'admin') {
            header('Location: /admin/dashboard');
        } else {
            header('Location: /cliente/cotizador');
        }
        exit;
    }
}

function requireAuth() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        header('Location: /auth/login');
        exit;
    }
}

function requireRole($roles) {
    requireAuth();
    
    if (!isset($_SESSION['rol']) || !in_array($_SESSION['rol'], (array)$roles)) {
        $rol = $_SESSION['rol'] ?? '';
        if ($rol === 'admin') {
            header('Location: /admin/dashboard');
        } else {
            header('Location: /cliente/cotizador');
        }
        exit;
    }
}

function isLoggedIn() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    return [
        'id' => $_SESSION['id_cliente'] ?? null,
        'nombre' => $_SESSION['nombre'] ?? '',
        'apellido' => $_SESSION['apellido'] ?? '',
        'email' => $_SESSION['email'] ?? '',
        'rol' => $_SESSION['rol'] ?? ''
    ];
}