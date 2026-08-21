<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Conexión a la base de datos (centralizada)
require_once __DIR__ . '/conexion.php';

try {

    // Eliminar mensaje si se solicita
    if (isset($_GET['delete'])) {
        $stmt = $pdo->prepare("DELETE FROM contact_messages WHERE id = :id");
        $stmt->execute([':id' => $_GET['delete']]);
        header("Location: admin_mensajes.php?msg=deleted");
        exit;
    }

    // Obtener todos los mensajes
    $stmt = $pdo->query("SELECT * FROM contact_messages ORDER BY fecha_envio DESC");
    $mensajes = $stmt->fetchAll();

} catch (PDOException $e) {
    die("Error de base de datos: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mensajes | Panel Admin</title>
    <link rel="icon" type="image/png" href="img/logo.png">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { theme: { extend: { colors: { primary: '#064E3B', secondary: '#D4AF37' } } } }</script>
</head>
<body class="bg-stone-100 min-h-screen flex">

    <!-- Sidebar (Mismo de Dashboard) -->
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
            <a href="admin_mensajes.php" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-secondary text-primary font-bold shadow-md">
                <i class="ph-bold ph-chat-circle-text text-xl"></i> Mensajes
            </a>
            <a href="admin_publicaciones.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-emerald-100 hover:bg-emerald-800 hover:text-white font-medium transition-colors">
                <i class="ph-bold ph-book-open text-xl"></i> Publicaciones
            </a>
            <a href="admin_boletin.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-emerald-100 hover:bg-emerald-800 hover:text-white font-medium transition-colors">
                <i class="ph-bold ph-newspaper text-xl"></i> Boletín
            </a>
        </nav>
        <div class="p-4 border-t border-emerald-800/50">
            <a href="logout.php" class="flex items-center gap-3 w-full px-4 py-3 rounded-xl text-red-300 hover:bg-red-900/40 hover:text-red-200 transition-all font-medium">
                <i class="ph-bold ph-sign-out text-xl"></i> Cerrar Sesión
            </a>
        </div>
    </aside>

    <main class="flex-1 ml-64 p-10">
        <div class="max-w-6xl mx-auto">
            
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-primary mb-2">Mensajes de Contacto</h1>
                    <p class="text-stone-500">Bandeja de entrada de consultas recibidas desde la web.</p>
                </div>
            </div>

            <?php if(isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
                <div class="bg-emerald-50 text-emerald-800 p-4 rounded-xl font-medium mb-6 border border-emerald-200 flex items-center gap-2">
                    <i class="ph-fill ph-check-circle text-xl"></i> Mensaje eliminado correctamente.
                </div>
            <?php endif; ?>

            <div class="bg-white rounded-3xl shadow-sm border border-stone-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-stone-50 border-b border-stone-200 text-stone-500 font-semibold uppercase text-xs tracking-wider">
                                <th class="p-5">Fecha</th>
                                <th class="p-5">Remitente</th>
                                <th class="p-5">Contacto</th>
                                <th class="p-5 w-1/3">Mensaje</th>
                                <th class="p-5 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100">
                            <?php if (empty($mensajes)): ?>
                                <tr>
                                    <td colspan="5" class="p-8 text-center text-stone-400">No hay mensajes en la bandeja de entrada.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($mensajes as $msg): ?>
                                    <tr class="hover:bg-stone-50/50 transition-colors">
                                        <td class="p-5 text-sm text-stone-500 font-medium whitespace-nowrap">
                                            <?php echo date('d/m/Y', strtotime($msg['fecha_envio'])); ?>
                                        </td>
                                        <td class="p-5">
                                            <p class="font-bold text-primary"><?php echo htmlspecialchars($msg['nombre']); ?></p>
                                        </td>
                                        <td class="p-5 text-sm">
                                            <p class="text-stone-800"><?php echo htmlspecialchars($msg['correo']); ?></p>
                                            <p class="text-stone-500"><?php echo htmlspecialchars($msg['telefono']); ?></p>
                                        </td>
                                        <td class="p-5">
                                            <p class="text-sm text-stone-600"><?php echo htmlspecialchars($msg['mensaje']); ?></p>
                                        </td>
                                        <td class="p-5 text-center">
                                            <a href="admin_mensajes.php?delete=<?php echo $msg['id']; ?>" onclick="return confirm('¿Eliminar mensaje?');" class="inline-block p-2 text-stone-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Eliminar mensaje">
                                                <i class="ph-bold ph-trash text-xl"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>
</body>
</html>
