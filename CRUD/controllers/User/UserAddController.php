<?php
require_once __DIR__ . '/../../models/Connection/ConnectionMysql.php';
require_once __DIR__ . '/../../models/User/UserClass.php';

if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

// Solo aceptar POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	header('Location: ../../index.php');
	exit;
}

$errors = [];

// Sanitización básica
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

// Validaciones
if ($name === '' || mb_strlen($name) < 2) {
	$errors[] = 'El nombre es obligatorio y debe tener al menos 2 caracteres.';
}
if ($email === '') {
	$errors[] = 'El email es obligatorio.';
}
if ($password === '' || mb_strlen($password) < 6) {
	$errors[] = 'La contraseña es obligatoria y debe tener al menos 6 caracteres.';
}

try {
	$userModel = new UserClass();

	// Email válido
	if ($email !== '') {
		$userModel->emailValid($email); // lanza ValidationException si es inválido
	}

	// Duplicado
	if ($email !== '' && $userModel->userExists($email)) {
		$errors[] = 'Ya existe un usuario con ese email.';
	}

	if (!empty($errors)) {
		$_SESSION['flash_errors'] = $errors;
		header('Location: ../../index.php');
		exit;
	}

	// Crear usuario
	$created = $userModel->createUser($name, $email, $password);

	if ($created) {
		$_SESSION['flash_success'] = 'Usuario creado correctamente.';
	} else {
		$_SESSION['flash_errors'] = ['No se pudo crear el usuario.'];
	}
} catch (ValidationException $ve) {
	$_SESSION['flash_errors'] = [$ve->getMessage()];
} catch (DatabaseException $de) {
	$_SESSION['flash_errors'] = ['Error de base de datos: ' . $de->getMessage()];
} catch (Throwable $e) {
	error_log('UserAddController error: ' . $e->getMessage());
	$_SESSION['flash_errors'] = ['Ocurrió un error inesperado.'];
}

header('Location: ../../index.php');
exit;
?>
