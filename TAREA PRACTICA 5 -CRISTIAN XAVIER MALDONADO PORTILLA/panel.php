<?php
include('includes/auth.php');
include('includes/db.php');
if ($_SESSION['role_id'] != 1) {
  header("Location: ../login.php");
  exit();
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Panel del Administrador</title>
  <link href=\"https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css\" rel=\"stylesheet\">
</head>
<body>
<div class=\"container mt-5\">
  <h2>Panel del Administrador</h2>
  <p>Bienvenido. Desde aquí puedes gestionar usuarios, roles y ver transacciones.</p>
  <a href=\"../logout.php\" class=\"btn btn-danger\">Cerrar sesión</a>
</div>
</body>
</html>
