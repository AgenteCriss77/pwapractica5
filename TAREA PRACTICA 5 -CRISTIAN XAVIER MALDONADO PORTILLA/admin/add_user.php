<?php
include('../includes/auth.php');
include('../includes/db.php');

// Verificar si el usuario es administrador
if ($_SESSION['role_id'] != 1) {
    header('Location: ../index.php');
    exit();
}

$error = '';
$success = '';

// Obtener roles para el select
$roles_query = "SELECT * FROM roles";
$roles_result = $conn->query($roles_query);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role_id = $_POST['role_id'];

    // Validaciones
    if (empty($username) || empty($email) || empty($password) || empty($role_id)) {
        $error = 'Todos los campos son obligatorios';
    } else {
        // Verificar si el email ya existe
        $check_query = "SELECT id FROM users WHERE email = ?";
        $stmt = $conn->prepare($check_query);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = 'El email ya está registrado';
        } else {
            // Insertar nuevo usuario
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert_query = "INSERT INTO users (username, email, password, role_id) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param('sssi', $username, $email, $hashed_password, $role_id);

            if ($stmt->execute()) {
                $success = 'Usuario creado exitosamente';
            } else {
                $error = 'Error al crear el usuario: ' . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Agregar Usuario</title>
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
        <h2>Agregar Nuevo Usuario</h2>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="POST" class="mt-3">
            <div class="mb-3">
                <label for="username" class="form-label">Nombre de Usuario</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <div class="mb-3">
                <label for="role_id" class="form-label">Rol</label>
                <select class="form-control" id="role_id" name="role_id" required>
                    <option value="">Seleccione un rol</option>
                    <?php while($role = $roles_result->fetch_assoc()): ?>
                        <option value="<?php echo $role['id']; ?>">
                            <?php echo htmlspecialchars($role['name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Crear Usuario</button>
            <a href="users.php" class="btn btn-secondary">Volver</a>
        </form>
    </div>
</body>
</html>