<?php
// obtener_publicaciones.php

header('Content-Type: application/json; charset=utf-8');

// Conexión a la base de datos (centralizada)
require_once __DIR__ . '/conexion.php';

try {

    $sql = "SELECT * FROM publicaciones WHERE status = 'publicado' ORDER BY fecha_publicacion DESC";
    $stmt = $pdo->query($sql);
    $publicaciones = $stmt->fetchAll();

    echo json_encode([
        'success' => true,
        'data' => $publicaciones
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error de conexión a la base de datos. Por favor, asegúrese de haber ejecutado el archivo publicaciones.sql en phpMyAdmin.'
    ]);
}
?>
