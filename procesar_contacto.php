<?php
// procesar_contacto.php

header('Content-Type: application/json; charset=utf-8');

// Conexión a la base de datos (centralizada)
require_once __DIR__ . '/conexion.php';

try {
    // Verificar que sea una petición POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método no permitido. Use POST.');
    }

    // Sanitizar y capturar datos
    $nombre  = trim(filter_input(INPUT_POST, 'nombre',  FILTER_SANITIZE_STRING) ?? '');
    $correo  = trim(filter_input(INPUT_POST, 'correo',  FILTER_SANITIZE_EMAIL)  ?? '');
    $mensaje = trim(filter_input(INPUT_POST, 'mensaje', FILTER_SANITIZE_STRING) ?? '');
    // telefono es opcional en el formulario de contacto
    $telefono = trim(filter_input(INPUT_POST, 'telefono', FILTER_SANITIZE_STRING) ?? '');

    // Validaciones básicas
    if (empty($nombre) || empty($correo) || empty($mensaje)) {
        throw new Exception('Nombre, correo y mensaje son obligatorios.');
    }

    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('El formato del correo electrónico no es válido.');
    }

    // Insertar en la base de datos
    $stmt = $pdo->prepare(
        "INSERT INTO contact_messages (nombre, telefono, correo, mensaje)
         VALUES (:nombre, :telefono, :correo, :mensaje)"
    );
    $stmt->execute([
        ':nombre'   => $nombre,
        ':telefono' => $telefono,
        ':correo'   => $correo,
        ':mensaje'  => $mensaje,
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Su mensaje ha sido enviado exitosamente. Nos comunicaremos con usted muy pronto.'
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    error_log('[procesar_contacto] PDOException: ' . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Error al guardar el mensaje. Verifique que la base de datos esté activa.'
    ]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
