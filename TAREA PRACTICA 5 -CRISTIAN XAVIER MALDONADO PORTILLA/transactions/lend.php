<?php
include('../includes/auth.php');
include('../includes/db.php');

// Verificar si el usuario es admin o bibliotecario
if ($_SESSION['role_id'] != 1 && $_SESSION['role_id'] != 2) {
    header('Location: ../index.php');
    exit();
}

$error = '';
$success = '';

// Obtener lista de usuarios lectores
$users_query = "SELECT id, username FROM users WHERE role_id = 3";
$users_result = $conn->query($users_query);

// Obtener lista de libros disponibles
$books_query = "SELECT id, title, quantity FROM books WHERE quantity > 0";
$books_result = $conn->query($books_query);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $book_id = $_POST['book_id'];
    $borrow_date = date('Y-m-d');

    // Verificar si el libro está disponible
    $check_query = "SELECT quantity FROM books WHERE id = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param('i', $book_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $book = $result->fetch_assoc();

    if ($book['quantity'] > 0) {
        // Iniciar transacción
        $conn->begin_transaction();

        try {
            // Insertar el préstamo
            $insert_query = "INSERT INTO transactions (user_id, book_id, date_of_issue, status) VALUES (?, ?, ?, 'prestado')";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param('iis', $user_id, $book_id, $borrow_date);
            $stmt->execute();

            // Actualizar la cantidad de libros
            $update_query = "UPDATE books SET quantity = quantity - 1 WHERE id = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param('i', $book_id);
            $stmt->execute();

            $conn->commit();
            $success = 'Préstamo registrado exitosamente';

            // Recargar la lista de libros
            $books_result = $conn->query($books_query);
        } catch (Exception $e) {
            $conn->rollback();
            $error = 'Error al registrar el préstamo: ' . $e->getMessage();
        }
    } else {
        $error = 'El libro no está disponible';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Nuevo Préstamo</title>
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
        <h2>Nuevo Préstamo</h2>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="POST" class="mt-3">
            <div class="mb-3">
                <label for="user_id" class="form-label">Usuario</label>
                <select class="form-control" id="user_id" name="user_id" required>
                    <option value="">Seleccione un usuario</option>
                    <?php while($user = $users_result->fetch_assoc()): ?>
                        <option value="<?php echo $user['id']; ?>">
                            <?php echo htmlspecialchars($user['username']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="book_id" class="form-label">Libro</label>
                <select class="form-control" id="book_id" name="book_id" required>
                    <option value="">Seleccione un libro</option>
                    <?php while($book = $books_result->fetch_assoc()): ?>
                        <option value="<?php echo $book['id']; ?>">
                            <?php echo htmlspecialchars($book['title']) . ' (Disponibles: ' . $book['quantity'] . ')'; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Registrar Préstamo</button>
            <a href="history.php" class="btn btn-secondary">Volver al Historial</a>
        </form>
    </div>
</body>
</html>
