<?php
require_once __DIR__ . '/Model.php';

class UsuarioModel extends Model {

    private const MAX_LOGIN_ATTEMPTS = 5;
    private const LOCKOUT_TIME = 900; // 15 minutos

    public function login($email, $password) {
        $email = strtolower(trim($email));
        
        $this->checkRateLimit($email);
        
        // Consulta directa en lugar de SP (más confiable)
        $result = $this->db->fetchAll(
            "SELECT id_cliente, nombre, apellido, email, password, rol, cuenta_activa 
             FROM Clientes 
             WHERE LOWER(email) = ? 
             LIMIT 1",
            [$email]
        );
        
        if (empty($result)) {
            $this->recordFailedAttempt($email);
            return ['error' => 'No existe el email ' . $email . ' en el sistema'];
        }
        
        $usuario = $result[0];
        
        if (!password_verify($password, $usuario['password'])) {
            $this->recordFailedAttempt($email);
            return ['error' => 'La contraseña no coincide'];
        }
        
        $cuentaActiva = $usuario['cuenta_activa'] ?? 'pendiente';
        $rol = strtolower($usuario['rol'] ?? 'cliente');
        
        if ($rol !== 'admin') {
            if ($cuentaActiva === 'pendiente') {
                return ['error' => 'Su cuenta está en revisión por Daniel'];
            }
            
            if ($cuentaActiva === 'rechazado') {
                return ['error' => 'Tu cuenta ha sido rechazada. Comunicate con la administración.'];
            }
        }

        $this->clearFailedAttempts($email);
        
        return [
            'id_cliente' => $usuario['id_cliente'],
            'nombre' => $usuario['nombre'],
            'apellido' => $usuario['apellido'],
            'email' => $usuario['email'],
            'rol' => $rol,
            'cuenta_activa' => $cuentaActiva
        ];
    }

    private function checkRateLimit($email) {
        $key = 'login_attempts_' . md5($email);
        
        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = ['attempts' => 0, 'locked_until' => null];
        }
        
        $attempts = $_SESSION[$key];
        
        if ($attempts['locked_until'] && time() < $attempts['locked_until']) {
            $remaining = $attempts['locked_until'] - time();
            throw new Exception('Too many login attempts. Try again in ' . ceil($remaining / 60) . ' minutes.');
        }
        
        if ($attempts['attempts'] >= self::MAX_LOGIN_ATTEMPTS) {
            $_SESSION[$key]['locked_until'] = time() + self::LOCKOUT_TIME;
            throw new Exception('Too many login attempts. Your account is locked for 15 minutes.');
        }
    }

    private function recordFailedAttempt($email) {
        $key = 'login_attempts_' . md5($email);
        
        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = ['attempts' => 0, 'locked_until' => null];
        }
        
        $_SESSION[$key]['attempts']++;
    }

    private function clearFailedAttempts($email) {
        $key = 'login_attempts_' . md5($email);
        unset($_SESSION[$key]);
    }

    public function getById($id) {
        return $this->db->fetchOne("SELECT id_cliente, nombre, apellido, email, rol FROM Clientes WHERE id_cliente = ?", [$id]);
    }

    public function getAll() {
        return $this->db->fetchAll("SELECT id_cliente, nombre, apellido, email, contacto, rol FROM Clientes ORDER BY apellido, nombre");
    }

    public function create($data) {
        if (strlen($data['password']) < 8) {
            throw new Exception('La contraseña debe tener al menos 8 caracteres');
        }
        
        $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);
        $rol = $data['rol'] ?? 'cliente';
        $cuentaActiva = ($rol === 'admin') ? 'aprobado' : 'pendiente';
        
        $sql = "INSERT INTO Clientes (nombre, apellido, email, contacto, password, rol, cuenta_activa) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $this->db->query($sql, [
            $data['nombre'],
            $data['apellido'],
            $data['email'],
            $data['contacto'] ?? '',
            $passwordHash,
            $rol,
            $cuentaActiva
        ]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data) {
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            $sql = "UPDATE Clientes SET nombre = ?, apellido = ?, email = ?, contacto = ?, password = ?, rol = ? WHERE id_cliente = ?";
            $params = [$data['nombre'], $data['apellido'], $data['email'], $data['contacto'] ?? '', $data['password'], $data['rol'], $id];
        } else {
            $sql = "UPDATE Clientes SET nombre = ?, apellido = ?, email = ?, contacto = ?, rol = ? WHERE id_cliente = ?";
            $params = [$data['nombre'], $data['apellido'], $data['email'], $data['contacto'] ?? '', $data['rol'], $id];
        }
        $this->db->query($sql, $params);
    }

    public function delete($id) {
        $this->db->query("DELETE FROM Clientes WHERE id_cliente = ?", [$id]);
    }

    public function getPendientes() {
        return $this->db->fetchAll(
            "SELECT id_cliente, nombre, apellido, email, contacto, fecha_registro, cuenta_activa 
             FROM Clientes 
             WHERE cuenta_activa = 'pendiente' 
             ORDER BY fecha_registro DESC"
        );
    }

    public function gestionarSolicitud($id, $nuevoEstado) {
        $estadosValidos = ['aprobado', 'rechazado'];
        if (!in_array($nuevoEstado, $estadosValidos)) {
            return false;
        }
        
        try {
            // Consulta directa en lugar de SP
            $this->db->query(
                "UPDATE Clientes SET cuenta_activa = ? WHERE id_cliente = ?",
                [$nuevoEstado, $id]
            );
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function cambiarEstadoCuenta($idUsuario, $nuevoEstado) {
        $estadosValidos = ['aprobado', 'rechazado', 'pendiente'];
        if (!in_array($nuevoEstado, $estadosValidos)) {
            return false;
        }
        
        try {
            // Consulta directa en lugar de SP
            $this->db->query(
                "UPDATE Clientes SET cuenta_activa = ? WHERE id_cliente = ?",
                [$nuevoEstado, $idUsuario]
            );
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function resetearPassword($idUsuario) {
        $nuevaPassword = 'Cortina2026';
        $hash = password_hash($nuevaPassword, PASSWORD_DEFAULT);
        
        try {
            $this->db->query(
                "UPDATE Clientes SET password = ? WHERE id_cliente = ?",
                [$hash, $idUsuario]
            );
            return $nuevaPassword;
        } catch (Exception $e) {
            return false;
        }
    }
}