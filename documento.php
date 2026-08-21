<?php
/**
 * documento.php
 * Sirve los archivos PDF con los headers correctos para que funcionen
 * tanto en desarrollo local (XAMPP) como en servidor de producción.
 *
 * Uso:
 *   Ver en navegador: documento.php?file=estatuto&view=1
 *   Descargar:        documento.php?file=estatuto
 */

// ── Catálogo de documentos disponibles ─────────────────────────────────────
// Solo los documentos en esta lista pueden ser solicitados (seguridad)
$documentos = [
    'reglamento_procesal'  => [
        'archivo' => 'reglamento_procesal.pdf',
        'nombre'  => 'Reglamento_Procesal_Centro_Arbitraje_Sullana.pdf',
        'titulo'  => 'Reglamento Procesal',
    ],
    'reglamento_aranceles' => [
        'archivo' => 'reglamento_aranceles.pdf',
        'nombre'  => 'Reglamento_Aranceles_CAMCO_Sullana.pdf',
        'titulo'  => 'Reglamento de Aranceles',
    ],
    'estatuto' => [
        'archivo' => 'estatuto.pdf',
        'nombre'  => 'Estatuto_Centro_Arbitraje_Sullana.pdf',
        'titulo'  => 'Estatuto del Centro de Arbitraje',
    ],
    'codigo_etica' => [
        'archivo' => 'codigo_etica.pdf',
        'nombre'  => 'Codigo_Etica_Centro_Arbitraje_Sullana.pdf',
        'titulo'  => 'Código de Ética',
    ],
    'nomina_arbitros' => [
        'archivo' => 'nomina_arbitros.pdf',
        'nombre'  => 'Listado_Nomina_Arbitros.pdf',
        'titulo'  => 'Nómina de Árbitros',
    ],
    'nomina_secretarios' => [
        'archivo' => 'nomina_secretarios.pdf',
        'nombre'  => 'Nomina_Secretarios_Arbitrales.pdf',
        'titulo'  => 'Nómina de Secretarios Arbitrales',
    ],
    'requisitos_arbitros' => [
        'archivo' => 'requisitos_arbitros.pdf',
        'nombre'  => 'Requisitos_Nomina_Arbitros.pdf',
        'titulo'  => 'Requisitos para ser Árbitro',
    ],
];

// ── Validar parámetro ───────────────────────────────────────────────────────
$key = $_GET['file'] ?? '';

if (empty($key) || !array_key_exists($key, $documentos)) {
    http_response_code(404);
    echo '<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8">
    <title>Documento no encontrado</title>
    <link rel="icon" type="image/png" href="img/logo.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{colors:{primary:"#064E3B",secondary:"#D4AF37"}}}}</script>
    </head><body class="bg-stone-50 flex items-center justify-center min-h-screen">
    <div class="text-center p-12">
        <div class="text-8xl mb-6">📄</div>
        <h1 class="text-3xl font-bold text-primary mb-4">Documento no encontrado</h1>
        <p class="text-stone-500 mb-8">El documento solicitado no existe o no está disponible.</p>
        <a href="javascript:history.back()" class="bg-primary text-white px-6 py-3 rounded-xl font-bold hover:bg-emerald-800 transition-colors">← Regresar</a>
    </div></body></html>';
    exit;
}

// ── Construir ruta absoluta del archivo ─────────────────────────────────────
$doc      = $documentos[$key];
$ruta     = __DIR__ . '/public/docs/' . $doc['archivo'];

if (!file_exists($ruta)) {
    http_response_code(404);
    echo '<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8">
    <title>Archivo no disponible</title>
    <link rel="icon" type="image/png" href="img/logo.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{colors:{primary:"#064E3B",secondary:"#D4AF37"}}}}</script>
    </head><body class="bg-stone-50 flex items-center justify-center min-h-screen">
    <div class="text-center p-12">
        <div class="text-8xl mb-6">⚠️</div>
        <h1 class="text-3xl font-bold text-primary mb-4">Archivo temporalmente no disponible</h1>
        <p class="text-stone-500 mb-8">El archivo PDF no se encuentra en el servidor en este momento.</p>
        <a href="javascript:history.back()" class="bg-primary text-white px-6 py-3 rounded-xl font-bold hover:bg-emerald-800 transition-colors">← Regresar</a>
    </div></body></html>';
    exit;
}

// ── ¿Ver en navegador o descargar? ─────────────────────────────────────────
$ver      = isset($_GET['view']) && $_GET['view'] == '1';
$tamano   = filesize($ruta);
$nombreDL = $doc['nombre'];

// ── Limpiar cualquier buffer antes de enviar headers ────────────────────────
while (ob_get_level()) {
    ob_end_clean();
}

// ── Enviar headers ──────────────────────────────────────────────────────────
header('Content-Type: application/pdf');
header('Content-Transfer-Encoding: binary');
header('Content-Length: ' . $tamano);
header('Cache-Control: public, must-revalidate, max-age=0');
header('Pragma: public');
header('Accept-Ranges: bytes');

if ($ver) {
    // Abrir inline en el navegador (visor PDF del navegador)
    header('Content-Disposition: inline; filename="' . $nombreDL . '"');
} else {
    // Forzar descarga
    header('Content-Disposition: attachment; filename="' . $nombreDL . '"');
}

// ── Enviar archivo ─────────────────────────────────────────────────────────
flush();
readfile($ruta);
exit;
