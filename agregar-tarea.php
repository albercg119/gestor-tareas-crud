<?php
require_once 'config/database.php';

// Verificar si es una petición POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Obtener y sanitizar los datos del formulario
        $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
        $descripcion = filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_STRING);

        // Validar que el nombre no esté vacío
        if (empty($nombre)) {
            throw new Exception("El nombre de la tarea es requerido");
        }

        // Preparar la consulta SQL
        $stmt = $conn->prepare("INSERT INTO tareas (nombre, descripcion) VALUES (:nombre, :descripcion)");
        
        // Vincular parámetros
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':descripcion', $descripcion);

        // Ejecutar la consulta
        $stmt->execute();

        // Redirigir con mensaje de éxito
        header("Location: index.php?mensaje=Tarea agregada exitosamente");
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