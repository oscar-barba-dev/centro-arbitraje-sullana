<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

$mensaje_ok  = '';
$mensaje_err = '';

// Conexión a la base de datos (centralizada)
require_once __DIR__ . '/conexion.php';

try {

    $accion = $_GET['accion'] ?? '';

    // ── APROBAR SOLICITUD ──────────────────────────────────────────────────────
    if ($accion === 'aprobar' && isset($_GET['id'])) {
        $id  = (int) $_GET['id'];
        $sol = $pdo->prepare("SELECT * FROM solicitudes_boletin WHERE id = :id AND estado = 'pendiente'");
        $sol->execute([':id' => $id]);
        $row = $sol->fetch();
        if ($row) {
            // Insertar en suscriptores (ignorar si correo duplicado)
            $ins = $pdo->prepare("INSERT IGNORE INTO suscriptores_boletin (nombre, correo, telefono) VALUES (:nombre, :correo, :telefono)");
            $ins->execute([':nombre' => $row['nombre'], ':correo' => $row['correo'], ':telefono' => $row['telefono']]);
            // Marcar solicitud como aprobada
            $upd = $pdo->prepare("UPDATE solicitudes_boletin SET estado = 'aprobado' WHERE id = :id");
            $upd->execute([':id' => $id]);
            $mensaje_ok = 'Solicitud aprobada. El suscriptor ha sido añadido.';
        }
    }

    // ── ELIMINAR SOLICITUD ─────────────────────────────────────────────────────
    if ($accion === 'del_sol' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("DELETE FROM solicitudes_boletin WHERE id = :id");
        $stmt->execute([':id' => (int)$_GET['id']]);
        $mensaje_ok = 'Solicitud eliminada correctamente.';
    }

    // ── ELIMINAR SUSCRIPTOR ────────────────────────────────────────────────────
    if ($accion === 'del_sus' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("DELETE FROM suscriptores_boletin WHERE id = :id");
        $stmt->execute([':id' => (int)$_GET['id']]);
        $mensaje_ok = 'Suscriptor eliminado correctamente.';
    }

    // ── AGREGAR SUSCRIPTOR MANUALMENTE ────────────────────────────────────────
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'agregar') {
        $nombre   = trim($_POST['nombre'] ?? '');
        $correo   = trim($_POST['correo'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        if (empty($nombre) || empty($correo) || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $mensaje_err = 'Nombre y correo válidos son obligatorios.';
        } else {
            $ins = $pdo->prepare("INSERT INTO suscriptores_boletin (nombre, correo, telefono) VALUES (:nombre, :correo, :telefono)");
            try {
                $ins->execute([':nombre' => $nombre, ':correo' => $correo, ':telefono' => $telefono]);
                $mensaje_ok = 'Suscriptor añadido manualmente.';
            } catch (PDOException $e) {
                $mensaje_err = 'El correo ya existe en la lista de suscriptores.';
            }
        }
    }

    // ── ACTUALIZAR SUSCRIPTOR ─────────────────────────────────────────────────
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'editar') {
        $id       = (int)($_POST['id'] ?? 0);
        $nombre   = trim($_POST['nombre'] ?? '');
        $correo   = trim($_POST['correo'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        if (empty($nombre) || empty($correo) || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $mensaje_err = 'Nombre y correo válidos son obligatorios.';
        } else {
            $upd = $pdo->prepare("UPDATE suscriptores_boletin SET nombre = :nombre, correo = :correo, telefono = :telefono WHERE id = :id");
            $upd->execute([':nombre' => $nombre, ':correo' => $correo, ':telefono' => $telefono, ':id' => $id]);
            $mensaje_ok = 'Suscriptor actualizado correctamente.';
        }
    }

    // ── LEER DATOS ────────────────────────────────────────────────────────────
    $solicitudes  = $pdo->query("SELECT * FROM solicitudes_boletin ORDER BY fecha_solicitud DESC")->fetchAll();
    $suscriptores = $pdo->query("SELECT * FROM suscriptores_boletin ORDER BY fecha_suscripcion DESC")->fetchAll();

    // Suscriptor a editar (si viene en GET)
    $editar = null;
    if (isset($_GET['editar'])) {
        $e = $pdo->prepare("SELECT * FROM suscriptores_boletin WHERE id = :id");
        $e->execute([':id' => (int)$_GET['editar']]);
        $editar = $e->fetch();
    }

} catch (PDOException $e) {
    die("Error de base de datos: " . $e->getMessage());
}

// Helper badge estado
function badge($estado) {
    return match($estado) {
        'pendiente' => '<span class="px-2 py-1 text-xs font-bold bg-amber-100 text-amber-800 rounded-full">Pendiente</span>',
        'aprobado'  => '<span class="px-2 py-1 text-xs font-bold bg-emerald-100 text-emerald-800 rounded-full">Aprobado</span>',
        'rechazado' => '<span class="px-2 py-1 text-xs font-bold bg-red-100 text-red-800 rounded-full">Rechazado</span>',
        default     => $estado,
    };
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boletín | Panel Admin — Centro de Arbitraje</title>
    <link rel="icon" type="image/png" href="img/logo.png">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { theme: { extend: { colors: { primary: '#064E3B', secondary: '#D4AF37' } } } }</script>
</head>
<body class="bg-stone-100 min-h-screen flex">

    <!-- Sidebar -->
    <aside class="w-64 bg-primary text-white flex flex-col shadow-2xl fixed h-full z-10">
        <div class="p-6 flex flex-col items-center border-b border-emerald-800/50 mb-6">
            <div class="bg-emerald-950 p-3 rounded-2xl mb-3 shadow-inner">
                <i class="ph-fill ph-scales text-secondary text-3xl"></i>
            </div>
            <h2 class="font-bold text-lg text-center tracking-wide">Centro Arbitraje</h2>
            <span class="text-secondary text-xs font-semibold uppercase tracking-widest mt-1">Panel Admin</span>
        </div>
        <nav class="flex-1 px-4 space-y-2">
            <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-emerald-100 hover:bg-emerald-800 hover:text-white font-medium transition-colors">
                <i class="ph-bold ph-squares-four text-xl"></i> Inicio
            </a>
            <a href="admin_mensajes.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-emerald-100 hover:bg-emerald-800 hover:text-white font-medium transition-colors">
                <i class="ph-bold ph-chat-circle-text text-xl"></i> Mensajes
            </a>
            <a href="admin_publicaciones.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-emerald-100 hover:bg-emerald-800 hover:text-white font-medium transition-colors">
                <i class="ph-bold ph-book-open text-xl"></i> Publicaciones
            </a>
            <a href="admin_boletin.php" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-secondary text-primary font-bold shadow-md">
                <i class="ph-bold ph-newspaper text-xl"></i> Boletín
            </a>
        </nav>
        <div class="p-4 border-t border-emerald-800/50">
            <a href="logout.php" class="flex items-center gap-3 w-full px-4 py-3 rounded-xl text-red-300 hover:bg-red-900/40 hover:text-red-200 transition-all font-medium">
                <i class="ph-bold ph-sign-out text-xl"></i> Cerrar Sesión
            </a>
        </div>
    </aside>

    <!-- Contenido Principal -->
    <main class="flex-1 ml-64 p-10">
        <div class="max-w-6xl mx-auto">

            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-primary mb-1">Gestión del Boletín</h1>
                    <p class="text-stone-500">Administre las solicitudes y los suscriptores activos.</p>
                </div>
                <button onclick="document.getElementById('modal-agregar').classList.remove('hidden')"
                    class="flex items-center gap-2 bg-primary text-white px-5 py-3 rounded-xl font-bold hover:bg-emerald-800 transition-colors shadow-lg">
                    <i class="ph-bold ph-plus text-xl"></i> Agregar Suscriptor
                </button>
            </div>

            <!-- Alertas -->
            <?php if ($mensaje_ok): ?>
                <div class="bg-emerald-50 text-emerald-800 p-4 rounded-xl font-medium mb-6 border border-emerald-200 flex items-center gap-2">
                    <i class="ph-fill ph-check-circle text-xl"></i> <?= htmlspecialchars($mensaje_ok) ?>
                </div>
            <?php endif; ?>
            <?php if ($mensaje_err): ?>
                <div class="bg-red-50 text-red-800 p-4 rounded-xl font-medium mb-6 border border-red-200 flex items-center gap-2">
                    <i class="ph-fill ph-warning-circle text-xl"></i> <?= htmlspecialchars($mensaje_err) ?>
                </div>
            <?php endif; ?>

            <!-- Modal editar suscriptor -->
            <?php if ($editar): ?>
            <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center" id="modal-editar">
                <div class="bg-white rounded-3xl shadow-2xl p-10 w-full max-w-md">
                    <h2 class="text-2xl font-bold text-primary mb-6">Editar Suscriptor</h2>
                    <form method="POST" action="admin_boletin.php" class="space-y-4">
                        <input type="hidden" name="accion" value="editar">
                        <input type="hidden" name="id" value="<?= $editar['id'] ?>">
                        <div>
                            <label class="block text-sm font-bold text-stone-700 mb-1">Nombre</label>
                            <input type="text" name="nombre" value="<?= htmlspecialchars($editar['nombre']) ?>" required
                                class="w-full px-4 py-3 bg-stone-50 border-2 border-stone-200 rounded-xl focus:border-secondary focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-stone-700 mb-1">Correo</label>
                            <input type="email" name="correo" value="<?= htmlspecialchars($editar['correo']) ?>" required
                                class="w-full px-4 py-3 bg-stone-50 border-2 border-stone-200 rounded-xl focus:border-secondary focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-stone-700 mb-1">Teléfono</label>
                            <input type="tel" name="telefono" value="<?= htmlspecialchars($editar['telefono']) ?>" required
                                class="w-full px-4 py-3 bg-stone-50 border-2 border-stone-200 rounded-xl focus:border-secondary focus:outline-none">
                        </div>
                        <div class="flex gap-3 pt-2">
                            <button type="submit" class="flex-1 bg-primary text-white py-3 rounded-xl font-bold hover:bg-emerald-800 transition-colors">
                                Guardar Cambios
                            </button>
                            <a href="admin_boletin.php" class="flex-1 text-center bg-stone-100 text-stone-700 py-3 rounded-xl font-bold hover:bg-stone-200 transition-colors">
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            <?php endif; ?>

            <!-- Modal agregar suscriptor -->
            <div id="modal-agregar" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
                <div class="bg-white rounded-3xl shadow-2xl p-10 w-full max-w-md">
                    <h2 class="text-2xl font-bold text-primary mb-6">Agregar Suscriptor</h2>
                    <form method="POST" action="admin_boletin.php" class="space-y-4">
                        <input type="hidden" name="accion" value="agregar">
                        <div>
                            <label class="block text-sm font-bold text-stone-700 mb-1">Nombre</label>
                            <input type="text" name="nombre" required placeholder="Nombre completo"
                                class="w-full px-4 py-3 bg-stone-50 border-2 border-stone-200 rounded-xl focus:border-secondary focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-stone-700 mb-1">Correo Electrónico</label>
                            <input type="email" name="correo" required placeholder="ejemplo@correo.com"
                                class="w-full px-4 py-3 bg-stone-50 border-2 border-stone-200 rounded-xl focus:border-secondary focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-stone-700 mb-1">Teléfono</label>
                            <input type="tel" name="telefono" required placeholder="Ej. 987 654 321"
                                class="w-full px-4 py-3 bg-stone-50 border-2 border-stone-200 rounded-xl focus:border-secondary focus:outline-none">
                        </div>
                        <div class="flex gap-3 pt-2">
                            <button type="submit" class="flex-1 bg-primary text-white py-3 rounded-xl font-bold hover:bg-emerald-800 transition-colors">
                                Agregar
                            </button>
                            <button type="button" onclick="document.getElementById('modal-agregar').classList.add('hidden')"
                                class="flex-1 bg-stone-100 text-stone-700 py-3 rounded-xl font-bold hover:bg-stone-200 transition-colors">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- TABS -->
            <div class="mb-6 flex gap-2 border-b border-stone-200">
                <button onclick="showTab('solicitudes')" id="tab-solicitudes"
                    class="tab-btn px-6 py-3 font-bold text-sm rounded-t-xl border-b-2 border-primary text-primary bg-white">
                    <i class="ph-bold ph-envelope-open mr-1"></i>
                    Solicitudes Pendientes
                    <span class="ml-2 bg-amber-400 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                        <?= count(array_filter($solicitudes, fn($s) => $s['estado'] === 'pendiente')) ?>
                    </span>
                </button>
                <button onclick="showTab('suscriptores')" id="tab-suscriptores"
                    class="tab-btn px-6 py-3 font-bold text-sm rounded-t-xl border-b-2 border-transparent text-stone-500 hover:text-primary transition-colors">
                    <i class="ph-bold ph-users mr-1"></i>
                    Suscriptores Aprobados
                    <span class="ml-2 bg-emerald-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                        <?= count($suscriptores) ?>
                    </span>
                </button>
            </div>

            <!-- PANEL: Solicitudes -->
            <div id="panel-solicitudes" class="tab-panel">
                <div class="bg-white rounded-3xl shadow-sm border border-stone-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-stone-50 border-b border-stone-200 text-stone-500 font-semibold uppercase text-xs tracking-wider">
                                    <th class="p-5">Fecha</th>
                                    <th class="p-5">Nombre</th>
                                    <th class="p-5">Correo</th>
                                    <th class="p-5">Teléfono</th>
                                    <th class="p-5 text-center">Estado</th>
                                    <th class="p-5 text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stone-100">
                                <?php if (empty($solicitudes)): ?>
                                    <tr><td colspan="5" class="p-8 text-center text-stone-400">No hay solicitudes registradas.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($solicitudes as $sol): ?>
                                        <tr class="hover:bg-stone-50/50 transition-colors">
                                            <td class="p-5 text-sm text-stone-500 whitespace-nowrap">
                                                <?= date('d/m/Y H:i', strtotime($sol['fecha_solicitud'])) ?>
                                            </td>
                                            <td class="p-5 font-bold text-primary"><?= htmlspecialchars($sol['nombre']) ?></td>
                                            <td class="p-5 text-sm text-stone-700"><?= htmlspecialchars($sol['correo']) ?></td>
                                            <td class="p-5 text-sm text-stone-600"><?= htmlspecialchars($sol['telefono']) ?></td>
                                            <td class="p-5 text-center"><?= badge($sol['estado']) ?></td>
                                            <td class="p-5 text-center">
                                                <div class="flex justify-center gap-2">
                                                    <?php if ($sol['estado'] === 'pendiente'): ?>
                                                        <a href="admin_boletin.php?accion=aprobar&id=<?= $sol['id'] ?>"
                                                            onclick="return confirm('¿Aprobar esta solicitud?')"
                                                            class="inline-flex items-center gap-1 px-3 py-1.5 bg-emerald-500 text-white text-xs font-bold rounded-lg hover:bg-emerald-600 transition-colors">
                                                            <i class="ph-bold ph-check"></i> Aprobar
                                                        </a>
                                                    <?php endif; ?>
                                                    <a href="admin_boletin.php?accion=del_sol&id=<?= $sol['id'] ?>"
                                                        onclick="return confirm('¿Eliminar esta solicitud?')"
                                                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-50 text-red-600 text-xs font-bold rounded-lg hover:bg-red-100 transition-colors">
                                                        <i class="ph-bold ph-trash"></i> Eliminar
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- PANEL: Suscriptores -->
            <div id="panel-suscriptores" class="tab-panel hidden">
                <div class="bg-white rounded-3xl shadow-sm border border-stone-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-stone-50 border-b border-stone-200 text-stone-500 font-semibold uppercase text-xs tracking-wider">
                                    <th class="p-5">Fecha</th>
                                    <th class="p-5">Nombre</th>
                                    <th class="p-5">Correo</th>
                                    <th class="p-5">Teléfono</th>
                                    <th class="p-5 text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stone-100">
                                <?php if (empty($suscriptores)): ?>
                                    <tr><td colspan="4" class="p-8 text-center text-stone-400">No hay suscriptores aprobados aún.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($suscriptores as $sus): ?>
                                        <tr class="hover:bg-stone-50/50 transition-colors">
                                            <td class="p-5 text-sm text-stone-500 whitespace-nowrap">
                                                <?= date('d/m/Y', strtotime($sus['fecha_suscripcion'])) ?>
                                            </td>
                                            <td class="p-5 font-bold text-primary"><?= htmlspecialchars($sus['nombre']) ?></td>
                                            <td class="p-5 text-sm text-stone-700"><?= htmlspecialchars($sus['correo']) ?></td>
                                            <td class="p-5 text-sm text-stone-600"><?= htmlspecialchars($sus['telefono']) ?></td>
                                            <td class="p-5 text-center">
                                                <div class="flex justify-center gap-2">
                                                    <a href="admin_boletin.php?editar=<?= $sus['id'] ?>"
                                                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-stone-100 text-stone-700 text-xs font-bold rounded-lg hover:bg-stone-200 transition-colors">
                                                        <i class="ph-bold ph-pencil"></i> Editar
                                                    </a>
                                                    <a href="admin_boletin.php?accion=del_sus&id=<?= $sus['id'] ?>"
                                                        onclick="return confirm('¿Eliminar este suscriptor?')"
                                                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-50 text-red-600 text-xs font-bold rounded-lg hover:bg-red-100 transition-colors">
                                                        <i class="ph-bold ph-trash"></i> Eliminar
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <script>
        function showTab(tab) {
            document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('hidden'));
            document.querySelectorAll('.tab-btn').forEach(b => {
                b.classList.remove('border-primary', 'text-primary', 'bg-white');
                b.classList.add('border-transparent', 'text-stone-500');
            });
            document.getElementById('panel-' + tab).classList.remove('hidden');
            const btn = document.getElementById('tab-' + tab);
            btn.classList.add('border-primary', 'text-primary', 'bg-white');
            btn.classList.remove('border-transparent', 'text-stone-500');
        }
    </script>
</body>
</html>
