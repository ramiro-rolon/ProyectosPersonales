USE cotizador_cortinas;

DELIMITER //

-- 1. Agregar nueva tela
CREATE PROCEDURE sp_agregar_tela(
    IN p_nombre VARCHAR(100),
    IN p_precio DECIMAL(10,2)
)
BEGIN
    INSERT INTO Telas (nombre_tela, precio_m2) VALUES (p_nombre, p_precio);
END //

-- 2. Agregar nuevo dispositivo (mecanismo)
CREATE PROCEDURE sp_agregar_dispositivo(
    IN p_nombre VARCHAR(100),
    IN p_ancho_min DECIMAL(10,2),
    IN p_ancho_max DECIMAL(10,2),
    IN p_precio DECIMAL(10,2)
)
BEGIN
    INSERT INTO Dispositivos (nombre_dispositivo, ancho_minimo, ancho_maximo, precio_dispositivo) 
    VALUES (p_nombre, p_ancho_min, p_ancho_max, p_precio);
END //

-- 3. Agregar nuevo servicio extra
CREATE PROCEDURE sp_agregar_extra(
    IN p_desc VARCHAR(100),
    IN p_precio DECIMAL(10,2)
)
BEGIN
    INSERT INTO Extras (descripcion, precio_fijo) VALUES (p_desc, p_precio);
END //

-- 4. Iniciar un nuevo Pedido (Devuelve el ID generado)
CREATE PROCEDURE sp_crear_pedido(
    IN p_id_cliente INT, 
    OUT p_id_generado INT
)
BEGIN
    INSERT INTO Pedidos (id_cliente, estado) 
    VALUES (p_id_cliente, 'Presupuesto');
    
    SET p_id_generado = LAST_INSERT_ID();
END //

-- 5. Cargar cortina a un pedido y calcular su valor automáticamente
CREATE PROCEDURE sp_cargar_cortina_a_pedido(
    IN p_id_pedido INT,
    IN p_id_tela INT,
    IN p_ancho DECIMAL(10,2),
    IN p_largo DECIMAL(10,2)
)
BEGIN
    DECLARE v_precio_tela DECIMAL(10,2);
    DECLARE v_id_dispositivo INT;
    DECLARE v_precio_dispositivo DECIMAL(10,2);
    DECLARE v_subtotal DECIMAL(10,2);

    -- Obtener precio de la tela
    SELECT precio_m2 INTO v_precio_tela FROM Telas WHERE id_tela = p_id_tela;

    -- Buscar el dispositivo adecuado según el ancho
    SELECT id_dispositivo, precio_dispositivo 
    INTO v_id_dispositivo, v_precio_dispositivo
    FROM Dispositivos 
    WHERE p_ancho >= ancho_minimo AND p_ancho <= ancho_maximo
    LIMIT 1;

    -- Cálculo: (Metros cuadrados * precio tela) + precio del dispositivo
    SET v_subtotal = (p_ancho * p_largo * v_precio_tela) + v_precio_dispositivo;

    -- Insertar la cortina
    INSERT INTO Cortinas (id_pedido, id_tela, id_dispositivo, ancho, largo, subtotal_cortina)
    VALUES (p_id_pedido, p_id_tela, v_id_dispositivo, p_ancho, p_largo, v_subtotal);

    -- Actualizar el total del pedido
    UPDATE Pedidos SET total_pedido = total_pedido + v_subtotal WHERE id_pedido = p_id_pedido;
END //

-- 6. Cargar extras a un pedido
CREATE PROCEDURE sp_cargar_extra_a_pedido(
    IN p_id_pedido INT,
    IN p_id_extra INT,
    IN p_cantidad INT
)
BEGIN
    DECLARE v_precio_actual DECIMAL(10,2);
    
    SELECT precio_fijo INTO v_precio_actual FROM Extras WHERE id_extra = p_id_extra;

    INSERT INTO Pedido_Extras (id_pedido, id_extra, cantidad, precio_al_momento)
    VALUES (p_id_pedido, p_id_extra, p_cantidad, v_precio_actual);

    -- Actualizar el total del pedido sumando el extra
    UPDATE Pedidos SET total_pedido = total_pedido + (v_precio_actual * p_cantidad) 
    WHERE id_pedido = p_id_pedido;
END //

CREATE PROCEDURE sp_agregar_tela(
    IN p_nombre VARCHAR(100),
    IN p_precio DECIMAL(10,2),
    IN p_ancho_max DECIMAL(10,2)
)
BEGIN
    INSERT INTO Telas (nombre_tela, precio_m2, ancho_maximo_tela) 
    VALUES (p_nombre, p_precio, p_ancho_max);
END //

-- 7. Procedimiento para dar de alta al cliente y obtener su ID
CREATE PROCEDURE sp_registrar_usuario(
    IN p_nombre VARCHAR(50),
    IN p_apellido VARCHAR(50),
    IN p_contacto VARCHAR(20),
    IN p_email VARCHAR(100),
    IN p_password VARCHAR(255),
    IN p_rol ENUM('cliente', 'admin'),
    OUT p_id_generado INT
)
BEGIN
    INSERT INTO Clientes (nombre, apellido, contacto, email, password, rol, cuenta_activa) 
    VALUES (p_nombre, p_apellido, p_contacto, p_email, p_password, p_rol, 'pendiente');
    
    SET p_id_generado = LAST_INSERT_ID();
END //


-- 8. Trae la información del cliente y los ítems del pedido

CREATE PROCEDURE sp_obtener_detalle_pedido(IN p_id_pedido INT)
BEGIN
    SELECT 
        id_pedido,
        cliente_nombre,
        cliente_apellido,
        cliente_contacto,
        nombre_tela,
        ancho,
        largo,
        nombre_dispositivo,
        subtotal_cortina,
        total_pedido,
        estado
    FROM vista_detalle_presupuesto
    WHERE id_pedido = p_id_pedido;
END //

-- 9. Obtenemos los datos necesarios para validar en codigo y armar la sesión

CREATE PROCEDURE sp_login_usuario(IN p_email VARCHAR(100))
BEGIN
    SELECT id_cliente, nombre, apellido, email, password, rol, cuenta_activa 
    FROM Clientes 
    WHERE LOWER(email) = LOWER(p_email)
    LIMIT 1;
END //

-- 10. Cambiamos los datos de los usuarios

CREATE PROCEDURE sp_actualizar_usuario(
    IN p_id_usuario INT,
    IN p_nombre VARCHAR(50),
    IN p_apellido VARCHAR(50),
    IN p_contacto VARCHAR(20),
    IN p_email VARCHAR(100)
)
BEGIN
    UPDATE Clientes 
    SET nombre = p_nombre, 
        apellido = p_apellido, 
        contacto = p_contacto, 
        email = p_email
    WHERE id_cliente = p_id_usuario;
END //

-- 11. SP para el cambio de password

CREATE PROCEDURE sp_actualizar_password(
    IN p_id_usuario INT,
    IN p_nueva_password VARCHAR(255)
)
BEGIN
    UPDATE Clientes 
    SET password = p_nueva_password 
    WHERE id_cliente = p_id_usuario;
END //

-- 12. Asi se le puede dar un aprobado a un cliente o no 

CREATE PROCEDURE sp_gestionar_solicitud(
    IN p_id_usuario INT,
    IN p_nuevo_estado ENUM('pendiente', 'aprobado', 'rechazado')
)
BEGIN
    UPDATE Clientes 
    SET cuenta_activa = p_nuevo_estado 
    WHERE id_cliente = p_id_usuario;
END //

-- 13. Login por nombre y apellido

CREATE PROCEDURE sp_login_nombre_apellido(
    IN p_nombre VARCHAR(50),
    IN p_apellido VARCHAR(50)
)
BEGIN
    SELECT id_cliente, nombre, apellido, email, password, rol, cuenta_activa 
    FROM Clientes 
    WHERE LOWER(nombre) = p_nombre AND LOWER(apellido) = p_apellido
    LIMIT 1;
END //


DELIMITER ;


INSERT INTO Clientes (nombre, apellido, contacto, email, password, rol, cuenta_activa)
VALUES ('Daniel', 'Rolon', '223586626', 'danielrolon583@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'aprobado');

-- 1. Verificar que el usuario existe
SELECT id_cliente, email, password, rol, cuenta_activa FROM Clientes;

-- 2. Probar el SP manualmente
CALL sp_login_usuario('danielrolon583@gmail.com');