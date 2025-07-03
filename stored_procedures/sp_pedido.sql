-- Crear pedido
DELIMITER $$
CREATE PROCEDURE sp_crear_pedido(
    IN p_usuario_id INT,
    IN p_total DECIMAL(10,2)
)
BEGIN
    INSERT INTO pedidos (usuario_id, total) VALUES (p_usuario_id, p_total);
END $$
DELIMITER ;

-- Crear detalle de pedido
DELIMITER $$
CREATE PROCEDURE sp_crear_detalle_pedido(
    IN p_pedido_id INT,
    IN p_producto_id INT,
    IN p_cantidad INT,
    IN p_precio_unitario DECIMAL(10,2),
    IN p_subtotal DECIMAL(10,2)
)
BEGIN
    INSERT INTO detalle_pedido (pedido_id, producto_id, cantidad, precio_unitario, subtotal)
    VALUES (p_pedido_id, p_producto_id, p_cantidad, p_precio_unitario, p_subtotal);
END $$
DELIMITER ;

-- Actualizar stock de producto
DELIMITER $$
CREATE PROCEDURE sp_actualizar_stock_producto(
    IN p_producto_id INT,
    IN p_cantidad INT
)
BEGIN
    UPDATE productos SET stock = stock - p_cantidad WHERE id = p_producto_id;
END $$
DELIMITER ;

-- Obtener pedidos por usuario
DELIMITER $$
CREATE PROCEDURE sp_obtener_pedidos_por_usuario(
    IN p_usuario_id INT
)
BEGIN
    SELECT * FROM pedidos WHERE usuario_id = p_usuario_id ORDER BY fecha_pedido DESC;
END $$
DELIMITER ;

-- Obtener detalle de pedido
DELIMITER $$
CREATE PROCEDURE sp_obtener_detalle_pedido(
    IN p_pedido_id INT
)
BEGIN
    SELECT d.*, p.nombre
    FROM detalle_pedido d
    JOIN productos p ON d.producto_id = p.id
    WHERE d.pedido_id = p_pedido_id;
END $$
DELIMITER ;