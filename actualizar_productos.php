<?php
header('Content-Type: application/json');
require_once 'conexion.php';

$data = json_decode(file_get_contents("php://input"));

if (isset($data->id)) {
    $sql = "UPDATE productos SET 
            nombre = ?, 
            descripcion = ?, 
            precio = ?, 
            stock = ?, 
            usuario_id = ? 
            WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $data->nombre,
        $data->descripcion,
        $data->precio,
        $data->stock,
        $data->usuario_id,
        $data->id
    ]);

    echo json_encode(["message" => "✅ Producto actualizado correctamente."]);
} else {
    echo json_encode(["message" => "❌ ID del producto no recibido."]);
}
?>
