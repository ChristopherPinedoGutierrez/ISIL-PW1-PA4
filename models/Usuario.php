<?php
// Modelo para usuarios usando procedimientos almacenados
require_once __DIR__ . '/../conexion.php';

class Usuario
{
    // Buscar usuario por correo
    public static function buscarPorCorreo($correo)
    {
        global $pdo;
        $stmt = $pdo->prepare("CALL sp_buscar_usuario_por_correo(?)");
        $stmt->execute([$correo]);
        $usuario = $stmt->fetch();
        $stmt->closeCursor();
        return $usuario;
    }

    // Buscar usuario por ID
    public static function buscarPorId($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("CALL sp_buscar_usuario_por_id(?)");
        $stmt->execute([$id]);
        $usuario = $stmt->fetch();
        $stmt->closeCursor();
        return $usuario;
    }

    // Crear usuario
    public static function crear($data)
    {
        global $pdo;
        $clave_encriptada = password_hash($data['clave'], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("CALL sp_crear_usuario(?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['nombre'],
            $data['correo'],
            $clave_encriptada,
            $data['direccion'],
            $data['telefono'],
            $data['rol'] ?? 'cliente'
        ]);
        $id = $pdo->lastInsertId();
        $stmt->closeCursor();
        return $id;
    }

    // Validar login
    public static function validarLogin($correo, $clave)
    {
        $usuario = self::buscarPorCorreo($correo);
        if ($usuario && password_verify($clave, $usuario['clave'])) {
            return $usuario;
        }
        return false;
    }
}
