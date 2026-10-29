CREATE DATABASE IF NOT EXISTS odontologia_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE odontologia_db;
CREATE TABLE IF NOT EXISTS usuario (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(120) NOT NULL,
    email VARCHAR(180) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'odontologo', 'recepcion') NOT NULL DEFAULT 'recepcion',
    activo TINYINT(1) NOT NULL DEFAULT 1,
    creado TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS password_reset (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT UNSIGNED NOT NULL,
    token_hash CHAR(64) NOT NULL UNIQUE,
    expira DATETIME NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuario (id)   ON DELETE CASCADE
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS paciente (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(120) NOT NULL,
    documento VARCHAR(30) UNIQUE,
    fecha_nacimiento DATE,
    telefono VARCHAR(25),
    email VARCHAR(150),
    direccion VARCHAR(255)
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS odontologo (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(120) NOT NULL,
    colegiatura VARCHAR(50) UNIQUE,
    especialidad VARCHAR(120),
    telefono VARCHAR(25),
    email VARCHAR(150)
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS servicio (
	id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(120) NOT NULL UNIQUE,
    descripcion TEXT,
    precio DECIMAL(10,2) NOT NULL DEFAULT 0,
    duracion_min INT
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS cita (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    paciente_id INT UNSIGNED NOT NULL,
    odontologo_id INT UNSIGNED NOT NULL,
    servicio_id INT UNSIGNED NOT NULL,
    fecha_hora DATETIME NOT NULL,
    estado ENUM('pendiente', 'confirmada', 'atendida', 'cancelada') NOT NULL DEFAULT 'pendiente',
    motivo TEXT,
	FOREIGN KEY (paciente_id) REFERENCES paciente (id),
    FOREIGN KEY (odontologo_id) REFERENCES odontologo (id),
    FOREIGN KEY (servicio_id) REFERENCES servicio (id),
    UNIQUE KEY uq_odontologo_fecha (odontologo_id, fecha_hora),
    INDEX idx_fecha (fecha_hora),
    INDEX idx_paciente (paciente_id)
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS diagnostico (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    cita_id INT UNSIGNED NOT NULL,
    descripcion TEXT,
    observaciones TEXT,
    fecha DATE,
    FOREIGN KEY (cita_id) REFERENCES cita (id) ON DELETE CASCADE
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS tratamiento (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    diagnostico_id INT UNSIGNED NOT NULL,
    nombre VARCHAR(200) NOT NULL,
    indicaciones TEXT,
    fecha_inicio DATE,
    fecha_fin DATE,
    costo DECIMAL(10,2),
    FOREIGN KEY (diagnostico_id) REFERENCES diagnostico (id) ON DELETE CASCADE
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS pago (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    cita_id INT UNSIGNED NOT NULL,
    monto DECIMAL(10,2) NOT NULL,
    metodo ENUM('efectivo', 'tarjeta_credito', 'tarjeta_debito', 'yape') NOT NULL,
    estado ENUM('pendiente', 'pagado', 'anulado') NOT NULL DEFAULT 'pendiente',
    referencia VARCHAR(120),
    fecha_pago DATETIME,
    FOREIGN KEY (cita_id) REFERENCES cita (id),
    INDEX idx_fecha_pago (fecha_pago)
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS reporte (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    paciente_id INT UNSIGNED NOT NULL,
    odontologo_id INT UNSIGNED NOT NULL,
    titulo VARCHAR(150),
    contenido TEXT,
    fecha DATE,
    FOREIGN KEY (paciente_id) REFERENCES paciente (id),
    FOREIGN KEY (odontologo_id) REFERENCES odontologo (id)
)  ENGINE=INNODB;

INSERT IGNORE INTO servicio (nombre, descripcion, precio, duracion_min) VALUES
('Consulta general', 'Evaluación clínica', 60, 30),
('Limpieza dental', 'Profilaxis y remoción de sarro', 80, 45),
('Extracción simple', 'Extracción de pieza dental', 120, 40),
('Endodoncia', 'Tratamiento de conductos', 350, 90);