<?php
require_once 'config/database.php';

// Lógica de búsqueda
try {
    $where = "";
    $params = [];

    if (isset($_GET['buscar']) && !empty($_GET['buscar'])) {
        $buscar = $_GET['buscar'];
        // Buscar por ID exacto si es un número
        if (is_numeric($buscar)) {
            $where = "WHERE id = :id OR nombre LIKE :buscar OR descripcion LIKE :buscar";
            $params[':id'] = $buscar;
            $params[':buscar'] = "%$buscar%";
        } else {
            $where = "WHERE nombre LIKE :buscar OR descripcion LIKE :buscar";
            $params[':buscar'] = "%$buscar%";
        }
    }

    // Construir y ejecutar la consulta
    $sql = "SELECT * FROM tareas " . $where . " ORDER BY id DESC";
    $stmt = $conn->prepare($sql);

    // Vincular parámetros
    foreach ($params as $param => $value) {
        $stmt->bindValue($param, $value);
    }

    $stmt->execute();
    $tareas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Error al cargar las tareas: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor de Tareas</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Sweet Alert -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- CSS personalizado -->
    <link href="/CRUD/css/style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-dark bg-primary">
        <div class="container">
            <span class="navbar-brand mb-0 h1">
                <i class="bi bi-check2-square"></i> Gestor de Tareas
            </span>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Buscador -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h1 class="h3">Mis Tareas</h1>
            </div>
            <div class="col-md-6">
                <form action="index.php" method="GET" class="search-form">
                    <div class="input-group">
                        <input type="text" 
                               class="form-control" 
                               name="buscar" 
                               placeholder="Buscar por ID, nombre o descripción..." 
                               value="<?php echo isset($_GET['buscar']) ? htmlspecialchars($_GET['buscar']) : ''; ?>">
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                        <?php if (isset($_GET['buscar']) && !empty($_GET['buscar'])): ?>
                            <a href="index.php" class="btn btn-secondary ms-2">
                                <i class="bi bi-x-circle"></i> Limpiar
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>

        <!-- Mensajes de búsqueda -->
        <?php if (isset($_GET['buscar']) && !empty($_GET['buscar'])): ?>
            <?php if (count($tareas) > 0): ?>
                <div class="alert alert-info">
                    Se encontraron <?php echo count($tareas); ?> resultado(s) para: "<?php echo htmlspecialchars($_GET['buscar']); ?>"
                </div>
            <?php else: ?>
                <div class="alert alert-warning">
                    No se encontraron tareas que coincidan con "<?php echo htmlspecialchars($_GET['buscar']); ?>"
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Nueva Tarea -->
        <div class="card mb-4">
            <div class="card-body">
                <h5>Nueva Tarea</h5>
                <div class="form-container">
                    <form action="agregar-tarea.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nombre de la tarea</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Descripción</label>
                            <textarea name="descripcion" class="form-control" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Guardar tarea
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Lista de Tareas -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Lista de Tareas</h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tareas as $tarea): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($tarea['id']); ?></td>
                                <td><?php echo htmlspecialchars($tarea['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($tarea['descripcion']); ?></td>
                                <td>
                                    <button class="btn btn-sm btn-warning me-1" 
                                            onclick="cargarDatosEdicion(<?php echo $tarea['id']; ?>)"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editModal">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" 
                                            data-id="<?php echo $tarea['id']; ?>"
                                            onclick="confirmarEliminacion(this)">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal de edición -->
        <div class="modal fade" id="editModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modificar Tarea</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="editar-tarea.php" method="POST" class="needs-validation" novalidate>
                        <div class="modal-body">
                            <input type="hidden" id="editTaskId" name="id">
                            <div class="mb-3">
                                <label for="editTaskName" class="form-label">Nombre de la tarea</label>
                                <input type="text" class="form-control" id="editTaskName" name="nombre" required>
                                <div class="invalid-feedback">
                                    Por favor ingresa un nombre para la tarea.
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="editTaskDescription" class="form-label">Descripción</label>
                                <textarea class="form-control" id="editTaskDescription" name="descripcion" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Guardar cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <!-- Sweet Alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
    <!-- Scripts personalizados -->
    <script>
    function confirmarEliminacion(button) {
        const id = button.getAttribute('data-id');
        
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción no se puede deshacer",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `eliminar-tarea.php?id=${id}`;
            }
        });
    }

    // Mostrar mensajes de éxito o error
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const mensaje = urlParams.get('mensaje');
        const error = urlParams.get('error');

        if (mensaje) {
            Swal.fire({
                title: '¡Éxito!',
                text: mensaje,
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
        }

        if (error) {
            Swal.fire({
                title: '¡Error!',
                text: error,
                icon: 'error'
            });
        }
    });

    function cargarDatosEdicion(id) {
        fetch(`obtener-tarea.php?id=${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('editTaskId').value = data.id;
                document.getElementById('editTaskName').value = data.nombre;
                document.getElementById('editTaskDescription').value = data.descripcion || '';
            })
            .catch(error => console.error('Error:', error));
    }
    </script>
</body>
</html>