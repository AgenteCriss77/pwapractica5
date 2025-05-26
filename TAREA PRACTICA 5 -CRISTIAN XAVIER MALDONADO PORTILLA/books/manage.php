<?php
include('../includes/auth.php');
include('../includes/db.php');

// Verifica si es bibliotecario
if ($_SESSION['role_id'] != 2) {
    header("Location: ../index.php");
    exit();
}

// Agregar libro
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $year = $_POST['year'];
    $genre = $_POST['genre'];
    $quantity = $_POST['quantity'];

    $stmt = $conn->prepare("INSERT INTO books (title, author, year, genre, quantity) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssisi", $title, $author, $year, $genre, $quantity);
    $stmt->execute();
    $stmt->close();
    header("Location: manage.php");
    exit();
}

// Eliminar libro
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM books WHERE id = $id");
    header("Location: manage.php");
    exit();
}

// Obtener libros
$result = $conn->query("SELECT * FROM books");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestionar Libros</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Gestión de Libros</a>
    <a class="btn btn-outline-light" href="../librarian/dashboard.php">Volver</a>
  </div>
</nav>

<div class="container mt-5">
    <h3>📚 Agregar Libro</h3>
    <form method="POST" class="row g-3 mb-4">
        <div class="col-md-4">
            <input type="text" name="title" class="form-control" placeholder="Título" required>
        </div>
        <div class="col-md-4">
            <input type="text" name="author" class="form-control" placeholder="Autor" required>
        </div>
        <div class="col-md-2">
            <input type="number" name="year" class="form-control" placeholder="Año">
        </div>
        <div class="col-md-2">
            <input type="text" name="genre" class="form-control" placeholder="Género">
        </div>
        <div class="col-md-2">
            <input type="number" name="quantity" class="form-control" placeholder="Cantidad" required>
        </div>
        <div class="col-md-2">
            <button type="submit" name="add" class="btn btn-success w-100">Agregar</button>
        </div>
    </form>

    <h3>📖 Catálogo Actual</h3>
    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>Título</th>
                <th>Autor</th>
                <th>Año</th>
                <th>Género</th>
                <th>Cantidad</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($book = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($book['title']); ?></td>
                <td><?php echo htmlspecialchars($book['author']); ?></td>
                <td><?php echo $book['year']; ?></td>
                <td><?php echo htmlspecialchars($book['genre']); ?></td>
                <td><?php echo $book['quantity']; ?></td>
                <td>
                    <!-- Para simplificar: solo botón de eliminar -->
                    <a href="?delete=<?php echo $book['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar este libro?')">Eliminar</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
