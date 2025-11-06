<?php
require_once __DIR__ . '\..\..\models\Connection\ConnectionMysql.php';
require_once __DIR__ . '\..\..\models\User\UserClass.php';

class UserGetController
{
    public function index(): array
    {
        try {
            $userModel = new UserClass();
            $users = $userModel->getUsers();

            if ($users === false || $users === null) {
                // En caso de error en la consulta, getUsers() retorna false
                return [];
            }

            return $users;
        } catch (Throwable $e) {
            // Seguridad: no exponer mensajes sensibles en la UI
            error_log('UserGetController error: ' . $e->getMessage());
            return [];
        }
    }
}
