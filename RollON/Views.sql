
CREATE OR REPLACE VIEW vista_detalle_presupuesto AS
SELECT 
    p.id_pedido,
    p.fecha_creacion,
    p.estado,
    p.total_pedido,
    cl.nombre AS cliente_nombre,
    cl.apellido AS cliente_apellido,
    cl.email AS cliente_email,
    cl.contacto AS cliente_contacto,
    c.ancho,
    c.largo,
    t.nombre_tela,
    d.nombre_dispositivo,
    c.subtotal_cortina
FROM Pedidos p
JOIN Clientes cl ON p.id_cliente = cl.id_cliente
JOIN Cortinas c ON p.id_pedido = c.id_pedido
JOIN Telas t ON c.id_tela = t.id_tela
JOIN Dispositivos d ON c.id_dispositivo = d.id_dispositivo;