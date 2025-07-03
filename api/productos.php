<?php
require_once __DIR__ . '/../models/Producto.php';
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        // Si viene un id por GET, obtener solo ese producto
        if (isset($_GET['id'])) {
            $producto = Producto::obtenerPorId($_GET['id']);
            if ($producto) {
                echo json_encode($producto);
            } else {
                http_response_code(404);
                echo json_encode(["message" => "Producto no encontrado"]);
            }
        } else {
            // Listar todos los productos
            echo json_encode(Producto::listarTodos());
        }
        break;
    case 'POST':
        // Crear producto
        $data = json_decode(file_get_contents("php://input"), true);
        if ($data && isset($data['nombre'], $data['descripcion'], $data['precio'], $data['stock'], $data['usuario_id'])) {
            $id = Producto::crear($data);
            echo json_encode(["message" => "Producto creado", "id" => $id]);
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Datos incompletos"]);
        }
        break;
    case 'PUT':
        // Actualizar producto
        parse_str(file_get_contents("php://input"), $put_vars);
        $id = $put_vars['id'] ?? null;
        $data = json_decode(file_get_contents("php://input"), true);
        if ($id && $data) {
            $ok = Producto::actualizar($id, $data);
            if ($ok) {
                echo json_encode(["message" => "Producto actualizado"]);
            } else {
                http_response_code(404);
                echo json_encode(["message" => "Producto no encontrado"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Datos incompletos"]);
        }
        break;
    case 'DELETE':
        // Eliminar producto
        parse_str(file_get_contents("php://input"), $delete_vars);
        $id = $delete_vars['id'] ?? null;
        if ($id) {
            $ok = Producto::eliminar($id);
            if ($ok) {
                echo json_encode(["message" => "Producto eliminado"]);
            } else {
                http_response_code(404);
                echo json_encode(["message" => "Producto no encontrado"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "ID no recibido"]);
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(["message" => "Método no permitido"]);
        break;
}
