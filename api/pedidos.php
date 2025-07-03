<?php
require_once __DIR__ . '/../models/Pedido.php';
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../enviar_correo.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'POST':
        // Registrar un nuevo pedido
        $data = json_decode(file_get_contents("php://input"), true);
        if ($data && isset($data['usuario_id'], $data['productos'], $data['total'])) {
            $pedido_id = Pedido::crear($data['usuario_id'], $data['productos'], $data['total']);
            if (is_array($pedido_id) && isset($pedido_id['error'])) {
                http_response_code(500);
                echo json_encode(["message" => "Error al registrar pedido: " . $pedido_id['error'], "body" => $pedido_id['body'] ?? null]);
            } elseif ($pedido_id) {
                // Obtener datos del usuario
                $usuario = Usuario::buscarPorId($data['usuario_id']);
                // Obtener detalle de productos para el correo
                $productos = $data['productos'];
                // Enviar correo
                $correoEnviado = enviarCorreoPedido(
                    $usuario['correo'],
                    $usuario['nombre'],
                    $pedido_id,
                    $productos,
                    $data['total']
                );
                if (is_array($correoEnviado)) {
                    $msg = isset($correoEnviado['success']) && $correoEnviado['success'] ? "Pedido registrado y correo enviado" : "Pedido registrado pero error al enviar correo: " . ($correoEnviado['error'] ?? '');
                    echo json_encode(["message" => $msg, "pedido_id" => $pedido_id, "body" => $correoEnviado['body'] ?? null]);
                } else {
                    $msg = $correoEnviado ? "Pedido registrado y correo enviado" : "Pedido registrado pero error al enviar correo";
                    echo json_encode(["message" => $msg, "pedido_id" => $pedido_id]);
                }
            } else {
                http_response_code(500);
                echo json_encode(["message" => "Error al registrar pedido"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Datos incompletos"]);
        }
        break;
    case 'GET':
        // Obtener pedidos de un usuario
        if (isset($_GET['usuario_id'])) {
            $pedidos = Pedido::obtenerPorUsuario($_GET['usuario_id']);
            echo json_encode($pedidos);
        } elseif (isset($_GET['pedido_id'])) {
            // Obtener detalle de un pedido
            $detalle = Pedido::obtenerDetalle($_GET['pedido_id']);
            echo json_encode($detalle);
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Parámetros insuficientes"]);
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(["message" => "Método no permitido"]);
        break;
}
