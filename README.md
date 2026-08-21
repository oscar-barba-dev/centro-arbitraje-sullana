# Centro de Arbitraje — Cámara de Comercio de Sullana

Sitio web institucional y panel administrativo del Centro de Arbitraje de la Cámara de Comercio de Sullana. Incluye las páginas informativas del centro (servicios, tarifas, publicaciones, nosotros, contacto), un sistema de gestión de documentos oficiales (reglamentos, estatuto, código de ética, nóminas de árbitros y secretarios) y un panel de administración privado para gestionar mensajes de contacto, publicaciones y suscripciones al boletín informativo.

**Sitio en producción:** [centroarbitrajecamarasullana.com](https://centroarbitrajecamarasullana.com)

<img width="1296" height="617" alt="image" src="https://github.com/user-attachments/assets/24360ab1-f463-4103-8459-eb1d2d1914e7" />
<img width="1208" height="627" alt="image" src="https://github.com/user-attachments/assets/bd9d17f7-d4ff-49d3-ae11-90d198b1bdbf" />

## Características principales

- **Páginas institucionales:** Inicio, Nosotros, Servicios, Tarifas, Publicaciones y Contacto, con diseño responsivo (Tailwind CSS).
- **Calculadora de tarifas:** herramienta interactiva en la sección de Tarifas para estimar los costos del proceso arbitral según la cuantía.
- **Gestor de documentos oficiales:** descarga y visualización en línea (`documento.php`) de reglamentos, estatuto, código de ética y nóminas de árbitros/secretarios en PDF, servidos de forma controlada mediante un catálogo interno (no por ruta directa).
- **Formulario de contacto:** envío de mensajes que quedan almacenados en base de datos para su gestión desde el panel administrativo.
- **Suscripción a boletín:** los visitantes pueden solicitar su suscripción; las solicitudes quedan pendientes de aprobación por un administrador.
- **Panel de administración protegido por sesión** (`login.php` / `dashboard.php`) con tres módulos:
  - **Mensajes:** gestión de los mensajes recibidos por el formulario de contacto.
  - **Publicaciones:** creación y edición del contenido que se muestra públicamente en la sección de Publicaciones.
  - **Boletín:** aprobación de solicitudes de suscripción al boletín informativo.

## Tecnologías utilizadas

| Tecnología | Uso en el proyecto |
|---|---|
| PHP (PDO) | Backend: conexión a base de datos, autenticación, procesamiento de formularios y servido de documentos |
| MySQL | Base de datos (usuarios, mensajes de contacto, publicaciones, suscriptores del boletín) |
| HTML5 | Maquetado de las páginas públicas del sitio |
| Tailwind CSS (CDN) | Estilos y diseño responsivo |
| JavaScript | Interactividad en el cliente (formularios, calculadora de tarifas) |
| Phosphor Icons | Iconografía de la interfaz |
| React / TypeScript (.tsx) | Componentes de referencia incluidos en el repositorio para una eventual migración del frontend |
| XAMPP (Apache + MySQL) | Entorno de desarrollo local |

## Instalación en local

1. **Requisitos previos:** tener instalado [XAMPP](https://www.apachefriends.org/) (o Apache + PHP 8+ y MySQL por separado).
2. **Clonar el repositorio** dentro de la carpeta `htdocs` de XAMPP:
   ```bash
   git clone https://github.com/<tu-usuario>/centro-arbitraje-sullana.git C:/xampp/htdocs/centro
   ```
3. **Iniciar Apache y MySQL** desde el Panel de Control de XAMPP.
4. **Crear la base de datos:** en phpMyAdmin, crea una base de datos llamada `centro_arbitraje_db` y ejecuta en orden los scripts SQL incluidos en el proyecto: `database.sql`, `admin.example.sql` (renómbralo o copia su contenido, reemplazando los valores de ejemplo), `boletin.sql` y `publicaciones.sql`.
5. **Configurar la conexión a la base de datos:** copia `conexion.example.php` como `conexion.php` y coloca tus propias credenciales locales:
   ```bash
   cp conexion.example.php conexion.php
   ```
6. **Acceder al sitio** en tu navegador:
   - Sitio público: `http://localhost/centro/index.html`
   - Panel de administración: `http://localhost/centro/login.php`

## Autor

**Oscar Alberto Barba Alvarado**
📧 oscarbarba159@gmail.com
