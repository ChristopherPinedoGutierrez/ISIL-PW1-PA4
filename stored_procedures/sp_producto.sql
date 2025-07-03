-- Crear producto
DELIMITER $$
CREATE PROCEDURE sp_crear_producto(
    IN p_nombre VARCHAR(100),
    IN p_descripcion TEXT,
    IN p_precio DECIMAL(10,2),
    IN p_stock INT,
    IN p_fecha_ingreso DATE,
    IN p_usuario_id INT
)
BEGIN
    INSERT INTO productos (nombre, descripcion, precio, stock, fecha_ingreso, usuario_id)
    VALUES (p_nombre, p_descripcion, p_precio, p_stock, p_fecha_ingreso, p_usuario_id);
END $$
DELIMITER ;

-- Listar productos
DELIMITER $$
CREATE PROCEDURE sp_listar_productos()
BEGIN
    SELECT * FROM productos;
END $$
DELIMITER ;

-- Obtener producto por ID
DELIMITER $$
CREATE PROCEDURE sp_obtener_producto(IN p_id INT)
BEGIN
    SELECT * FROM productos WHERE id = p_id;
END $$
DELIMITER ;

-- Actualizar producto
DELIMITER $$
CREATE PROCEDURE sp_actualizar_producto(
    IN p_id INT,
    IN p_nombre VARCHAR(100),
    IN p_descripcion TEXT,
    IN p_precio DECIMAL(10,2),
    IN p_stock INT,
    IN p_usuario_id INT
)
BEGIN
    UPDATE productos
    SET nombre = p_nombre,
        descripcion = p_descripcion,
        precio = p_precio,
        stock = p_stock,
        usuario_id = p_usuario_id
    WHERE id = p_id;
END $$
DELIMITER ;

-- Eliminar producto
DELIMITER $$
CREATE PROCEDURE sp_eliminar_producto(IN p_id INT)
BEGIN
    DELETE FROM productos WHERE id = p_id;
END $$
DELIMITER ;