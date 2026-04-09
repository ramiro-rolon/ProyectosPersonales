USE cotizador_cortinas;

DELIMITER //

-- 1. Agregar nueva tela
CREATE PROCEDURE sp_agregar_tela(
    IN p_nombre VARCHAR(100),
    IN p_precio DECIMAL(10,2),
    IN p_ancho_max DECIMAL(10,2)
)
BEGIN
    INSERT INTO Telas (nombre_tela, precio_m2, ancho_maximo_tela) 
    VALUES (p_nombre, p_precio, p_ancho_max);
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
    IN p_cliente VARCHAR(100),
    OUT p_id_generado INT
)
BEGIN
    INSERT INTO Pedidos (nombre_cliente, estado) VALUES (p_cliente, 'Presupuesto');
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

DELIMITER ;
