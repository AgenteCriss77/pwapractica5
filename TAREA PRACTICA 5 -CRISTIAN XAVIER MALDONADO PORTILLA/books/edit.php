<?php
include('../includes/auth.php');
include('../includes/db.php');

if (!in_array($_SESSION['role_id'], [1, 2])) {
    header("Location: ../index.php");
    exit();
}

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: list.php");
    exit();
}

$error = '';
$stmt = $conn->prepare("SELECT * FROM books WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: list.php");
    exit();
}

$book = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'] ?? '';
    $author = $_POST['author'] ?? '';
    $year = $_POST['year'] ?? null;
    $genre = $_POST['genre'] ?? '';
    $quantity = $_POST['quantity'] ?? 0;

    if (empty($title) || empty($author) || $quantity < 0) {
        $error = "Complete los campos requeridos correctamente.";
    } else {
        $stmt = $conn->prepare("UPDATE books SET title=?, author=?, year=?, genre=?, quantity=? WHERE id=?");
        $stmt->bind_param("ssisii", $title, $author, $year, $genre, $quantity, $id);
        if ($stmt->execute()) {
            header("Location: list.php");
            exit();
        } else {
            $error = "Error al actualizar el libro.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Editar Libro</title>
<link href="../diseños.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>

<div class="container mt-4">
  <h2>Editar Libro</h2>

  <?php if ($error): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
  <?php endif; ?>

  <form method="POST" action="edit.php?id=<?php echo $id; ?>">
    <div class="mb-3">
      <label for="title" class="form-label">Título</label>
      <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($book['title']); ?>" required />
    </div>
    <div class="mb-3">
      <label for="author" class="form-label">Autor</label>
      <input type="text" class="form-control" id="author" name="author" value="<?php echo htmlspecialchars($book['author']); ?>" required />
    </div>
    <div class="mb-3">
      <label for="year" class="form-label">Año</label>
      <input type="number" class="form-control" id="year" name="year" value="<?php echo htmlspecialchars($book['year']); ?>" min="0" />
    </div>
    <div class="mb-3">
      <label for="genre" class="form-label">Género</label>
      <input type="text" class="form-control" id="genre" name="genre" value="<?php echo htmlspecialchars($book['genre']); ?>" />
    </div>
    <div class="mb-3">
      <label for="quantity" class="form-label">Cantidad</label>
      <input type="number" class="form-control" id="quantity" name="quantity" value="<?php echo htmlspecialchars($book['quantity']); ?>" min="0" required />
    </div>
    <button type="submit" class="btn btn-primary">Actualizar</button>
    <a href="list.php" class="btn btn-secondary">Cancelar</a>
  </form>
</div>

</body>
</html>
