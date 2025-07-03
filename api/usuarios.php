<?php
require_once __DIR__ . '/../models/Usuario.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Obtener usuario por ID
        if (isset($_GET['id'])) {
            $usuario = Usuario::buscarPorId($_GET['id']);
            if ($usuario) {
                unset($usuario['clave']); // No exponer la clave
                echo json_encode($usuario);
            } else {
                http_response_code(404);
                echo json_encode(["message" => "Usuario no encontrado"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "ID no recibido"]);
        }
        break;
    case 'POST':
        // Registrar usuario
        $data = json_decode(file_get_contents("php://input"), true);
        if ($data && isset($data['nombre'], $data['correo'], $data['clave'], $data['direccion'], $data['telefono'])) {
            $id = Usuario::crear($data);
            echo json_encode(["message" => "Usuario registrado", "id" => $id]);
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Datos incompletos"]);
        }
        break;
    case 'PUT':
        // No implementado (puedes agregar actualización de usuario si lo deseas)
        http_response_code(405);
        echo json_encode(["message" => "Método no permitido"]);
        break;
    case 'DELETE':
        // No implementado (puedes agregar eliminación de usuario si lo deseas)
        http_response_code(405);
        echo json_encode(["message" => "Método no permitido"]);
        break;
    default:
        http_response_code(405);
        echo json_encode(["message" => "Método no permitido"]);
        break;
}
