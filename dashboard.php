<?php
session_start();
// Proteger la ruta
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Centro de Arbitraje</title>
    <link rel="icon" type="image/png" href="img/logo.png">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { colors: { primary: '#064E3B', secondary: '#D4AF37' } } } }
    </script>
</head>
<body class="bg-stone-100 min-h-screen flex">

    <!-- Sidebar Lateral -->
    <aside class="w-64 bg-primary text-white flex flex-col shadow-2xl fixed h-full z-10">
        <div class="p-6 flex flex-col items-center border-b border-emerald-800/50 mb-6">
            <div class="bg-emerald-950 p-3 rounded-2xl mb-3 shadow-inner">
                <i class="ph-fill ph-scales text-secondary text-3xl"></i>
            </div>
            <h2 class="font-bold text-lg text-center tracking-wide">Centro Arbitraje</h2>
            <span class="text-secondary text-xs font-semibold uppercase tracking-widest mt-1">Panel Admin</span>
        </div>

        <nav class="flex-1 px-4 space-y-2">
            <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-secondary text-primary font-bold shadow-md">
                <i class="ph-bold ph-squares-four text-xl"></i> Inicio
            </a>
            <a href="admin_mensajes.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-emerald-100 hover:bg-emerald-800 hover:text-white font-medium transition-colors">
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

    <!-- Contenido Principal -->
    <main class="flex-1 ml-64 p-10">
        <div class="max-w-6xl mx-auto">
            
            <h1 class="text-3xl font-bold text-primary mb-2">Bienvenido al Panel de Administración</h1>
            <p class="text-stone-500 mb-10 text-lg">Hola, <span class="font-bold text-stone-700"><?php echo htmlspecialchars($_SESSION['admin_user']); ?></span>. Gestione los mensajes y publicaciones de forma segura.</p>

            <div class="bg-white p-12 rounded-3xl shadow-sm border border-stone-200 flex flex-col items-center text-center mt-6">
                <div class="w-24 h-24 bg-emerald-50 rounded-full flex items-center justify-center mb-6">
                    <i class="ph-fill ph-shield-check text-5xl text-primary"></i>
                </div>
                <h2 class="text-2xl font-bold text-stone-800 mb-4">Sistema Seguro (PHP Nativo)</h2>
                <p class="text-stone-600 leading-relaxed mb-8 max-w-2xl text-lg">
                    Se encuentra en una conexión autenticada. Utilice el menú lateral para acceder a la bandeja de entrada de contactos o al catálogo de libros y normativas institucionales.
                </p>
                <div class="flex gap-4">
                    <a href="admin_mensajes.php" class="bg-stone-100 text-stone-700 px-6 py-3 rounded-xl font-bold hover:bg-stone-200 transition-colors flex items-center gap-2">
                        <i class="ph-bold ph-chat-circle-text"></i> Ver Mensajes
                    </a>
                    <a href="admin_publicaciones.php" class="bg-primary text-white px-6 py-3 rounded-xl font-bold hover:bg-emerald-800 transition-colors flex items-center gap-2 shadow-lg">
                        <i class="ph-bold ph-book-open"></i> Gestionar Publicaciones
                    </a>
                </div>
            </div>

        </div>
    </main>

</body>
</html>
