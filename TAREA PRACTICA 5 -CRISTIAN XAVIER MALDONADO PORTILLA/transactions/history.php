<?php
include('../includes/auth.php');
include('../includes/db.php');

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

// Consulta para obtener el historial de transacciones
$query = "SELECT t.id, u.username, b.title, t.date_of_issue, t.return_date, t.status 
          FROM transactions t 
          JOIN users u ON t.user_id = u.id 
          JOIN books b ON t.book_id = b.id";

// Si es un lector, mostrar solo sus transacciones
if ($_SESSION['role_id'] == 3) {
    $query .= " WHERE t.user_id = " . $_SESSION['user_id'];
}

$query .= " ORDER BY t.date_of_issue DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Historial de Préstamos</title>
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
        <h2>Historial de Préstamos</h2>

        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Libro</th>
                    <th>Fecha de Préstamo</th>
                    <th>Fecha de Devolución</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php while($transaction = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($transaction['username']); ?></td>
                    <td><?php echo htmlspecialchars($transaction['title']); ?></td>
                    <td><?php echo htmlspecialchars($transaction['date_of_issue']); ?></td>
                    <td>
                        <?php 
                        echo $transaction['return_date'] 
                            ? htmlspecialchars($transaction['return_date']) 
                            : 'Pendiente';
                        ?>
                    </td>
                    <td>
                        <?php 
                        $status_class = $transaction['status'] == 'prestado' ? 'text-warning' : 'text-success';
                        echo "<span class='$status_class'>" . 
                             htmlspecialchars(ucfirst($transaction['status'])) . 
                             "</span>";
                        ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <?php if ($_SESSION['role_id'] != 3): ?>
        <div class="mt-3">
            <a href="lend.php" class="btn btn-primary">Nuevo Préstamo</a>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>
