<?php
class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        $host = 'localhost';
        $dbname = 'cotizador_cortinas';
        $user = 'root';
        $pass = '';
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
        
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }

    public function query($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function fetchAll($sql, $params = []) {
        return $this->query($sql, $params)->fetchAll();
    }

    public function fetchOne($sql, $params = []) {
        return $this->query($sql, $params)->fetch();
    }

    public function callProcedure($procedure, $params = []) {
        $placeholders = implode(',', array_fill(0, count($params), '?'));
        $sql = "CALL $procedure($placeholders)";
        return $this->fetchAll($sql, $params);
    }

    public function callFunction($function, $params = []) {
        $placeholders = implode(',', array_fill(0, count($params), '?'));
        $sql = "SELECT $function($placeholders) AS result";
        $result = $this->fetchOne($sql, $params);
        return $result['result'] ?? null;
    }

    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }
}
