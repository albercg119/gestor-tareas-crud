<?php
require_once 'config/database.php';

// Verificar si es una petición POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Obtener y sanitizar los datos del formulario
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
        $descripcion = filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_STRING);

        // Validaciones
        if (empty($id) || empty($nombre)) {
            throw new Exception("El ID y nombre de la tarea son requeridos");
        }

        // Preparar la consulta SQL
        $stmt = $conn->prepare("UPDATE tareas SET nombre = :nombre, descripcion = :descripcion WHERE id = :id");
        
        // Vincular parámetros
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);

        // Ejecutar la consulta
        $stmt->execute();

        // Verificar si la tarea existe
        if ($stmt->rowCount() === 0) {
            throw new Exception("No se encontró la tarea especificada");
        }

        // Redirigir con mensaje de éxito
        header("Location: index.php?mensaje=Tarea actualizada exitosamente");
        exit();

    } catch (Exception $e) {
        // Redirigir con mensaje de error
        header("Location: index.php?error=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    // Si no es POST, redirigir al index
    header("Location: index.php");
    exit();
}