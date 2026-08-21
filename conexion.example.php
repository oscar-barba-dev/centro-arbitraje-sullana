<?php
// =============================================================================
// conexion.example.php — Plantilla de conexión a la base de datos
// Copia este archivo como "conexion.php" y coloca tus propias credenciales.
// El archivo "conexion.php" real NUNCA debe subirse a GitHub (ver .gitignore).
// =============================================================================

// --- Credenciales de la base de datos ---
define('DB_HOST',    'localhost');
define('DB_NAME',    'centro_arbitraje_db');
define('DB_USER',    'tu_usuario');
define('DB_PASS',    'tu_contraseña');
define('DB_CHARSET', 'utf8mb4');

// --- DSN (Data Source Name) ---
$dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;

// --- Opciones PDO recomendadas ---
$opciones = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,   // Lanza excepciones en errores
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,         // Devuelve arrays asociativos
    PDO::ATTR_EMULATE_PREPARES   => false,                    // Prepara consultas reales
];

// --- Crear la conexión ---
try {
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $opciones);
} catch (PDOException $e) {
    // En producción no se debe mostrar el mensaje técnico al usuario.
    // Se registra el error y se detiene la ejecución de forma segura.
    error_log('[ERROR BD] ' . $e->getMessage());
    http_response_code(500);

    // Si el archivo que incluyó este script espera JSON, devolver JSON.
    $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
    if (str_contains($accept, 'application/json') || str_contains($_SERVER['SCRIPT_NAME'] ?? '', 'api')) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success' => false,
            'message' => 'Error de conexión a la base de datos. Contacte al administrador.'
        ]);
    } else {
        echo '<p style="font-family:sans-serif;color:#b91c1c;padding:2rem;">
                <strong>Error crítico:</strong> No se pudo conectar a la base de datos.<br>
                Verifique que MySQL esté activo en XAMPP y que la base de datos
                <code>centro_arbitraje_db</code> exista.
              </p>';
    }
    exit;
}
