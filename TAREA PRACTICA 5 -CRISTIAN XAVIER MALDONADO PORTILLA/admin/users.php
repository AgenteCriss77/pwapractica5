<?php
include('../includes/auth.php');
include('../includes/db.php');

// Verificar si el usuario es administrador
if ($_SESSION['role_id'] != 1) {
    header('Location: ../index.php');
    exit();
}

$query = "SELECT u.id, u.username, u.email, r.name as role_name 
          FROM users u 
          JOIN roles r ON u.role_id = r.id";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Gestión de Usuarios</title>
    <link href="../diseños.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a href="../index.php" class="navbar-brand">Biblioteca</a>
            <div class="d-flex">
                <span class="navbar-text me-3">Usuario: <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <a href="../logout.php" class="btn btn-outline-danger">Cerrar sesión</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Gestión de Usuarios</h2>
        
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while($user = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo htmlspecialchars($user['role_name']); ?></td>
                    <td>
                        <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="btn btn-primary btn-sm">Editar</a>
                        <?php if ($user['id'] != $_SESSION['user_id']): ?>
                        <a href="delete_user.php?id=<?php echo $user['id']; ?>" 
                           onclick="return confirm('¿Está seguro de eliminar este usuario?');" 
                           class="btn btn-danger btn-sm">Eliminar</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        
        <a href="add_user.php" class="btn btn-success">Agregar Nuevo Usuario</a>
    </div>
</body>
</html>