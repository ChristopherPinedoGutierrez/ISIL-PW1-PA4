-- Crear usuario
DELIMITER $$
CREATE PROCEDURE sp_crear_usuario(
    IN p_nombre VARCHAR(100),
    IN p_correo VARCHAR(100),
    IN p_clave VARCHAR(255),
    IN p_direccion VARCHAR(150),
    IN p_telefono VARCHAR(20),
    IN p_rol ENUM('cliente','admin')
)
BEGIN
    INSERT INTO usuarios (nombre, correo, clave, direccion, telefono, rol)
    VALUES (p_nombre, p_correo, p_clave, p_direccion, p_telefono, p_rol);
END $$
DELIMITER ;

-- Buscar usuario por correo
DELIMITER $$
CREATE PROCEDURE sp_buscar_usuario_por_correo(
    IN p_correo VARCHAR(100)
)
BEGIN
    SELECT * FROM usuarios WHERE correo = p_correo;
END $$
DELIMITER ;

-- Buscar usuario por ID
DELIMITER $$
CREATE PROCEDURE sp_buscar_usuario_por_id(
    IN p_id INT
)
BEGIN
    SELECT * FROM usuarios WHERE id = p_id;
END $$
DELIMITER ;