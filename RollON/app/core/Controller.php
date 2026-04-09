<?php
class Controller {
    protected $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    protected function view($view, $data = [], $layout = 'main') {
        extract($data);
        ob_start();
        require_once "app/views/$view.php";
        $content = ob_get_clean();
        require_once "app/views/layouts/$layout.php";
    }

    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }

    protected function isAuthenticated() {
        return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
    }

    protected function requireAuth() {
        if (!$this->isAuthenticated()) {
            header('Location: ' . BASE_URL . '/admin/login');
            exit;
        }
    }

    protected function sanitize($data) {
        return htmlspecialchars(strip_tags(trim($data)));
    }
}
