<?php
require_once __DIR__ . '/../../models/Connection/ConnectionMysql.php';
require_once __DIR__ . '/../../models/User/UserClass.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../index.php');
    exit;
}

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';

$errors = [];
if ($id <= 0) {
    $errors[] = 'ID inválido.';
}
if ($name === '' || mb_strlen($name) < 2) {
    $errors[] = 'El nombre es obligatorio y debe tener al menos 2 caracteres.';
}
if ($email === '') {
    $errors[] = 'El email es obligatorio.';
}

try {
    $userModel = new UserClass();

    if ($email !== '') {
        $userModel->emailValid($email);
    }

    if ($userModel->emailInUseByOther($email, $id)) {
        $errors[] = 'El email ya está en uso por otro usuario.';
    }

    if (!empty($errors)) {
        $_SESSION['flash_errors'] = $errors;
        header('Location: ../../views/User/EditUser.php?id=' . $id);
        exit;
    }

    $updated = $userModel->updateUser($id, $name, $email);
    if ($updated) {
        $_SESSION['flash_success'] = 'Usuario actualizado correctamente.';
        header('Location: ../../index.php');
        exit;
    } else {
        $_SESSION['flash_errors'] = ['No se pudo actualizar el usuario.'];
        header('Location: ../../views/User/EditUser.php?id=' . $id);
        exit;
    }
} catch (ValidationException $ve) {
    $_SESSION['flash_errors'] = [$ve->getMessage()];
    header('Location: ../../views/User/EditUser.php?id=' . $id);
    exit;
} catch (DatabaseException $de) {
    $_SESSION['flash_errors'] = ['Error de base de datos: ' . $de->getMessage()];
    header('Location: ../../views/User/EditUser.php?id=' . $id);
    exit;
} catch (Throwable $e) {
    error_log('UserUpdateController error: ' . $e->getMessage());
    $_SESSION['flash_errors'] = ['Ocurrió un error inesperado.'];
    header('Location:  ../../views/User/EditUser.php?id=' . $id);
    exit;
}
