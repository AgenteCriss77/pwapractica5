<?php
session_start();
include 'db.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit();
}

$response = ['success' => false, 'message' => 'Operación no válida'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch($action) {
        case 'create':
            if ($_SESSION['rol_id'] == 2) { // Solo gerentes pueden crear tareas
                $titulo = $_POST['titulo'];
                $descripcion = $_POST['descripcion'];
                $usuario_id = $_POST['usuario_id'];
                $creador_id = $_SESSION['usuario_id'];
                
                $stmt = $conexion->prepare("INSERT INTO tareas (titulo, descripcion, usuario_id, creador_id, estado) VALUES (?, ?, ?, ?, 'Pendiente')");
                $stmt->bind_param("ssii", $titulo, $descripcion, $usuario_id, $creador_id);
                
                if ($stmt->execute()) {
                    $response = ['success' => true, 'message' => 'Tarea creada exitosamente'];
                } else {
                    $response = ['success' => false, 'message' => 'Error al crear la tarea'];
                }
            }
            break;
            
        case 'update':
            $id = $_POST['id'];
            $titulo = $_POST['titulo'];
            $descripcion = $_POST['descripcion'];
            $estado = $_POST['estado'];
            
            // Verificar permisos
            $stmt = $conexion->prepare("SELECT usuario_id FROM tareas WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $tarea = $result->fetch_assoc();
            
            if ($tarea && ($_SESSION['rol_id'] == 2 || $tarea['usuario_id'] == $_SESSION['usuario_id'])) {
                $stmt = $conexion->prepare("UPDATE tareas SET titulo = ?, descripcion = ?, estado = ? WHERE id = ?");
                $stmt->bind_param("sssi", $titulo, $descripcion, $estado, $id);
                
                if ($stmt->execute()) {
                    $response = ['success' => true, 'message' => 'Tarea actualizada exitosamente'];
                } else {
                    $response = ['success' => false, 'message' => 'Error al actualizar la tarea'];
                }
            }
            break;
            
        case 'delete':
            if ($_SESSION['rol_id'] == 2) { // Solo gerentes pueden eliminar tareas
                $id = $_POST['id'];
                
                $stmt = $conexion->prepare("DELETE FROM tareas WHERE id = ?");
                $stmt->bind_param("i", $id);
                
                if ($stmt->execute()) {
                    $response = ['success' => true, 'message' => 'Tarea eliminada exitosamente'];
                } else {
                    $response = ['success' => false, 'message' => 'Error al eliminar la tarea'];
                }
            }
            break;
            
        case 'update_status':
            $id = $_POST['id'];
            $estado = $_POST['estado'];
            
            // Verificar que el usuario puede actualizar esta tarea
            $stmt = $conexion->prepare("SELECT usuario_id FROM tareas WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $tarea = $result->fetch_assoc();
            
            if ($tarea && ($_SESSION['rol_id'] == 2 || $tarea['usuario_id'] == $_SESSION['usuario_id'])) {
                $stmt = $conexion->prepare("UPDATE tareas SET estado = ? WHERE id = ?");
                $stmt->bind_param("si", $estado, $id);
                
                if ($stmt->execute()) {
                    $response = ['success' => true, 'message' => 'Estado actualizado exitosamente'];
                } else {
                    $response = ['success' => false, 'message' => 'Error al actualizar el estado'];
                }
            }
            break;
    }
}

header('Content-Type: application/json');
echo json_encode($response);
?>