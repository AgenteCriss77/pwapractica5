<?php
include('../includes/auth.php');
if ($_SESSION['role_id'] != 1) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Administrador - Dashboard</title>
<link href="../diseños.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Admin Dashboard</a>
    <div class="d-flex">
      <span class="navbar-text text-white me-3">Hola, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
      <a href="../logout.php" class="btn btn-outline-light">Cerrar sesión</a>
    </div>
  </div>
</nav>

<div class="container mt-5">
  <h1>Panel de Administrador</h1>
  <p>Bienvenido al sistema de gestión de biblioteca.</p>

  <div class="list-group mt-4">
    <a href="../books/list.php" class="list-group-item list-group-item-action">Gestión de Libros</a>
    <a href="users.php" class="list-group-item list-group-item-action">Gestión de Usuarios</a>
    <a href="../transactions/history.php" class="list-group-item list-group-item-action">Historial de Préstamos</a>
  </div>
</div>

</body>
</html>
