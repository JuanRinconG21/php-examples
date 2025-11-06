<?php
require_once __DIR__ . '/../../models/Connection/ConnectionMysql.php';
require_once __DIR__ . '/../../models/User/UserClass.php';

if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

$id = null;
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
	$id = isset($_GET['id']) ? intval($_GET['id']) : null;
}

if (!$id || $id <= 0) {
	$_SESSION['flash_errors'] = ['ID inválido para eliminar.'];
	header('Location: ../../index.php');
	exit;
}

try {
	$userModel = new UserClass();
	$deleted = $userModel->deleteUser($id);
	if ($deleted) {
		$_SESSION['flash_success'] = 'Usuario eliminado correctamente.';
	} else {
		$_SESSION['flash_errors'] = ['No se pudo eliminar el usuario.'];
	}
} catch (Throwable $e) {
	error_log('UserDeleteController error: ' . $e->getMessage());
	$_SESSION['flash_errors'] = ['Ocurrió un error al eliminar el usuario.'];
}

header('Location: ../../index.php');
exit;
?>
