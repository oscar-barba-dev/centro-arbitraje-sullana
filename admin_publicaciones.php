<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Conexión a la base de datos (centralizada)
require_once __DIR__ . '/conexion.php';

// Valida que un archivo subido tenga una extensión y un tipo MIME permitidos
function archivoEsValido(array $archivo, array $extensionesPermitidas, array $mimesPermitidos): bool {
    $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, $extensionesPermitidas, true)) {
        return false;
    }
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $archivo['tmp_name']);
    finfo_close($finfo);
    return in_array($mime, $mimesPermitidos, true);
}

$extensionesImagen = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
$mimesImagen = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
$extensionesDocumento = ['pdf', 'doc', 'docx'];
$mimesDocumento = [
    'application/pdf',
    'application/msword',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
];

try {

    $msg = '';

    // Eliminar publicación
    if (isset($_GET['delete'])) {
        $stmt = $pdo->prepare("DELETE FROM publicaciones WHERE id = :id");
        $stmt->execute([':id' => $_GET['delete']]);
        header("Location: admin_publicaciones.php?msg=deleted");
        exit;
    }

    // Crear o Editar publicación
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $titulo = $_POST['titulo'];
        $categoria = $_POST['categoria'];
        $fecha = $_POST['fecha_publicacion'];
        $resumen = $_POST['resumen'];
        $status = $_POST['status'];

        $id_pub = !empty($_POST['id']) ? $_POST['id'] : null;
        $portada = '';
        $enlace = '';

        // Si es edición, obtener los archivos actuales primero
        if ($id_pub) {
            $stmt = $pdo->prepare("SELECT portada, enlace FROM publicaciones WHERE id = ?");
            $stmt->execute([$id_pub]);
            $current = $stmt->fetch();
            $portada = $current['portada'];
            $enlace = $current['enlace'];
        }

        // Manejo de subida de imagen
        if (isset($_FILES['portada_file']) && $_FILES['portada_file']['error'] === UPLOAD_ERR_OK) {
            if (!archivoEsValido($_FILES['portada_file'], $extensionesImagen, $mimesImagen)) {
                throw new Exception('La imagen de portada debe ser JPG, PNG, GIF o WEBP.');
            }
            $uploadDir = 'uploads/portadas/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            $filename = time() . '_' . preg_replace("/[^a-zA-Z0-9.]/", "_", basename($_FILES['portada_file']['name']));
            $targetPath = $uploadDir . $filename;
            if (move_uploaded_file($_FILES['portada_file']['tmp_name'], $targetPath)) {
                $portada = $targetPath;
            }
        }

        // Manejo de subida de archivo (enlace)
        if (isset($_FILES['enlace_file']) && $_FILES['enlace_file']['error'] === UPLOAD_ERR_OK) {
            if (!archivoEsValido($_FILES['enlace_file'], $extensionesDocumento, $mimesDocumento)) {
                throw new Exception('El archivo de descarga debe ser PDF, DOC o DOCX.');
            }
            $uploadDirDoc = 'uploads/archivos/';
            if (!is_dir($uploadDirDoc)) mkdir($uploadDirDoc, 0755, true);
            $filenameDoc = time() . '_' . preg_replace("/[^a-zA-Z0-9.]/", "_", basename($_FILES['enlace_file']['name']));
            $targetPathDoc = $uploadDirDoc . $filenameDoc;
            if (move_uploaded_file($_FILES['enlace_file']['tmp_name'], $targetPathDoc)) {
                $enlace = $targetPathDoc;
            }
        }

        if ($id_pub) {
            // Actualizar
            $stmt = $pdo->prepare("UPDATE publicaciones SET titulo=?, portada=?, categoria=?, fecha_publicacion=?, resumen=?, enlace=?, status=? WHERE id=?");
            $stmt->execute([$titulo, $portada, $categoria, $fecha, $resumen, $enlace, $status, $id_pub]);
            $msg = 'updated';
        } else {
            // Crear
            if ($portada === '') {
                $portada = 'https://images.unsplash.com/photo-1589829085413-56de8ae18c73?auto=format&fit=crop&q=80&w=600';
            }
            if ($enlace === '') {
                $enlace = '#';
            }
            $stmt = $pdo->prepare("INSERT INTO publicaciones (titulo, portada, categoria, fecha_publicacion, resumen, enlace, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$titulo, $portada, $categoria, $fecha, $resumen, $enlace, $status]);
            $msg = 'created';
        }
        header("Location: admin_publicaciones.php?msg=$msg");
        exit;
    }

    // Obtener todas las publicaciones
    $stmt = $pdo->query("SELECT * FROM publicaciones ORDER BY fecha_publicacion DESC");
    $publicaciones = $stmt->fetchAll();

} catch (PDOException $e) {
    die("Error de base de datos: " . $e->getMessage());
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Publicaciones | Panel Admin</title>
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
            <a href="admin_publicaciones.php" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-secondary text-primary font-bold shadow-md">
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
                    <h1 class="text-3xl font-bold text-primary mb-2">Gestión de Publicaciones</h1>
                    <p class="text-stone-500">Administre el catálogo de libros, manuales y reglamentos.</p>
                </div>
                <button onclick="openModal()" class="bg-secondary text-primary px-5 py-2.5 rounded-xl flex items-center gap-2 hover:bg-yellow-500 transition-colors font-bold shadow-md">
                    <i class="ph-bold ph-plus text-lg"></i> Nueva Publicación
                </button>
            </div>

            <?php if(isset($_GET['msg'])): ?>
                <div class="bg-emerald-50 text-emerald-800 p-4 rounded-xl font-medium mb-6 border border-emerald-200 flex items-center gap-2">
                    <i class="ph-fill ph-check-circle text-xl"></i> 
                    <?php 
                        if($_GET['msg'] == 'created') echo "Publicación guardada exitosamente.";
                        if($_GET['msg'] == 'updated') echo "Publicación actualizada exitosamente.";
                        if($_GET['msg'] == 'deleted') echo "Publicación eliminada correctamente.";
                    ?>
                </div>
            <?php endif; ?>

            <div class="bg-white rounded-3xl shadow-sm border border-stone-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-stone-50 border-b border-stone-200 text-stone-500 font-semibold uppercase text-xs tracking-wider">
                                <th class="p-5">Publicación</th>
                                <th class="p-5">Categoría / Estado</th>
                                <th class="p-5">Fecha</th>
                                <th class="p-5 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100">
                            <?php if (empty($publicaciones)): ?>
                                <tr><td colspan="4" class="p-8 text-center text-stone-400">No hay publicaciones registradas.</td></tr>
                            <?php else: ?>
                                <?php foreach ($publicaciones as $pub): ?>
                                    <tr class="hover:bg-stone-50/50 transition-colors">
                                        <td class="p-5 flex items-center gap-4">
                                            <div class="w-12 h-16 bg-stone-200 rounded overflow-hidden border border-stone-200 shrink-0">
                                                <img src="<?php echo htmlspecialchars($pub['portada']); ?>" class="w-full h-full object-cover">
                                            </div>
                                            <div>
                                                <p class="font-bold text-primary"><?php echo htmlspecialchars($pub['titulo']); ?></p>
                                                <a href="<?php echo htmlspecialchars($pub['enlace']); ?>" target="_blank" class="text-xs text-blue-600 hover:underline">Ver Enlace</a>
                                            </div>
                                        </td>
                                        <td class="p-5">
                                            <span class="bg-stone-100 text-stone-700 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">
                                                <?php echo htmlspecialchars($pub['categoria']); ?>
                                            </span>
                                            <br>
                                            <span class="inline-block mt-2 w-2 h-2 rounded-full <?php echo $pub['status'] == 'publicado' ? 'bg-green-500' : 'bg-yellow-500'; ?>"></span>
                                            <span class="text-xs text-stone-500 ml-1 capitalize"><?php echo htmlspecialchars($pub['status']); ?></span>
                                        </td>
                                        <td class="p-5 text-sm text-stone-500 font-medium">
                                            <?php echo date('d/m/Y', strtotime($pub['fecha_publicacion'])); ?>
                                        </td>
                                        <td class="p-5 text-center">
                                            <div class="flex justify-center gap-2">
                                                <button onclick='editPub(<?php echo json_encode($pub); ?>)' class="p-2 text-stone-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                                    <i class="ph-bold ph-pencil-simple text-xl"></i>
                                                </button>
                                                <a href="admin_publicaciones.php?delete=<?php echo $pub['id']; ?>" onclick="return confirm('¿Eliminar publicación?');" class="p-2 text-stone-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                                    <i class="ph-bold ph-trash text-xl"></i>
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
    </main>

    <!-- Modal Formulario -->
    <div id="modal" class="fixed inset-0 bg-stone-900/60 backdrop-blur-sm hidden items-center justify-center p-4 z-50">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden border border-stone-200 flex flex-col max-h-[90vh]">
            <div class="p-6 border-b border-stone-100 flex justify-between items-center bg-stone-50">
                <h3 id="modal-title" class="font-serif font-bold text-xl text-primary">Nueva Publicación</h3>
                <button type="button" onclick="closeModal()" class="text-stone-400 hover:text-stone-800"><i class="ph-bold ph-x text-2xl"></i></button>
            </div>
            
            <div class="p-6 overflow-y-auto">
                <form id="pub-form" method="POST" action="admin_publicaciones.php" enctype="multipart/form-data" class="space-y-5">
                    <input type="hidden" name="id" id="pub_id">
                    
                    <div class="grid grid-cols-2 gap-5">
                        <div class="col-span-2">
                            <label class="block text-sm font-bold text-stone-700 mb-2">Título de la Publicación *</label>
                            <input required type="text" name="titulo" id="pub_titulo" class="w-full px-4 py-3 bg-stone-50 border border-stone-200 rounded-xl focus:ring-2 focus:ring-primary outline-none">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-bold text-stone-700 mb-2">Imagen de la Portada <span id="portada_hint" class="text-xs text-stone-400 font-normal ml-2"></span></label>
                            <input type="file" accept="image/*" name="portada_file" id="pub_portada" class="w-full px-4 py-3 bg-stone-50 border border-stone-200 rounded-xl focus:ring-2 focus:ring-primary outline-none file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-900 hover:file:bg-emerald-100 cursor-pointer">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-stone-700 mb-2">Categoría *</label>
                            <select required name="categoria" id="pub_categoria" class="w-full px-4 py-3 bg-stone-50 border border-stone-200 rounded-xl focus:ring-2 focus:ring-primary outline-none">
                                <option value="Instructivo">Instructivo</option>
                                <option value="Libros">Libros</option>
                                <option value="Normativa">Normativa</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-stone-700 mb-2">Fecha de Publicación *</label>
                            <input required type="date" name="fecha_publicacion" id="pub_fecha" class="w-full px-4 py-3 bg-stone-50 border border-stone-200 rounded-xl focus:ring-2 focus:ring-primary outline-none">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-bold text-stone-700 mb-2">Archivo de Descarga (PDF, etc.) <span id="enlace_hint" class="text-xs text-stone-400 font-normal ml-2"></span></label>
                            <input type="file" name="enlace_file" id="pub_enlace" class="w-full px-4 py-3 bg-stone-50 border border-stone-200 rounded-xl focus:ring-2 focus:ring-primary outline-none file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-900 hover:file:bg-emerald-100 cursor-pointer">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-bold text-stone-700 mb-2">Resumen</label>
                            <textarea name="resumen" id="pub_resumen" rows="3" class="w-full px-4 py-3 bg-stone-50 border border-stone-200 rounded-xl focus:ring-2 focus:ring-primary outline-none resize-none"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-stone-700 mb-2">Estado</label>
                            <select required name="status" id="pub_status" class="w-full px-4 py-3 bg-stone-50 border border-stone-200 rounded-xl focus:ring-2 focus:ring-primary outline-none">
                                <option value="publicado">Publicado</option>
                                <option value="borrador">Borrador</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>

            <div class="p-6 border-t border-stone-100 flex justify-end gap-3 bg-stone-50">
                <button type="button" onclick="closeModal()" class="px-6 py-3 font-bold text-stone-500 hover:text-stone-800 transition-colors">Cancelar</button>
                <button form="pub-form" type="submit" class="px-8 py-3 font-bold bg-primary text-white rounded-xl hover:bg-emerald-800 transition-colors shadow-lg">Guardar</button>
            </div>
        </div>
    </div>

    <script>
        const modal = document.getElementById('modal');
        
        function openModal() {
            document.getElementById('pub-form').reset();
            document.getElementById('pub_id').value = '';
            document.getElementById('modal-title').innerText = 'Nueva Publicación';
            document.getElementById('portada_hint').innerText = '(* Obligatorio)';
            document.getElementById('enlace_hint').innerText = '';
            document.getElementById('pub_portada').required = true;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function editPub(pub) {
            document.getElementById('pub-form').reset();
            document.getElementById('pub_id').value = pub.id;
            document.getElementById('pub_titulo').value = pub.titulo;
            document.getElementById('pub_categoria').value = pub.categoria;
            document.getElementById('pub_fecha').value = pub.fecha_publicacion;
            document.getElementById('pub_resumen').value = pub.resumen;
            document.getElementById('pub_status').value = pub.status;
            
            document.getElementById('portada_hint').innerText = '(Opcional: Suba una nueva imagen solo si desea reemplazar la actual)';
            document.getElementById('enlace_hint').innerText = '(Opcional: Suba un nuevo archivo solo si desea reemplazar el actual)';
            document.getElementById('pub_portada').required = false;
            
            document.getElementById('modal-title').innerText = 'Editar Publicación';
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    </script>
</body>
</html>
