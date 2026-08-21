-- Script SQL para la tabla de Publicaciones
-- Base de Datos: centro_arbitraje_db

CREATE TABLE IF NOT EXISTS publicaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    portada TEXT NOT NULL,
    categoria VARCHAR(100) NOT NULL,
    fecha_publicacion DATE NOT NULL,
    resumen TEXT,
    enlace TEXT,
    status VARCHAR(50) DEFAULT 'publicado',
    
    -- Índices para búsquedas y filtros rápidos
    INDEX idx_categoria (categoria),
    INDEX idx_status (status),
    INDEX idx_fecha (fecha_publicacion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar datos de muestra (Categorías: Instructivo, Libros)
INSERT INTO publicaciones (titulo, portada, categoria, fecha_publicacion, resumen, enlace, status) VALUES
(
    'Guía Práctica de Arbitraje Institucional', 
    'https://images.unsplash.com/photo-1589829085413-56de8ae18c73?auto=format&fit=crop&q=80&w=600', 
    'Instructivo', 
    '2023-10-15', 
    'Conoce el paso a paso para iniciar un proceso arbitral en nuestro centro.', 
    '/descargas/guia-arbitraje.pdf', 
    'publicado'
),
(
    'Reglamento de Arbitraje 2024', 
    'https://images.unsplash.com/photo-1505664159854-2328109d7224?auto=format&fit=crop&q=80&w=600', 
    'Instructivo', 
    '2024-01-10', 
    'Documento oficial con las normativas actualizadas para el presente año.', 
    '/descargas/reglamento-2024.pdf', 
    'publicado'
),
(
    'El Arbitraje Comercial en el Perú: Casos Prácticos', 
    'https://images.unsplash.com/photo-1589391886645-d51941baf7fb?auto=format&fit=crop&q=80&w=600', 
    'Libros', 
    '2022-05-20', 
    'Análisis profundo de la jurisprudencia arbitral comercial.', 
    '/descargas/libro-arbitraje-comercial.pdf', 
    'publicado'
),
(
    'Manual de Junta de Resolución de Disputas (JRD)', 
    'https://images.unsplash.com/photo-1450101499163-c8848c66cb85?auto=format&fit=crop&q=80&w=600', 
    'Libros', 
    '2023-11-05', 
    'Guía completa sobre la implementación y funcionamiento de las JRD en obras públicas.', 
    '/descargas/manual-jrd.pdf', 
    'publicado'
);