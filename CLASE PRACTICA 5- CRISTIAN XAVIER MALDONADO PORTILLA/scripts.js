// Validación de formularios
document.addEventListener('DOMContentLoaded', function() {
    // Validación del formulario de registro
    const registroForm = document.querySelector('form[action="registro_procesar.php"]');
    if (registroForm) {
        registroForm.addEventListener('submit', function(e) {
            const password = this.querySelector('input[name="contrasena"]').value;
            if (password.length < 6) {
                e.preventDefault();
                alert('La contraseña debe tener al menos 6 caracteres');
            }
        });
    }

    // Validación del formulario de login
    const loginForm = document.querySelector('form[action="login_procesar.php"]');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            const email = this.querySelector('input[name="email"]').value;
            if (!email.includes('@')) {
                e.preventDefault();
                alert('Por favor, ingrese un email válido');
            }
        });
    }
});

// Funciones para gestión de tareas
function editarTarea(id) {
    // Obtener datos de la tarea
    const fila = document.querySelector(`tr[data-id="${id}"]`);
    const titulo = fila.querySelector('td:nth-child(1)').textContent;
    const descripcion = fila.querySelector('td:nth-child(2)').textContent;
    const estado = fila.querySelector('td:nth-child(3)').textContent;

    // Actualizar modal con los datos
    document.getElementById('editTituloTarea').value = titulo;
    document.getElementById('editDescripcionTarea').value = descripcion;
    document.getElementById('editEstadoTarea').value = estado;
    document.getElementById('editTareaId').value = id;

    // Mostrar modal
    const modal = new bootstrap.Modal(document.getElementById('editarTareaModal'));
    modal.show();
}

function eliminarTarea(id) {
    if (confirm('¿Está seguro de que desea eliminar esta tarea?')) {
        // Enviar solicitud para eliminar la tarea
        const formData = new FormData();
        formData.append('id', id);
        formData.append('action', 'delete');

        fetch('tareas_procesar.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Eliminar la fila de la tabla
                document.querySelector(`tr[data-id="${id}"]`).remove();
            } else {
                alert('Error al eliminar la tarea');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al procesar la solicitud');
        });
    }
}

// Función para actualizar el estado de una tarea
function actualizarEstadoTarea(id, nuevoEstado) {
    const formData = new FormData();
    formData.append('id', id);
    formData.append('estado', nuevoEstado);
    formData.append('action', 'update_status');

    fetch('tareas_procesar.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Actualizar el estado en la interfaz
            const estadoCell = document.querySelector(`tr[data-id="${id}"] td:nth-child(3)`);
            estadoCell.innerHTML = `<span class="badge bg-${getBadgeColor(nuevoEstado)}">${nuevoEstado}</span>`;
        } else {
            alert('Error al actualizar el estado');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al procesar la solicitud');
    });
}

// Función auxiliar para obtener el color del badge según el estado
function getBadgeColor(estado) {
    switch(estado.toLowerCase()) {
        case 'pendiente':
            return 'warning';
        case 'en progreso':
            return 'info';
        case 'completada':
            return 'success';
        default:
            return 'secondary';
    }
}


function guardarNuevaTarea() {
    const formData = new FormData(document.getElementById('formNuevaTarea'));
    
    fetch('tareas_procesar.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Tarea creada exitosamente');
            location.reload();
        } else {
            alert('Error al crear la tarea: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al procesar la solicitud');
    });
}