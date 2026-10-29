CREATE DATABASE IF NOT EXISTS veterinaria_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE veterinaria_db;
CREATE TABLE IF NOT EXISTS usuarios (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(120) NOT NULL,
    email VARCHAR(180) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'veterinario', 'recepcion') NOT NULL DEFAULT 'recepcion',
    activo TINYINT(1) NOT NULL DEFAULT 1,
    creado TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS password_resets (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT UNSIGNED NOT NULL,
    token_hash CHAR(64) NOT NULL UNIQUE,
    expira DATETIME NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios (id)   ON DELETE CASCADE
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS duenos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(120) NOT NULL,
    telefono VARCHAR(25),
    email VARCHAR(150),
    direccion VARCHAR(255),
    documento VARCHAR(30) UNIQUE
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS veterinarios (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(120) NOT NULL,
    colegiatura VARCHAR(50) UNIQUE,
    especialidad VARCHAR(120),
    telefono VARCHAR(25),
    email VARCHAR(150)
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS mascotas (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    dueno_id INT UNSIGNED NOT NULL,
    nombre VARCHAR(120) NOT NULL,
    especie VARCHAR(60),
    raza VARCHAR(90),
    sexo VARCHAR(20),
    fecha_nacimiento DATE,
    peso_kg DECIMAL(8 , 2 ),
    FOREIGN KEY (dueno_id) REFERENCES duenos (id)
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS servicios (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(120) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10 , 2 ) NOT NULL DEFAULT 0,
    duracion_min INT
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS citas (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    mascota_id INT UNSIGNED NOT NULL,
    veterinario_id INT UNSIGNED NOT NULL,
    servicio_id INT UNSIGNED NOT NULL,
    fecha_hora DATETIME NOT NULL,
    estado ENUM('pendiente', 'confirmada', 'atendida', 'cancelada') DEFAULT 'pendiente',
    motivo TEXT,
    FOREIGN KEY (mascota_id) REFERENCES mascotas (id),
    FOREIGN KEY (veterinario_id)  REFERENCES veterinarios (id),
    FOREIGN KEY (servicio_id) REFERENCES servicios (id),
    INDEX (fecha_hora)
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS diagnosticos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    cita_id INT UNSIGNED NOT NULL,
    descripcion TEXT,
    observaciones TEXT,
    fecha DATE,
    FOREIGN KEY (cita_id) REFERENCES citas (id)
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS tratamientos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    diagnostico_id INT UNSIGNED NOT NULL,
    medicamento VARCHAR(200),
    indicaciones TEXT,
    fecha_inicio DATE,
    fecha_fin DATE,
    costo DECIMAL(10 , 2 ),
    FOREIGN KEY (diagnostico_id) REFERENCES diagnosticos (id)
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS pagos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    cita_id INT UNSIGNED NOT NULL,
    monto DECIMAL(10 , 2 ) NOT NULL,
    metodo ENUM('contado', 'tarjeta_credito', 'tarjeta_debito', 'yape') NOT NULL,
    estado ENUM('pendiente', 'pagado', 'anulado') DEFAULT 'pendiente',
    referencia VARCHAR(120),
    fecha_pago DATETIME,
    FOREIGN KEY (cita_id)  REFERENCES citas (id)
)  ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS reportes (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    mascota_id INT UNSIGNED NOT NULL,
    veterinario_id INT UNSIGNED NOT NULL,
    titulo VARCHAR(150),
    contenido TEXT,
    fecha DATE,
    FOREIGN KEY (mascota_id)   REFERENCES mascotas (id),
    FOREIGN KEY (veterinario_id)  REFERENCES veterinarios (id)
)  ENGINE=INNODB;

INSERT INTO servicios(nombre,descripcion,precio,duracion_min) 
SELECT 'Consulta general','Evaluación clínica',60,30 
	WHERE NOT EXISTS (SELECT 1 FROM servicios WHERE nombre='Consulta general');