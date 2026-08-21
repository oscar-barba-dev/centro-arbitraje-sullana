-- Script SQL para el Módulo de Seguridad y Login del Panel de Administración
-- Base de Datos: centro_arbitraje_db
--
-- Plantilla de ejemplo: genera tu propio hash con password_hash() en PHP
-- (algoritmo bcrypt) y reemplaza el valor de EJEMPLO antes de ejecutarlo.

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    rol VARCHAR(20) DEFAULT 'admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar usuario administrador por defecto
-- Usuario: CAMBIAR_USUARIO
-- Contraseña: CAMBIAR_CONTRASEÑA (genera el hash con password_hash('tu_contraseña', PASSWORD_BCRYPT))
INSERT INTO usuarios (usuario, password_hash, rol)
VALUES ('CAMBIAR_USUARIO', 'CAMBIAR_POR_TU_HASH_BCRYPT', 'admin')
ON DUPLICATE KEY UPDATE usuario=usuario;
