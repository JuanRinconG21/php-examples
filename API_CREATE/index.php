<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once 'config/Database.php';
require_once 'models/User.php';
require_once 'controllers/UserController.php';

$method = $_SERVER['REQUEST_METHOD'];
$request = $_SERVER['REQUEST_URI'];
$request = explode('/', trim($request, '/'));

// Obtener el ID si existe en la URL
$id = isset($request[2]) && is_numeric($request[2]) ? $request[2] : null;

$controller = new UserController();
$data = json_decode(file_get_contents("php://input"));

switch ($method) {
    case 'GET':
        if ($id) {
            $controller->show($id);
        } else {
            $controller->index();
        }
        break;

    case 'POST':
        $controller->store($data);
        break;

    case 'PUT':
        if ($id) {
            $controller->update($id, $data);
        } else {
            http_response_code(400);
            echo json_encode(["message" => "ID requerido para actualizar"]);
        }
        break;

    case 'DELETE':
        if ($id) {
            $controller->destroy($id);
        } else {
            http_response_code(400);
            echo json_encode(["message" => "ID requerido para eliminar"]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["message" => "Método no permitido"]);
        break;
}
