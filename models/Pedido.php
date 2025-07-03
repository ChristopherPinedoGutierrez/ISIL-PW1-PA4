<?php
// Modelo para pedidos usando procedimientos almacenados
require_once __DIR__ . '/../conexion.php';

class Pedido
{
    // Registrar un nuevo pedido y sus detalles
    public static function crear($usuario_id, $productos, $total)
    {
        global $pdo;
        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare("CALL sp_crear_pedido(?, ?)");
            $stmt->execute([$usuario_id, $total]);
            $row = $stmt->fetch();
            $pedido_id = $row['id'];
            $stmt->closeCursor();

            $stmt_det = $pdo->prepare("CALL sp_crear_detalle_pedido(?, ?, ?, ?, ?)");
            foreach ($productos as $prod) {
                $subtotal = $prod['cantidad'] * $prod['precio_unitario'];
                $stmt_det->execute([
                    $pedido_id,
                    $prod['producto_id'],
                    $prod['cantidad'],
                    $prod['precio_unitario'],
                    $subtotal
                ]);
                $stmt_det->closeCursor();
                // Actualizar stock
                $stmt_stock = $pdo->prepare("CALL sp_actualizar_stock_producto(?, ?)");
                $stmt_stock->execute([$prod['producto_id'], $prod['cantidad']]);
                $stmt_stock->closeCursor();
            }
            $pdo->commit();
            return $pedido_id;
        } catch (Exception $e) {
            $pdo->rollBack();
            return ["error" => $e->getMessage()];
        }
    }

    // Obtener pedidos de un usuario
    public static function obtenerPorUsuario($usuario_id)
    {
        global $pdo;
        $stmt = $pdo->prepare("CALL sp_obtener_pedidos_por_usuario(?)");
        $stmt->execute([$usuario_id]);
        $result = $stmt->fetchAll();
        $stmt->closeCursor();
        return $result;
    }

    // Obtener detalles de un pedido
    public static function obtenerDetalle($pedido_id)
    {
        global $pdo;
        $stmt = $pdo->prepare("CALL sp_obtener_detalle_pedido(?)");
        $stmt->execute([$pedido_id]);
        $result = $stmt->fetchAll();
        $stmt->closeCursor();
        return $result;
    }
}
