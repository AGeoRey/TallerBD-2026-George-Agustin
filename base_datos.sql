CREATE DATABASE IF NOT EXISTS `db_inventario-10`;

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

-- Usuarios iniciales de prueba para el sistema web:
-- Administrador -> usuario: admin | contrasena: admin123
-- Consulta      -> usuario: consulta | contrasena: consulta123
INSERT IGNORE INTO usuarios_app (username, password_hash, rol) VALUES
('admin', '$2y$12$aUqUXaPaIqtYdvZv9HMSseA5ORlhAvGnzX5YFcWGfeYDkgV5SOu82', 'Administrador'),
('consulta', '$2y$12$p8h8oTiuiWxY2ssfNpvIB.0rao7kkwN/SnGe872BHuGuiyYMRdWAi', 'Consulta');

/*
Observaciones (objetos programables):
Contempla cómo impedir que un equipo pueda tener dos préstamos activos al mismo tiempo.
Define cómo se registrarán los cambios de estado del equipo para que la información histórica se mantenga actualizada.
*/