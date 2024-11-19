<?php
require_once 'config/database.php';

// Establecer el header para JSON
header('Content-Type: application/json');

try {
    // Si se proporciona un ID específico
    if (isset($_GET['id'])) {
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        
        // Preparar consulta para una tarea específica
        $stmt = $conn->prepare("SELECT * FROM tareas WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $tarea = $stmt->fetch();
        
        if (!$tarea) {
            throw new Exception("Tarea no encontrada");
        }
        
        echo json_encode($tarea);
    } 
    // Si se proporciona un término de búsqueda
    else if (isset($_GET['buscar'])) {
        $buscar = '%' . $_GET['buscar'] . '%';
        
        // Preparar consulta para búsqueda
        $stmt = $conn->prepare("SELECT * FROM tareas WHERE nombre LIKE :buscar OR descripcion LIKE :buscar");
        $stmt->bindParam(':buscar', $buscar);
        $stmt->execute();
        
        $tareas = $stmt->fetchAll();
        echo json_encode($tareas);
    }
    // Si no hay parámetros, devolver todas las tareas
    else {
        $stmt = $conn->query("SELECT * FROM tareas ORDER BY id DESC");
        $tareas = $stmt->fetchAll();
        echo json_encode($tareas);
    }
} catch (Exception $e) {
    // Devolver error en formato JSON
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}