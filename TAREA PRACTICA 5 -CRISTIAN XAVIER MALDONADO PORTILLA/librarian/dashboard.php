<?php
include('../includes/auth.php');
include('../includes/db.php');

// Verificar que sea Bibliotecario (rol_id = 2)
if ($_SESSION['role_id'] != 2) {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel del Bibliotecario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Bibliotecario</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="../transactions/history.php">Historial</a></li>
        <li class="nav-item"><a class="nav-link" href="../logout.php">Cerrar sesión</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-5">
    <h2 class="mb-4">Panel del Bibliotecario</h2>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card border-success shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">📘 Gestionar Libros</h5>
                    <p class="card-text">Agregar, editar o eliminar libros del catálogo.</p>
                    <a href="../books/manage.php" class="btn btn-success">Gestionar Libros</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-primary shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">📤 Prestar Libros</h5>
                    <p class="card-text">Registrar un nuevo préstamo de libro.</p>
                    <a href="../transactions/lend.php" class="btn btn-primary">Prestar</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-warning shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">📥 Devolver Libros</h5>
                    <p class="card-text">Registrar la devolución de libros prestados.</p>
                    <a href="../transactions/return.php" class="btn btn-warning">Registrar Devolución</a>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
