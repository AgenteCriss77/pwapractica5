<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../includes/auth.php');
include('../includes/db.php');

$query = "SELECT * FROM books";
$result = $conn->query($query);
if (!$result) {
    die("Error en la consulta: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Catálogo de Libros</title>
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
  <h2>Catálogo de Libros</h2>
  <?php if ($_SESSION['role_id'] == 1 || $_SESSION['role_id'] == 2): ?>
    <a href="add.php" class="btn btn-success mb-3">Agregar Nuevo Libro</a>
  <?php endif; ?>

  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Título</th>
        <th>Autor</th>
        <th>Año</th>
        <th>Género</th>
        <th>Cantidad</th>
        <?php if ($_SESSION['role_id'] == 1 || $_SESSION['role_id'] == 2): ?>
          <th>Acciones</th>
        <?php endif; ?>
      </tr>
    </thead>
    <tbody>
      <?php while($book = $result->fetch_assoc()): ?>
      <tr>
        <td><?php echo htmlspecialchars($book['title']); ?></td>
        <td><?php echo htmlspecialchars($book['author']); ?></td>
        <td><?php echo htmlspecialchars($book['year']); ?></td>
        <td><?php echo htmlspecialchars($book['genre']); ?></td>
        <td><?php echo htmlspecialchars($book['quantity']); ?></td>
        <?php if ($_SESSION['role_id'] == 1 || $_SESSION['role_id'] == 2): ?>
          <td>
            <a href="edit.php?id=<?php echo $book['id']; ?>" class="btn btn-primary btn-sm">Editar</a>
            <a href="delete.php?id=<?php echo $book['id']; ?>" onclick="return confirm('¿Está seguro de eliminar este libro?');" class="btn btn-danger btn-sm">Eliminar</a>
          </td>
        <?php endif; ?>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

</body> 
</html>
