<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '\controllers\\User\\UserGetController.php';
$controller = new UserGetController();
$users = $controller->index();

$flash_success = $_SESSION['flash_success'] ?? null;
$flash_errors = $_SESSION['flash_errors'] ?? [];
// Limpiar flashes para que se muestren solo una vez
unset($_SESSION['flash_success'], $_SESSION['flash_errors']);
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listado de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>

<body>
    <div class="container py-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h1 class="fw-bold m-0">Usuarios</h1>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">Agregar usuario</button>
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
        <table class="table table-dark table-striped align-middle text-center">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Email</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)) : ?>
                    <?php foreach ($users as $user) : ?>
                        <tr>
                            <th scope="row"><?php echo ($user['id'] ?? ''); ?></th>
                            <td><?php echo isset($user['name']) ? htmlspecialchars($user['name']) : ''; ?></td>
                            <td><?php echo isset($user['email']) ? htmlspecialchars($user['email']) : ''; ?></td>
                            <td><?php echo isset($user['state']) ? (($user['state'] == 0) ? 'Activo' : 'Inactivo') : ''; ?></td>
                            <td>
                                <a href="./views/User/EditUser.php?id=<?php echo ($user['id'] ?? ''); ?>" class="btn btn-warning btn-sm">Editar</a>
                                <a href="./controllers/User/UserDeleteController.php?id=<?php echo ($user['id'] ?? ''); ?>" class="btn btn-danger btn-sm">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="5" class="text-center">No hay usuarios para mostrar.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Modal Agregar Usuario -->
        <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addUserModalLabel">Agregar Usuario</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" action="controllers/User/UserAddController.php">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="name" name="name" required minlength="2" maxlength="100" />
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required />
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="password" name="password" required minlength="6" />
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>