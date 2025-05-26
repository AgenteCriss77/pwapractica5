<?php
include('../includes/auth.php');
include('../includes/db.php');

// Verificar que el usuario sea Lector (rol_id = 3)
if ($_SESSION['role_id'] != 3) {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel del Lector</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Lector</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="../transactions/history.php">Mi Historial</a></li>
        <li class="nav-item"><a class="nav-link" href="../logout.php">Cerrar sesión</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-5">
    <h2 class="mb-4">Bienvenido, <?php echo htmlspecialchars($_SESSION['username']); ?> 👋</h2>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card border-info shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">📚 Explorar Libros</h5>
                    <p class="card-text">Consulta el catálogo disponible en la biblioteca.</p>
                    <a href="../books/list.php" class="btn btn-info">Ver Catálogo</a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-secondary shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">🕘 Historial de Préstamos</h5>
                    <p class="card-text">Revisa los libros que has solicitado o devuelto.</p>
                    <a href="../transactions/history.php" class="btn btn-secondary">Ver Historial</a>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
