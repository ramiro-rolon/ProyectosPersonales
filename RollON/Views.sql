CREATE OR REPLACE VIEW vista_detalle_presupuesto AS
SELECT 
    p.id_pedido,
    p.nombre_cliente,
    c.ancho,
    c.largo,
    t.nombre_tela,
    d.nombre_dispositivo,
    c.subtotal_cortina
FROM Pedidos p
JOIN Cortinas c ON p.id_pedido = c.id_pedido
JOIN Telas t ON c.id_tela = t.id_tela
JOIN Dispositivos d ON c.id_dispositivo = d.id_dispositivo;