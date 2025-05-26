<?php
include('../includes/auth.php');
include('../includes/db.php');

// Verificar que sea Bibliotecario
if ($_SESSION['role_id'] != 2) {
    header("Location: ../index.php");
    exit();
}

// Procesar devolución
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['transaction_id'])) {
    $transaction_id = intval($_POST['transaction_id']);
    $date_of_return = date('Y-m-d');

    // Verificar transacción válida y no devuelta
    $sql = "SELECT book_id FROM transactions WHERE id = $transaction_id AND return_date IS NULL";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    if ($row) {
        $book_id = $row['book_id'];

        // Actualizar la transacción
        $conn->query("UPDATE transactions SET return_date = '$date_of_return' WHERE id = $transaction_id");

        // Aumentar cantidad del libro
        $conn->query("UPDATE books SET quantity = quantity + 1 WHERE id = $book_id");

        $msg = "✅ Libro devuelto exitosamente.";
    } else {
        $msg = "❌ Transacción no válida o ya devuelta.";
    }
}

// Obtener préstamos activos
$sql = "SELECT t.id, u.username, b.title, t.borrow_date
        FROM transactions t
        JOIN users u ON t.user_id = u.id
        JOIN books b ON t.book_id = b.id
        WHERE t.return_date IS NULL";
$transactions = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Devolver Libro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">📥 Devolver Libro</a>
        <a class="btn btn-outline-light" href="../librarian/dashboard.php">Volver</a>
    </div>
</nav>

<div class="container mt-5">
    <h3>📚 Registrar Devolución</h3>

    <?php if (isset($msg)): ?>
        <div class="alert alert-info"><?php echo $msg; ?></div>
    <?php endif; ?>

    <?php if ($transactions->num_rows > 0): ?>
        <form method="POST" class="mb-4">
            <div class="mb-3">
                <label class="form-label">Selecciona un préstamo a devolver:</label>
                <select name="transaction_id" class="form-select" required>
                    <option value="">-- Selecciona una transacción --</option>
                    <?php while ($t = $transactions->fetch_assoc()): ?>
                        <option value="<?php echo $t['id']; ?>">
                            <?php echo htmlspecialchars($t['username']) . " - " . htmlspecialchars($t['title']) . " (Prestado el " . $t['date_of_issue'] . ")"; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Registrar Devolución</button>
        </form>
    <?php else: ?>
        <div class="alert alert-warning">No hay libros prestados pendientes de devolución.</div>
    <?php endif; ?>
</div>

</body>
</html>
