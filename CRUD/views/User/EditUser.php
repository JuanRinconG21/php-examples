<?php
// Evitar acceso directo a la vista sin datos del usuario
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: ../../index.php');
    exit;
}

require_once __DIR__ . '/../../models/Connection/ConnectionMysql.php';
require_once __DIR__ . '/../../models/User/UserClass.php';

$id = intval($_GET['id']);
$userModel = new UserClass();
$user = $userModel->getUserById($id);

?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <style>
        body {
            background-color: #0b0f13;
            color: #e5e7eb
        }

        .card {
            background-color: #111827;
            border-color: #1f2937
        }

        .form-control,
        .form-select {
            background-color: #0f172a;
            border-color: #334155;
            color: #e5e7eb
        }

        .form-control:focus {
            background-color: #0f172a;
            color: #fff
        }

        a,
        a:hover {
            color: #60a5fa
        }
    </style>
    <?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $flash_success = $_SESSION['flash_success'] ?? null;
    $flash_errors = $_SESSION['flash_errors'] ?? [];
    unset($_SESSION['flash_success'], $_SESSION['flash_errors']);
    ?>
</head>

<body>
    <div class="container py-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h1 class="fw-bold m-0">Editar Usuario</h1>
            <a href="../../index.php" class="btn btn-outline-light">Volver</a>
        </div>

        <?php if (!empty($flash_success)) : ?>
            <div class="alert alert-success" role="alert"><?php echo htmlspecialchars($flash_success); ?></div>
        <?php endif; ?>
        <?php if (!empty($flash_errors)) : ?>
            <div class="alert alert-danger" role="alert">
                <ul class="m-0 ps-3">
                    <?php foreach ($flash_errors as $err) : ?>
                        <li><?php echo htmlspecialchars($err); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <form method="POST" action="../../controllers/User/UserUpdateController.php">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id']); ?>" />
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="name" name="name" required minlength="2" maxlength="100" value="<?php echo htmlspecialchars($user['name']); ?>" />
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required value="<?php echo htmlspecialchars($user['email']); ?>" />
                    </div>
                    <div class="d-flex gap-2">
                        <a href="index.php" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>