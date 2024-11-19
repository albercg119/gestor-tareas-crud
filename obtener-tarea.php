<?php
require_once 'config/database.php';

if (isset($_GET['id'])) {
    try {
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        
        $stmt = $conn->prepare("SELECT id, nombre, descripcion FROM tareas WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $tarea = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($tarea) {
            header('Content-Type: application/json');
            echo json_encode($tarea);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Tarea no encontrada']);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Error del servidor']);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'ID no proporcionado']);
}