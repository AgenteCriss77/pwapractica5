-- Crear base de datos
CREATE DATABASE IF NOT EXISTS gestion_tareas;
USE gestion_tareas;

-- Crear tabla de roles
CREATE TABLE roles (
    rol_id INT AUTO_INCREMENT PRIMARY KEY,
    rol_nombre VARCHAR(30) NOT NULL
);

-- Insertar roles predefinidos
INSERT INTO roles (rol_nombre) VALUES 
('Administrador'),
('Gerente de proyecto'),
('Miembro del equipo');

-- Crear tabla de usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    contraseña VARCHAR(255) NOT NULL,
    rol_id INT NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (rol_id) REFERENCES roles(rol_id) ON DELETE RESTRICT
);

-- Crear tabla de tareas
CREATE TABLE tareas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    descripcion TEXT,
    estado ENUM('Pendiente', 'En proceso', 'Completado') DEFAULT 'Pendiente',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    fecha_vencimiento DATE,
    prioridad ENUM('Baja', 'Media', 'Alta') DEFAULT 'Media',
    usuario_id INT NOT NULL,
    creador_id INT NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (creador_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Crear tabla de comentarios de tareas
CREATE TABLE comentarios_tarea (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tarea_id INT NOT NULL,
    usuario_id INT NOT NULL,
    comentario TEXT NOT NULL,
    fecha_comentario TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (tarea_id) REFERENCES tareas(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Crear tabla de historial de cambios
CREATE TABLE historial_tareas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tarea_id INT NOT NULL,
    usuario_id INT NOT NULL,
    accion VARCHAR(50) NOT NULL,
    descripcion TEXT,
    fecha_cambio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (tarea_id) REFERENCES tareas(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Crear índices para optimizar búsquedas
CREATE INDEX idx_tareas_usuario ON tareas(usuario_id);
CREATE INDEX idx_tareas_estado ON tareas(estado);
CREATE INDEX idx_tareas_prioridad ON tareas(prioridad);
CREATE INDEX idx_usuarios_rol ON usuarios(rol_id);

-- Crear vistas para facilitar consultas comunes
CREATE VIEW vista_tareas_completa AS
SELECT 
    t.id,
    t.titulo,
    t.descripcion,
    t.estado,
    t.fecha_creacion,
    t.fecha_vencimiento,
    t.prioridad,
    u.nombre as asignado_a,
    c.nombre as creado_por,
    r.rol_nombre
FROM tareas t
JOIN usuarios u ON t.usuario_id = u.id
JOIN usuarios c ON t.creador_id = c.id
JOIN roles r ON u.rol_id = r.rol_id;

-- Crear vista para estadísticas de tareas por usuario
CREATE VIEW vista_estadisticas_usuario AS
SELECT 
    u.id,
    u.nombre,
    r.rol_nombre,
    COUNT(t.id) as total_tareas,
    SUM(CASE WHEN t.estado = 'Completado' THEN 1 ELSE 0 END) as tareas_completadas,
    SUM(CASE WHEN t.estado = 'En proceso' THEN 1 ELSE 0 END) as tareas_en_proceso,
    SUM(CASE WHEN t.estado = 'Pendiente' THEN 1 ELSE 0 END) as tareas_pendientes
FROM usuarios u
LEFT JOIN tareas t ON u.id = t.usuario_id
JOIN roles r ON u.rol_id = r.rol_id
GROUP BY u.id;