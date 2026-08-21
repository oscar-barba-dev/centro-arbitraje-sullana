-- ============================================================
-- Sistema de Boletín | Centro de Arbitraje de Sullana
-- Ejecutar en phpMyAdmin sobre la base de datos: centro_arbitraje_db
-- ============================================================

USE centro_arbitraje_db;

-- Tabla 1: Solicitudes pendientes del público
CREATE TABLE IF NOT EXISTS solicitudes_boletin (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    nombre          VARCHAR(100) NOT NULL,
    correo          VARCHAR(150) NOT NULL,
    telefono        VARCHAR(20)  NOT NULL,
    fecha_solicitud TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estado          ENUM('pendiente', 'aprobado', 'rechazado') DEFAULT 'pendiente',

    INDEX idx_correo (correo),
    INDEX idx_estado (estado),
    INDEX idx_fecha  (fecha_solicitud)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla 2: Suscriptores aprobados por el administrador
CREATE TABLE IF NOT EXISTS suscriptores_boletin (
    id                 INT AUTO_INCREMENT PRIMARY KEY,
    nombre             VARCHAR(100) NOT NULL,
    correo             VARCHAR(150) NOT NULL UNIQUE,
    telefono           VARCHAR(20)  NOT NULL,
    fecha_suscripcion  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    activo             BOOLEAN DEFAULT TRUE,

    INDEX idx_correo (correo),
    INDEX idx_activo (activo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
