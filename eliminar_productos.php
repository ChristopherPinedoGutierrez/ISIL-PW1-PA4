<?php
header('Content-Type: application/json');
require_once 'conexion.php';

$data = json_decode(file_get_contents("php://input"));

if (isset($data->id)) {
    $sql = "DELETE FROM productos WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$data->id]);

    echo json_encode(["message" => "✅ Producto eliminado correctamente."]);
} else {
    echo json_encode(["message" => "❌ ID del producto no recibido."]);
}
?>
