function cargarDatosEdicion(id) {
    // Aquí puedes agregar la lógica para cargar los datos de la tarea en el modal
    fetch(`obtener-tarea.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('editTaskId').value = data.id;
            document.getElementById('editTaskName').value = data.nombre;
            document.getElementById('editTaskDescription').value = data.descripcion;
        })
        .catch(error => console.error('Error:', error));
}

function confirmarEliminacion(id) {
    if (confirm('¿Estás seguro de que deseas eliminar esta tarea?')) {
        window.location.href = `eliminar-tarea.php?id=${id}`;
    }
}

// Validación de formularios
(function () {
    'use strict';
    var forms = document.querySelectorAll('.needs-validation');
    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();