<?php
require_once __DIR__ . '/../models/Usuario.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    if ($data && isset($data['correo'], $data['clave'])) {
        $usuario = Usuario::validarLogin($data['correo'], $data['clave']);
        if ($usuario) {
            unset($usuario['clave']); // No exponer la clave
            echo json_encode([
                "message" => "Login exitoso",
                "usuario" => $usuario
            ]);
        } else {
            http_response_code(401);
            echo json_encode(["message" => "Usuario o clave incorrectos"]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["message" => "Datos incompletos"]);
    }
} else {
    http_response_code(405);
    echo json_encode(["message" => "Método no permitido"]);
}
