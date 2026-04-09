<?php
require_once __DIR__ . '/../core/Database.php';

class AuthController {
    protected $db;
    private const ADMIN_USER = 'Daniel';
    private const ADMIN_PASS = 'Flora0612';

    public function __construct() {
        $this->db = Database::getInstance();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function showLogin() {
        if ($this->isAuthenticated()) {
            header('Location: /admin');
            exit;
        }
        require_once __DIR__ . '/../views/auth/login.php';
    }

    public function login() {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($username === self::ADMIN_USER && $password === self::ADMIN_PASS) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_user'] = $username;
            $_SESSION['login_time'] = time();
            header('Location: /admin');
            exit;
        }

        $_SESSION['login_error'] = 'Credenciales incorrectas';
        header('Location: /admin/login');
        exit;
    }

    public function logout() {
        session_destroy();
        header('Location: /');
        exit;
    }

    public function isAuthenticated() {
        return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
    }

    public function requireAuth() {
        if (!$this->isAuthenticated()) {
            header('Location: /admin/login');
            exit;
        }
    }
}
