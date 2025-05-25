<?php
session_start();
include 'header.php';
include 'db.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$rol_id = $_SESSION['rol_id'];

// Consulta según el rol
$query = "SELECT t.*, u.nombre as asignado_a 
          FROM tareas t 
          LEFT JOIN usuarios u ON t.usuario_id = u.id ";

if ($rol_id == 2) { // Gerente
    $query .= "WHERE t.creador_id = ? OR t.usuario_id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("ii", $usuario_id, $usuario_id);
} else { // Miembro del equipo
    $query .= "WHERE t.usuario_id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $usuario_id);
}

$stmt->execute();
$resultado = $stmt->get_result();

echo "<div class='container mt-4'>";
echo "<div class='d-flex justify-content-between align-items-center mb-4'>";
echo "<h2>Mis Tareas</h2>";
if ($rol_id == 2) {
    echo "<button class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#nuevaTareaModal'>Nueva Tarea</button>";
}
echo "</div>";

echo "<div class='table-responsive'>";
echo "<table class='table table-hover'>";
echo "<thead class='table-dark'>";
echo "<tr>";
echo "<th>Título</th>";
echo "<th>Descripción</th>";
echo "<th>Estado</th>";
if ($rol_id == 2) echo "<th>Asignado a</th>";
echo "<th>Acciones</th>";
echo "</tr>";
echo "</thead><tbody>";

while ($fila = $resultado->fetch_assoc()) {
    echo "<tr data-id='" . $fila['id'] . "'>";
    echo "<td>" . htmlspecialchars($fila['titulo']) . "</td>";
    echo "<td>" . htmlspecialchars($fila['descripcion']) . "</td>";
    echo "<td><span class='badge bg-" . 
         ($fila['estado'] == 'Pendiente' ? 'warning' : 
         ($fila['estado'] == 'En proceso' ? 'info' : 'success')) . 
         "'>" . htmlspecialchars($fila['estado']) . "</span></td>";
    if ($rol_id == 2) echo "<td>" . htmlspecialchars($fila['asignado_a']) . "</td>";
    echo "<td>";
    echo "<button class='btn btn-sm btn-info me-1' onclick='editarTarea(" . $fila['id'] . ")'><i class='bi bi-pencil'></i></button>";
    if ($rol_id == 2) {
        echo "<button class='btn btn-sm btn-danger' onclick='eliminarTarea(" . $fila['id'] . ")'><i class='bi bi-trash'></i></button>";
    }
    echo "</td>";
    echo "</tr>";
}

echo "</tbody></table>";
echo "</div>";
echo "</div>";
?>

<!-- Modal para Nueva Tarea -->
<div class="modal fade" id="nuevaTareaModal" tabindex="-1" aria-labelledby="nuevaTareaModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="nuevaTareaModalLabel">Nueva Tarea</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="formNuevaTarea">
          <input type="hidden" name="action" value="create">
          <div class="mb-3">
            <label for="titulo" class="form-label">Título</label>
            <input type="text" class="form-control" id="titulo" name="titulo" required>
          </div>
          <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
          </div>
          <div class="mb-3">
            <label for="usuario_id" class="form-label">Asignar a</label>
            <select class="form-control" id="usuario_id" name="usuario_id" required>
              <?php
              $query_usuarios = "SELECT id, nombre FROM usuarios WHERE rol_id = 3";
              $resultado_usuarios = $conexion->query($query_usuarios);
              while ($usuario = $resultado_usuarios->fetch_assoc()) {
                echo "<option value='" . $usuario['id'] . "'>" . htmlspecialchars($usuario['nombre']) . "</option>";
              }
              ?>
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" onclick="guardarNuevaTarea()">Guardar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal para Editar Tarea -->
<div class="modal fade" id="editarTareaModal" tabindex="-1" aria-labelledby="editarTareaModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editarTareaModalLabel">Editar Tarea</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="formEditarTarea">
          <input type="hidden" name="action" value="update">
          <input type="hidden" id="editTareaId" name="id">
          <div class="mb-3">
            <label for="editTituloTarea" class="form-label">Título</label>
            <input type="text" class="form-control" id="editTituloTarea" name="titulo" required>
          </div>
          <div class="mb-3">
            <label for="editDescripcionTarea" class="form-label">Descripción</label>
            <textarea class="form-control" id="editDescripcionTarea" name="descripcion" rows="3" required></textarea>
          </div>
          <div class="mb-3">
            <label for="editEstadoTarea" class="form-label">Estado</label>
            <select class="form-control" id="editEstadoTarea" name="estado" required>
              <option value="Pendiente">Pendiente</option>
              <option value="En proceso">En proceso</option>
              <option value="Completado">Completado</option>
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" onclick="actualizarTarea()">Guardar cambios</button>
      </div>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>