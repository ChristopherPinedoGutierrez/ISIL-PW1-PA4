<?php
// Modelo para productos usando procedimientos almacenados
require_once __DIR__ . '/../conexion.php';

class Producto
{
    // Listar todos los productos
    public static function listarTodos()
    {
        global $pdo;
        $stmt = $pdo->query("CALL sp_listar_productos()");
        $result = $stmt->fetchAll();
        $stmt->closeCursor(); // Importante para liberar el resultado del procedure
        return $result;
    }

    // Obtener un producto por su ID
    public static function obtenerPorId($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("CALL sp_obtener_producto(?)");
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        $stmt->closeCursor();
        return $result;
    }

    // Crear un nuevo producto
    public static function crear($data)
    {
        global $pdo;
        $fecha = date('Y-m-d');
        $stmt = $pdo->prepare("CALL sp_crear_producto(?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['nombre'],
            $data['descripcion'],
            $data['precio'],
            $data['stock'],
            $fecha,
            $data['usuario_id']
        ]);
        // Obtener el último ID insertado
        $id = $pdo->lastInsertId();
        $stmt->closeCursor();
        return $id;
    }

    // Actualizar un producto existente
    public static function actualizar($id, $data)
    {
        global $pdo;
        $stmt = $pdo->prepare("CALL sp_actualizar_producto(?, ?, ?, ?, ?, ?)");
        $ok = $stmt->execute([
            $id,
            $data['nombre'],
            $data['descripcion'],
            $data['precio'],
            $data['stock'],
            $data['usuario_id']
        ]);
        $stmt->closeCursor();
        return $ok;
    }

    // Eliminar un producto
    public static function eliminar($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("CALL sp_eliminar_producto(?)");
        $ok = $stmt->execute([$id]);
        $stmt->closeCursor();
        return $ok;
    }
}
