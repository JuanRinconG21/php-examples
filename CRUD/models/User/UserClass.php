<?php
class UserException extends Exception {}
class ValidationException extends UserException {}
class DatabaseException extends UserException {}

class UserClass extends ConnectionMysql
{
    private $table = 'user';

    public function createUser($name, $email, $password)
    {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $query = "INSERT INTO {$this->table} (name, email, pass, state) VALUES (:name, :email, :password, :state)";
        $params = [
            ':name' => $name,
            ':email' => $email,
            ':password' => $hashedPassword,
            ':state' => 0
        ];

        // 3. Delegar a queryPost (ya maneja errores de BD)
        $result = $this->queryPost($query, $params);

        // 4. Verificar resultado
        if (!$result) {
            // El error ya fue registrado en queryPost
            // Opcionalmente agregar contexto
            throw new DatabaseException("Error al crear usuario: " . $this->getLastError());
        }

        return $result;
    }

    public function getUsers()
    {
        $query = "SELECT id, name, email, state FROM {$this->table} ORDER BY id DESC";
        return $this->queryGet($query);
    }

    public function getUserById($id)
    {
        $query = "SELECT id, name, email, state FROM {$this->table} WHERE id = :id LIMIT 1";
        $result = $this->queryGet($query, [':id' => $id]);
        if ($result && count($result) > 0) {
            return $result[0];
        }
        return null;
    }

    public function updateUser($id, $name, $email)
    {
        $query = "UPDATE {$this->table} SET name = :name, email = :email WHERE id = :id";
        $params = [
            ':name' => $name,
            ':email' => $email,
            ':id' => $id
        ];

        $result = $this->queryPost($query, $params);

        if (!$result) {
            throw new DatabaseException("Error al actualizar usuario: " . $this->getLastError());
        }

        return $result;
    }

    public function deleteUser($id)
    {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $params = [
            ':id' => $id
        ];

        $result = $this->queryPost($query, $params);

        if (!$result) {
            throw new DatabaseException("Error al eliminar usuario: " . $this->getLastError());
        }

        return $result;
    }

    public function emailValid($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new ValidationException("Email inválido: $email");
        }
        return true;
    }

    public function userExists($email)
    {
        if (!$this->emailValid($email)) {
            return false;
        }
        $query = "SELECT COUNT(*) as count FROM {$this->table} WHERE email = :email";
        $result = $this->queryGet($query, [':email' => $email]);

        return isset($result[0]['count']) && $result[0]['count'] > 0;
    }

    public function emailInUseByOther($email, $excludeId)
    {
        if (!$this->emailValid($email)) {
            return false;
        }
        $query = "SELECT COUNT(*) as count FROM {$this->table} WHERE email = :email AND id <> :id";
        $result = $this->queryGet($query, [':email' => $email, ':id' => $excludeId]);
        return isset($result[0]['count']) && $result[0]['count'] > 0;
    }
}
