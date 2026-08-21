<?php
session_start();

// Si ya está logueado, redirigir al dashboard
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: dashboard.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Conexión a la base de datos (centralizada)
    require_once __DIR__ . '/conexion.php';

    try {

        $user = filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_STRING);
        $pass = $_POST['password'];

        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = :usuario LIMIT 1");
        $stmt->execute([':usuario' => $user]);
        $userData = $stmt->fetch();

        // Verificar si el usuario existe y la contraseña es correcta (TEXTO PLANO)
        if ($userData && $pass === $userData['password_hash']) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_user'] = $userData['usuario'];
            $_SESSION['admin_role'] = $userData['rol'];
            
            header("Location: dashboard.php");
            exit;
        } else {
            $error = 'Usuario o contraseña incorrectos.';
        }

    } catch (PDOException $e) {
        $error = 'Error de conexión a la base de datos. Verifique phpMyAdmin.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Panel de Administración</title>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { primary: '#064E3B', secondary: '#D4AF37' }
                }
            }
        }
    </script>
</head>
<body class="bg-stone-100 min-h-screen flex items-center justify-center p-6">

    <div class="bg-white p-10 rounded-3xl shadow-2xl w-full max-w-md border border-stone-200">
        <div class="flex flex-col items-center mb-8">
            <div class="bg-primary p-4 rounded-2xl mb-4 shadow-inner">
                <i class="ph-fill ph-scales text-secondary text-4xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-primary">Panel de Administración</h1>
            <p class="text-xs text-stone-500 uppercase tracking-widest mt-1">Centro de Arbitraje</p>
        </div>

        <?php if ($error): ?>
            <div class="bg-red-50 text-red-600 p-4 rounded-xl text-sm font-medium mb-6 text-center border border-red-100 flex items-center justify-center gap-2">
                <i class="ph-fill ph-warning-circle text-lg"></i> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="login.php" class="space-y-6">
            <div>
                <label class="block text-sm font-bold text-stone-700 mb-2">Usuario</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="ph-fill ph-user text-stone-400 text-xl"></i>
                    </div>
                    <input type="text" name="usuario" required class="w-full pl-12 pr-4 py-3 bg-stone-50 border border-stone-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:bg-white transition-all text-stone-800 font-medium" placeholder="Ingrese su usuario">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-bold text-stone-700 mb-2">Contraseña</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="ph-fill ph-lock-key text-stone-400 text-xl"></i>
                    </div>
                    <input type="password" name="password" required class="w-full pl-12 pr-4 py-3 bg-stone-50 border border-stone-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary focus:bg-white transition-all text-stone-800 font-medium" placeholder="••••••••">
                </div>
            </div>

            <button type="submit" class="w-full bg-primary text-white font-bold py-4 rounded-xl hover:bg-emerald-800 transition-colors shadow-lg flex justify-center items-center gap-2 text-lg">
                Ingresar al Panel <i class="ph-bold ph-arrow-right"></i>
            </button>
        </form>
    </div>

</body>
</html>
