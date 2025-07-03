<?php
header('Content-Type: application/json');
require_once 'conexion.php';

$data = json_decode(file_get_contents("php://input"));

if (
    isset($data->nombre, $data->descripcion, $data->precio,
          $data->stock, $data->usuario_id)
) {
    $fecha = date('Y-m-d');
    $sql = "INSERT INTO productos (nombre, descripcion, precio, stock, fecha_ingreso, usuario_id)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $data->nombre,
        $data->descripcion,
        $data->precio,
        $data->stock,
        $fecha,
        $data->usuario_id
    ]);

    echo json_encode(["message" => "✅ Producto insertado correctamente."]);
} else {
    echo json_encode(["message" => "❌ Datos incompletos."]);
}
?>

