<?php
// procesar_suscripcion.php — Recibe la solicitud de suscripción al boletín

header('Content-Type: application/json; charset=utf-8');

// Conexión a la base de datos (centralizada)
require_once __DIR__ . '/conexion.php';

try {
    // Verificar que sea una petición POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método no permitido.');
    }

    // Sanitizar y capturar datos
    $nombre   = trim(filter_input(INPUT_POST, 'nombre',   FILTER_SANITIZE_STRING) ?? '');
    $correo   = trim(filter_input(INPUT_POST, 'correo',   FILTER_SANITIZE_EMAIL)  ?? '');
    $telefono = trim(filter_input(INPUT_POST, 'telefono', FILTER_SANITIZE_STRING) ?? '');

    // Validaciones básicas
    if (empty($nombre) || empty($correo) || empty($telefono)) {
        throw new Exception('Nombre, correo y teléfono son obligatorios.');
    }
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('El formato del correo no es válido.');
    }

    // Verificar si el correo ya está suscrito (aprobado)
    $check = $pdo->prepare(
        "SELECT id FROM suscriptores_boletin WHERE correo = :correo AND activo = 1"
    );
    $check->execute([':correo' => $correo]);
    if ($check->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Este correo ya está suscrito al boletín.']);
        exit;
    }

    // Verificar si ya hay una solicitud pendiente para ese correo
    $check2 = $pdo->prepare(
        "SELECT id FROM solicitudes_boletin WHERE correo = :correo AND estado = 'pendiente'"
    );
    $check2->execute([':correo' => $correo]);
    if ($check2->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Ya existe una solicitud pendiente con ese correo. Pronto la revisaremos.']);
        exit;
    }

    // Insertar la nueva solicitud
    $stmt = $pdo->prepare(
        "INSERT INTO solicitudes_boletin (nombre, correo, telefono)
         VALUES (:nombre, :correo, :telefono)"
    );
    $stmt->execute([
        ':nombre'   => $nombre,
        ':correo'   => $correo,
        ':telefono' => $telefono,
    ]);

    echo json_encode([
        'success' => true,
        'message' => '¡Solicitud enviada! Pronto revisaremos su solicitud y le confirmaremos su suscripción.'
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    error_log('[procesar_suscripcion] PDOException: ' . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Error al registrar la solicitud. Verifique que la base de datos esté activa.'
    ]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
