<?php
include('includes/auth.php');
include('includes/db.php');
// Obtener el rol actual del usuario
$role_id = $_SESSION['role_id'];
$user_id = $_SESSION['user_id'];

echo "<h3>Historial de Transacciones</h3>";

// Consulta general si es administrador o bibliotecario
if ($role_id == 1 || $role_id == 2) {
    $sql = "SELECT t.id, u.username, b.title, t.date_of_issue, t.date_of_return 
            FROM transactions t
            JOIN users u ON t.user_id = u.id
            JOIN books b ON t.book_id = b.id
            ORDER BY t.date_of_issue DESC";
} else {
    // Consulta restringida para el lector
    $sql = "SELECT t.id, u.username, b.title, t.date_of_issue, t.date_of_return 
            FROM transactions t
            JOIN users u ON t.user_id = u.id
            JOIN books b ON t.book_id = b.id
            WHERE t.user_id = ?
            ORDER BY t.date_of_issue DESC";
}

$stmt = $conn->prepare($sql);

// Si es lector, pasamos su ID como parámetro
if ($role_id == 3) {
    $stmt->bind_param("i", $user_id);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<table class='table table-striped'>";
    echo "<thead><tr><th>Usuario</th><th>Libro</th><th>Fecha de Préstamo</th><th>Fecha de Devolución</th></tr></thead>";
    echo "<tbody>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['username']}</td>
                <td>{$row['title']}</td>
                <td>{$row['date_of_issue']}</td>
                <td>" . ($row['date_of_return'] ?? 'Pendiente') . "</td>
              </tr>";
    }
    echo "</tbody></table>";
} else {
    echo "<p>No hay transacciones registradas.</p>";
}

$stmt->close();
$conn->close();
?>
