-- =========================================================
-- CREACIÓN DE LA BASE DE DATOS
-- =========================================================

USE `db_inventario-10`;

CREATE TABLE IF NOT EXISTS departamentos (
    id_departamento INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) UNIQUE NOT NULL
);

CREATE TABLE IF NOT EXISTS categorias (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) UNIQUE NOT NULL
);

CREATE TABLE IF NOT EXISTS marcas (
    id_marca INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) UNIQUE NOT NULL
);

CREATE TABLE IF NOT EXISTS usuarios_app (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    rol ENUM('Administrador', 'Consulta') NOT NULL
);

CREATE TABLE IF NOT EXISTS solicitantes (
    id_solicitante INT AUTO_INCREMENT PRIMARY KEY,
    numero_institucional VARCHAR(50) UNIQUE NOT NULL,
    nombre VARCHAR(150) NOT NULL,
    id_departamento INT NOT NULL,
    FOREIGN KEY (id_departamento) REFERENCES departamentos(id_departamento) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS equipos (
    id_inventario VARCHAR(50) PRIMARY KEY,
    descripcion VARCHAR(200) NOT NULL,
    id_categoria INT NOT NULL,
    id_marca INT NOT NULL,
    condicion_actual ENUM('Disponible', 'Prestado', 'Mantenimiento', 'Baja') DEFAULT 'Disponible',
    FOREIGN KEY (id_categoria) REFERENCES categorias(id_categoria) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (id_marca) REFERENCES marcas(id_marca) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS prestamos (
    id_prestamo INT AUTO_INCREMENT PRIMARY KEY,
    id_inventario VARCHAR(50) NOT NULL,
    id_solicitante INT NOT NULL,
    id_usuario_entrega INT NOT NULL,
    id_usuario_recepcion INT DEFAULT NULL,
    fecha_prestamo DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_prevista_devolucion DATETIME NOT NULL,
    fecha_recepcion DATETIME DEFAULT NULL,
    condicion_regreso TEXT DEFAULT NULL,
    CONSTRAINT chk_fecha_prevista CHECK (fecha_prevista_devolucion >= fecha_prestamo),
    CONSTRAINT chk_fecha_recepcion CHECK (fecha_recepcion IS NULL OR fecha_recepcion >= fecha_prestamo),
    FOREIGN KEY (id_inventario) REFERENCES equipos(id_inventario) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (id_solicitante) REFERENCES solicitantes(id_solicitante) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (id_usuario_entrega) REFERENCES usuarios_app(id_usuario) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (id_usuario_recepcion) REFERENCES usuarios_app(id_usuario) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS bitacora_mantenimiento (
    id_mantenimiento INT AUTO_INCREMENT PRIMARY KEY,
    id_inventario VARCHAR(50) NOT NULL,
    fecha_reporte DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    descripcion_falla TEXT NOT NULL,
    fecha_reparacion DATETIME DEFAULT NULL,
    CONSTRAINT chk_fecha_reparacion CHECK (fecha_reparacion IS NULL OR fecha_reparacion >= fecha_reporte),
    FOREIGN KEY (id_inventario) REFERENCES equipos(id_inventario) ON DELETE RESTRICT ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS historial_estados (
    id_historial INT AUTO_INCREMENT PRIMARY KEY,
    id_inventario VARCHAR(50) NOT NULL,
    estado_anterior VARCHAR(20),
    estado_nuevo VARCHAR(20),
    fecha_cambio DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_inventario) REFERENCES equipos(id_inventario) ON DELETE RESTRICT ON UPDATE CASCADE
);

-- =========================================================
-- USUARIO: admin, CONTRASENA: admin123
-- USUARIO: consulta, CONTRASENA: consulta123
-- =========================================================

INSERT IGNORE INTO usuarios_app (username, password_hash, rol) VALUES
('admin', '$2y$12$aUqUXaPaIqtYdvZv9HMSseA5ORlhAvGnzX5YFcWGfeYDkgV5SOu82', 'Administrador'),
('consulta', '$2y$12$p8h8oTiuiWxY2ssfNpvIB.0rao7kkwN/SnGe872BHuGuiyYMRdWAi', 'Consulta');

-- =========================================================
-- DATOS INICIALES DE PRUEBA
-- =========================================================

-- Catálogos
INSERT IGNORE INTO departamentos (id_departamento, nombre) VALUES
(1, 'Sistemas y Computación'),
(2, 'Ingeniería Industrial'),
(3, 'Servicios Escolares'),
(4, 'Vinculación y Extensión'),
(5, 'Dirección Académica'),
(6, 'Biblioteca');

INSERT IGNORE INTO categorias (id_categoria, nombre) VALUES
(1, 'Computadora Portátil'),
(2, 'Proyector'),
(3, 'Kit de Electrónica'),
(4, 'Equipo de Red'),
(5, 'Herramienta de Diagnóstico'),
(6, 'Adaptadores y Accesorios'),
(7, 'Audio y Video');

INSERT IGNORE INTO marcas (id_marca, nombre) VALUES
(1, 'Lenovo'),
(2, 'HP'),
(3, 'Epson'),
(4, 'Arduino'),
(5, 'Cisco'),
(6, 'Fluke'),
(7, 'UGREEN'),
(8, 'Logitech');

-- Solicitantes
INSERT IGNORE INTO solicitantes (id_solicitante, numero_institucional, nombre, id_departamento) VALUES
(1, 'EMP-1001', 'Laura Méndez Vargas', 1),
(2, 'EMP-1002', 'Carlos Eduardo Navarro Ruiz', 2),
(3, 'EMP-1003', 'Sofía Alejandra Ríos Torres', 3),
(4, 'EMP-1004', 'Miguel Ángel Delgado Pérez', 1),
(5, 'EMP-1005', 'Fernanda Castillo Ochoa', 4),
(6, 'EMP-1006', 'Javier Morales Quintero', 5);

-- Equipos
INSERT IGNORE INTO equipos (id_inventario, descripcion, id_categoria, id_marca, condicion_actual) VALUES
('LAP-001', 'Laptop ThinkPad E14 Gen 5, 16GB RAM, 512GB SSD', 1, 1, 'Disponible'),
('LAP-002', 'Laptop ThinkPad E14 Gen 5, 16GB RAM, 512GB SSD', 1, 1, 'Disponible'),
('LAP-003', 'Laptop ThinkPad E14 Gen 5, 16GB RAM, 512GB SSD', 1, 1, 'Prestado'),
('LAP-004', 'Laptop ProBook 450 G10, 16GB RAM, 512GB SSD', 1, 2, 'Mantenimiento'),
('PRO-001', 'Proyector PowerLite E20 3400 lúmenes HDMI', 2, 3, 'Prestado'),
('PRO-002', 'Proyector PowerLite X49 3600 lúmenes', 2, 3, 'Prestado'),
('PRO-003', 'Proyector PowerLite S39 (lámpara agotada)', 2, 3, 'Baja'),
('KIT-001', 'Kit Arduino Uno R3 con protoboard y sensores', 3, 4, 'Disponible'),
('RED-001', 'Router empresarial RV340 Dual WAN', 4, 5, 'Mantenimiento'),
('RED-002', 'Switch administrable Catalyst 2960 de 24 puertos', 4, 5, 'Disponible'),
('DIA-001', 'Multímetro digital True-RMS modelo 117', 5, 6, 'Prestado'),
('ACC-001', 'Adaptador USB-C a HDMI, VGA y Ethernet', 6, 7, 'Disponible');

SET @id_admin = (SELECT id_usuario FROM usuarios_app WHERE username = 'admin' LIMIT 1);

-- Préstamos
INSERT IGNORE INTO prestamos (
    id_prestamo, id_inventario, id_solicitante, id_usuario_entrega, id_usuario_recepcion,
    fecha_prestamo, fecha_prevista_devolucion, fecha_recepcion, condicion_regreso
) VALUES
(1,  'LAP-001', 1, @id_admin, @id_admin, NOW() - INTERVAL 45 DAY, NOW() - INTERVAL 42 DAY, NOW() - INTERVAL 42 DAY + INTERVAL 2 HOUR, 'Entregada completa con cargador, sin detalles.'),
(2,  'LAP-001', 2, @id_admin, @id_admin, NOW() - INTERVAL 28 DAY, NOW() - INTERVAL 25 DAY, NOW() - INTERVAL 26 DAY, 'Devuelta antes de la fecha límite en buen estado.'),
(3,  'LAP-002', 5, @id_admin, @id_admin, NOW() - INTERVAL 20 DAY, NOW() - INTERVAL 18 DAY, NOW() - INTERVAL 18 DAY, 'Equipo en buenas condiciones, batería sin fallas.'),
(4,  'KIT-001', 1, @id_admin, @id_admin, NOW() - INTERVAL 15 DAY, NOW() - INTERVAL 12 DAY, NOW() - INTERVAL 12 DAY, 'Kit completo, cables y sensores revisados.'),
(5,  'PRO-003', 5, @id_admin, @id_admin, NOW() - INTERVAL 60 DAY, NOW() - INTERVAL 58 DAY, NOW() - INTERVAL 58 DAY, 'Funcionó correctamente durante el evento.'),
(6,  'PRO-003', 2, @id_admin, @id_admin, NOW() - INTERVAL 35 DAY, NOW() - INTERVAL 33 DAY, NOW() - INTERVAL 33 DAY, 'Se apagó por sobrecalentamiento durante la presentación.'),
(7,  'LAP-004', 4, @id_admin, @id_admin, NOW() - INTERVAL 8 DAY,  NOW() - INTERVAL 5 DAY,  NOW() - INTERVAL 4 DAY,  'Falla en teclado: varias teclas no responden. Enviada a mantenimiento.'),
(8,  'LAP-003', 1, @id_admin, NULL,      NOW() - INTERVAL 1 DAY,  NOW() + INTERVAL 3 DAY,  NULL, NULL),
(9,  'DIA-001', 4, @id_admin, NULL,      NOW() - INTERVAL 5 HOUR, NOW() + INTERVAL 2 DAY,  NULL, NULL),
(10, 'PRO-001', 2, @id_admin, NULL,      NOW() - INTERVAL 6 DAY,  NOW() - INTERVAL 2 DAY - INTERVAL 4 HOUR, NULL, NULL),
(11, 'PRO-002', 3, @id_admin, NULL,      NOW() - INTERVAL 2 DAY,  NOW() - INTERVAL 3 HOUR - INTERVAL 20 MINUTE, NULL, NULL);

-- Bitácora de mantenimiento
INSERT IGNORE INTO bitacora_mantenimiento (
    id_mantenimiento, id_inventario, fecha_reporte, descripcion_falla, fecha_reparacion
) VALUES
(1, 'LAP-001', NOW() - INTERVAL 24 DAY, 'Ventilador con ruido excesivo y calentamiento bajo carga.', NOW() - INTERVAL 21 DAY),
(2, 'PRO-003', NOW() - INTERVAL 33 DAY, 'Se apaga por sobrecalentamiento; lámpara agotada y tarjeta de video sin refacción.', NOW() - INTERVAL 30 DAY),
(3, 'LAP-004', NOW() - INTERVAL 4 DAY,  'Falla en teclado: varias teclas no responden. Reportado al recibir devolución.', NULL),
(4, 'RED-001', NOW() - INTERVAL 2 DAY,  'Pérdida intermitente de conexión en puerto WAN1 tras actualización de firmware.', NULL);

-- Historial de estados
INSERT IGNORE INTO historial_estados (id_historial, id_inventario, estado_anterior, estado_nuevo, fecha_cambio) VALUES
(1,  'LAP-001', 'Disponible',    'Prestado',      NOW() - INTERVAL 45 DAY),
(2,  'LAP-001', 'Prestado',      'Disponible',    NOW() - INTERVAL 42 DAY + INTERVAL 2 HOUR),
(3,  'LAP-001', 'Disponible',    'Prestado',      NOW() - INTERVAL 28 DAY),
(4,  'LAP-001', 'Prestado',      'Disponible',    NOW() - INTERVAL 26 DAY),
(5,  'LAP-001', 'Disponible',    'Mantenimiento', NOW() - INTERVAL 24 DAY),
(6,  'LAP-001', 'Mantenimiento', 'Disponible',    NOW() - INTERVAL 21 DAY),
(7,  'LAP-002', 'Disponible',    'Prestado',      NOW() - INTERVAL 20 DAY),
(8,  'LAP-002', 'Prestado',      'Disponible',    NOW() - INTERVAL 18 DAY),
(9,  'LAP-003', 'Disponible',    'Prestado',      NOW() - INTERVAL 1 DAY),
(10, 'LAP-004', 'Disponible',    'Prestado',      NOW() - INTERVAL 8 DAY),
(11, 'LAP-004', 'Prestado',      'Mantenimiento', NOW() - INTERVAL 4 DAY),
(12, 'PRO-001', 'Disponible',    'Prestado',      NOW() - INTERVAL 6 DAY),
(13, 'PRO-002', 'Disponible',    'Prestado',      NOW() - INTERVAL 2 DAY),
(14, 'PRO-003', 'Disponible',    'Prestado',      NOW() - INTERVAL 60 DAY),
(15, 'PRO-003', 'Prestado',      'Disponible',    NOW() - INTERVAL 58 DAY),
(16, 'PRO-003', 'Disponible',    'Prestado',      NOW() - INTERVAL 35 DAY),
(17, 'PRO-003', 'Prestado',      'Mantenimiento', NOW() - INTERVAL 33 DAY),
(18, 'PRO-003', 'Mantenimiento', 'Baja',          NOW() - INTERVAL 30 DAY),
(19, 'KIT-001', 'Disponible',    'Prestado',      NOW() - INTERVAL 15 DAY),
(20, 'KIT-001', 'Prestado',      'Disponible',    NOW() - INTERVAL 12 DAY),
(21, 'DIA-001', 'Disponible',    'Prestado',      NOW() - INTERVAL 5 HOUR),
(22, 'RED-001', 'Disponible',    'Mantenimiento', NOW() - INTERVAL 2 DAY);

/*
Observaciones (objetos programables):
Contempla cómo impedir que un equipo pueda tener dos préstamos activos al mismo tiempo.
Define cómo se registrarán los cambios de estado del equipo para que la información histórica se mantenga actualizada.
*/