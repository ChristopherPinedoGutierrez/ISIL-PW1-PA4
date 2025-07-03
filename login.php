<?php
header('Content-Type: application/json');
session_start();
require_once 'conexion.php';

$data = json_decode(file_get_contents("php://input"));

if (isset($data->correo, $data->clave)) {
    $sql = "SELECT * FROM usuarios WHERE correo = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$data->correo]);
    $usuario = $stmt->fetch();

    if ($usuario && password_verify($data->clave, $usuario['clave'])) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['correo'] = $usuario['correo'];
        echo json_encode([
            "message" => "✅ Login exitoso",
            "usuario_id" => $usuario['id'],
            "rol" => $usuario['rol']
        ]);
    } else {
        echo json_encode(["message" => "❌ Usuario o clave incorrectos"]);
    }
} else {
    echo json_encode(["message" => "❌ Faltan datos"]);
}
?>
