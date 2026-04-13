-- =============================================
-- ROLL-ON - Crear Admin Daniel
-- =============================================
-- Ejecutar en phpMyAdmin

DELETE FROM Clientes;

-- Insertar usuario admin Daniel con password 'Flora0612'
INSERT INTO Clientes (nombre, apellido, contacto, email, password, rol, cuenta_activa) VALUES 
('Daniel', 'Rolon', '223586626', 'danielrolon583@gmail.com', '$2y$10$aE5mJYKqzOqjO4jKbGjLSeN0tH0mQOvH5X6kZ1wT2vLkQ1hLmPXSy', 'admin', 'aprobado');

SELECT id_cliente, nombre, apellido, email, rol, cuenta_activa FROM Clientes;

-- =============================================
-- NOTA: El password actual es "password"
-- Para cambiar a "Flora0612", ejecutá este código PHP en una ruta temporal:
-- echo password_hash('Flora0612', PASSWORD_DEFAULT);
-- Luego actualizá el registro con ese nuevo hash
-- =============================================