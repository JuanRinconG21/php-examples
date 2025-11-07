<?php
class User
{
    private $conn;
    private $table = "user";

    public $id;
    public $name;
    public $email;
    public $pass;
    public $state;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Obtener todos los usuarios
    public function getAll()
    {
        $query = "SELECT * FROM " . $this->table . " ORDER BY name  DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Obtener un usuario por ID
    public function getById()
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            $this->name = $row['name'];
            $this->email = $row['email'];
            $this->pass = $row['pass'];
            $this->state = $row['state'];
            return true;
        }
        return false;
    }

    // Crear usuario
    public function create()
    {
        $query = "INSERT INTO " . $this->table . " 
                  SET name = :name, 
                      email = :email, 
                      pass = :pass,
                      state = :state";

        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->pass = htmlspecialchars(strip_tags($this->pass));
        $this->state = htmlspecialchars(strip_tags($this->state));

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":pass", $this->pass);
        $stmt->bindParam(":state", $this->state);

        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    // Actualizar usuario
    public function update()
    {
        $query = "UPDATE " . $this->table . " 
                  SET nombre = :nombre, 
                      email = :email, 
                      pass = :pass, 
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->pass = htmlspecialchars(strip_tags($this->pass));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":pass", $this->pass);
        $stmt->bindParam(":id", $this->id);

        return $stmt->execute();
    }

    // Eliminar usuario
    public function delete()
    {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":id", $this->id);

        return $stmt->execute();
    }
}
