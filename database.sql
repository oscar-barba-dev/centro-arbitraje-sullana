-- Script SQL para la Base de Datos del Centro de Arbitraje de Sullana
-- Optimizado para entorno MySQL (XAMPP)

-- 1. Crear la base de datos si no existe
CREATE DATABASE IF NOT EXISTS centro_arbitraje_db
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE centro_arbitraje_db;

-- 2. Crear la tabla para almacenar de forma segura los mensajes de contacto
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    telefono VARCHAR(20) NOT NULL,
    correo VARCHAR(150) NOT NULL,
    mensaje TEXT NOT NULL,
    fecha_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    leido BOOLEAN DEFAULT FALSE,
    
    -- Índices para optimizar las consultas futuras desde un panel administrativo
    INDEX idx_correo (correo),
    INDEX idx_fecha (fecha_envio),
    INDEX idx_leido (leido)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Datos de prueba (Opcional - Quitar los comentarios para insertar un registro inicial)
-- INSERT INTO contact_messages (nombre, telefono, correo, mensaje) 
-- VALUES ('Juan Pérez', '987654321', 'juan.perez@email.com', 'Deseo información detallada sobre los aranceles para un arbitraje ad hoc institucional.');