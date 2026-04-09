USE cotizador_cortinas;

DELIMITER //

-- Función para calcular el valor de UNA cortina en el aire
CREATE FUNCTION fn_calcular_valor_cortina(
    p_id_tela INT,
    p_ancho DECIMAL(10,2),
    p_largo DECIMAL(10,2)
) 
RETURNS DECIMAL(10,2)
DETERMINISTIC
BEGIN
    DECLARE v_precio_tela DECIMAL(10,2);
    DECLARE v_precio_dispositivo DECIMAL(10,2);
    DECLARE v_total DECIMAL(10,2);

    -- Obtener precio de la tela
    SELECT precio_m2 INTO v_precio_tela FROM Telas WHERE id_tela = p_id_tela;

    -- Buscar el precio del dispositivo según el ancho
    SELECT precio_dispositivo INTO v_precio_dispositivo
    FROM Dispositivos 
    WHERE p_ancho >= ancho_minimo AND p_ancho <= ancho_maximo
    LIMIT 1;

    -- Si no encuentra dispositivo, asumimos 0 o un valor base
    SET v_precio_dispositivo = IFNULL(v_precio_dispositivo, 0);

    SET v_total = (p_ancho * p_largo * v_precio_tela) + v_precio_dispositivo;
    
    RETURN v_total;
END //

-- Función para ver el total del pedido SIN extras
CREATE FUNCTION fn_total_pedido_sin_extras(p_id_pedido INT)
RETURNS DECIMAL(10,2)
DETERMINISTIC
BEGIN
    DECLARE v_suma DECIMAL(10,2);
    SELECT SUM(subtotal_cortina) INTO v_suma FROM Cortinas WHERE id_pedido = p_id_pedido;
    RETURN IFNULL(v_suma, 0);
END //

-- Función para ver el total del pedido CON extras
CREATE FUNCTION fn_total_pedido_con_extras(p_id_pedido INT)
RETURNS DECIMAL(10,2)
DETERMINISTIC
BEGIN
    DECLARE v_total_cortinas DECIMAL(10,2);
    DECLARE v_total_extras DECIMAL(10,2);
    
    SET v_total_cortinas = fn_total_pedido_sin_extras(p_id_pedido);
    
    SELECT SUM(cantidad * precio_al_momento) INTO v_total_extras 
    FROM Pedido_Extras 
    WHERE id_pedido = p_id_pedido;
    
    RETURN v_total_cortinas + IFNULL(v_total_extras, 0);
END //

CREATE FUNCTION fn_validar_factibilidad(
    p_id_tela INT,
    p_ancho DECIMAL(10,2)
) 
RETURNS VARCHAR(255)
DETERMINISTIC
BEGIN
    DECLARE v_max_tela DECIMAL(10,2);
    DECLARE v_max_disp DECIMAL(10,2);
    
    -- Obtener máximo de la tela
    SELECT ancho_maximo_tela INTO v_max_tela FROM Telas WHERE id_tela = p_id_tela;
    
    -- Obtener el máximo permitido por el dispositivo más grande disponible
    SELECT MAX(ancho_maximo) INTO v_max_disp FROM Dispositivos;

    IF p_ancho > v_max_tela THEN
        RETURN CONCAT('Error: La tela seleccionada solo permite hasta ', v_max_tela, 'm de ancho.');
    ELSEIF p_ancho > v_max_disp THEN
        RETURN CONCAT('Error: No tenemos mecanismos para un ancho de ', p_ancho, 'm.');
    END IF;

    RETURN 'OK';
END //


DELIMITER ;