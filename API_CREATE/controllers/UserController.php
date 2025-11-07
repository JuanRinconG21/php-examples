<?php
class UserController
{
    private $db;
    private $user;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->user = new User($this->db);
    }

    // GET - Obtener todos los usuarios
    public function index()
    {
        $stmt = $this->user->getAll();
        $count = $stmt->rowCount();

        if ($count > 0) {
            $users = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $users[] = $row;
            }
            return $this->response(200, "Usuarios encontrados", $users);
        }
        return $this->response(404, "No se encontraron usuarios");
    }

    // GET - Obtener un usuario por ID
    public function show($id)
    {
        $this->user->id = $id;

        if ($this->user->getById()) {
            $userData = [
                "id" => $this->user->id,
                "name" => $this->user->name,
                "email" => $this->user->email,
                "pass" => $this->user->pass,
            ];
            return $this->response(200, "Usuario encontrado", $userData);
        }
        return $this->response(404, "Usuario no encontrado");
    }

    // POST - Crear usuario
    public function store($data)
    {
        if (empty($data->name) || empty($data->email)) {
            return $this->response(400, "Datos incompletos. Nombre y email son requeridos");
        }

        $this->user->name = $data->name;
        $this->user->email = $data->email;
        $this->user->pass = $data->pass ?? "";
        $this->user->state = $data->state ?? 0;

        if ($this->user->create()) {
            $userData = [
                "id" => $this->user->id,
                "name" => $this->user->name,
                "email" => $this->user->email,
                "pass" => $this->user->pass,
                "state" => $this->user->state
            ];
            return $this->response(201, "Usuario creado exitosamente", $userData);
        }
        return $this->response(500, "Error al crear usuario");
    }

    // PUT - Actualizar usuario
    public function update($id, $data)
    {
        if (empty($data->nombre) || empty($data->email)) {
            return $this->response(400, "Datos incompletos. Nombre y email son requeridos");
        }

        $this->user->id = $id;

        if (!$this->user->getById()) {
            return $this->response(404, "Usuario no encontrado");
        }

        $this->user->name = $data->name;
        $this->user->email = $data->email;
        $this->user->pass = $data->pass ?? "";

        if ($this->user->update()) {
            return $this->response(200, "Usuario actualizado exitosamente");
        }
        return $this->response(500, "Error al actualizar usuario");
    }

    // DELETE - Eliminar usuario
    public function destroy($id)
    {
        $this->user->id = $id;

        if (!$this->user->getById()) {
            return $this->response(404, "Usuario no encontrado");
        }

        if ($this->user->delete()) {
            return $this->response(200, "Usuario eliminado exitosamente");
        }
        return $this->response(500, "Error al eliminar usuario");
    }

    // Método para enviar respuestas JSON
    private function response($code, $message, $data = null)
    {
        http_response_code($code);
        $response = ["message" => $message];
        if ($data !== null) {
            $response["data"] = $data;
        }
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
