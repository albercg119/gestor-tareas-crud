<?php
require_once 'config/database.php';

// Verificar si se recibió un ID
if (isset($_GET['id'])) {
    try {
        // Obtener y sanitizar el ID
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

        // Validar que el ID no esté vacío
        if (empty($id)) {
            throw new Exception("ID de tarea inválido");
        }

        // Preparar la consulta SQL
        $stmt = $conn->prepare("DELETE FROM tareas WHERE id = :id");
        
        // Vincular parámetro
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Ejecutar la consulta
        $stmt->execute();

        // Verificar si la tarea existía
        if ($stmt->rowCount() === 0) {
            throw new Exception("No se encontró la tarea especificada");
        }

        // Redirigir con mensaje de éxito
        header("Location: index.php?mensaje=Tarea eliminada exitosamente");
        exit();

    } catch (Exception $e) {
        // Redirigir con mensaje de error
        header("Location: index.php?error=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    // Si no hay ID, redirigir al index
    header("Location: index.php");
    exit();
}
?>