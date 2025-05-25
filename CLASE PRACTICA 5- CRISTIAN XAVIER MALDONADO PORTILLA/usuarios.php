<?php
session_start();
include 'header.php';
include 'db.php';

// Verificar si es administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol_id'] != 1) {
    header("Location: dashboard.php");
    exit();
}

// Usar prepared statement para prevenir SQL injection
$stmt = $conexion->prepare("SELECT usuarios.*, roles.rol_nombre 
                          FROM usuarios 
                          JOIN roles ON usuarios.rol_id = roles.rol_id");
$stmt->execute();
$resultado = $stmt->get_result();

echo "<div class='container mt-4'>";
echo "<h2 class='mb-4'>Usuarios Registrados</h2>";
echo "<div class='table-responsive'>";
echo "<table class='table table-hover table-bordered'>";
echo "<thead class='table-dark'>";
echo "<tr>";
echo "<th>Nombre</th>";
echo "<th>Email</th>";
echo "<th>Rol</th>";
echo "</tr>";
echo "</thead><tbody>";

while ($fila = $resultado->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($fila['nombre']) . "</td>";
    echo "<td>" . htmlspecialchars($fila['email']) . "</td>";
    echo "<td>" . htmlspecialchars($fila['rol_nombre']) . "</td>";
    echo "</tr>";
}

echo "</tbody></table>";
echo "</div>";
echo "</div>";

include 'footer.php';
?>
 