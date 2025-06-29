-- Base de datos UPIITA - Versión corregida para IONOS
-- Sin DROP DATABASE ni CREATE DATABASE

-- Primero limpiar tablas si existen
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS RutasFavoritas;
DROP TABLE IF EXISTS Rutas;
DROP TABLE IF EXISTS PuntosConexion;
DROP TABLE IF EXISTS Aulas;
DROP TABLE IF EXISTS Edificios;
DROP TABLE IF EXISTS usuarios;
DROP VIEW IF EXISTS vista_lugares;
SET FOREIGN_KEY_CHECKS = 1;

-- Tabla de usuarios para el sistema de autenticación
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de edificios
CREATE TABLE Edificios(
    idEdificio int PRIMARY KEY NOT NULL,
    nombre varchar(30),
    descripcion varchar(150),
    pisos int
);

-- Tabla de aulas con coordenadas para el mapa
CREATE TABLE Aulas(
    idAula int PRIMARY KEY NOT NULL,
    numeroAula varchar(50),
    nombreAula varchar(200),
    piso int,
    idEdificio int,
    coordenada_x DECIMAL(10,2) DEFAULT NULL,
    coordenada_y DECIMAL(10,2) DEFAULT NULL,
    es_punto_conexion BOOLEAN DEFAULT FALSE,
    CONSTRAINT fk_edificio FOREIGN KEY (idEdificio) REFERENCES Edificios(idEdificio)
);

-- Nueva tabla para puntos de conexión (pasillos, escaleras, etc.)
CREATE TABLE PuntosConexion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    tipo ENUM('pasillo', 'escalera', 'entrada', 'salida') NOT NULL,
    piso INT NOT NULL,
    idEdificio INT,
    coordenada_x DECIMAL(10,2) NOT NULL,
    coordenada_y DECIMAL(10,2) NOT NULL,
    CONSTRAINT fk_edificio_punto FOREIGN KEY (idEdificio) REFERENCES Edificios(idEdificio)
);

-- Tabla de rutas (aristas del grafo)
CREATE TABLE Rutas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    origen_tipo ENUM('aula', 'punto') NOT NULL,
    origen_id INT NOT NULL,
    destino_tipo ENUM('aula', 'punto') NOT NULL,
    destino_id INT NOT NULL,
    distancia DECIMAL(10,2) NOT NULL,
    es_bidireccional BOOLEAN DEFAULT TRUE,
    tipo_conexion ENUM('directo', 'escalera', 'ascensor') DEFAULT 'directo',
    INDEX idx_origen (origen_tipo, origen_id),
    INDEX idx_destino (destino_tipo, destino_id)
);

-- Tabla para guardar rutas favoritas de usuarios
CREATE TABLE RutasFavoritas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    origen_tipo ENUM('aula', 'punto') NOT NULL,
    origen_id INT NOT NULL,
    destino_tipo ENUM('aula', 'punto') NOT NULL,
    destino_id INT NOT NULL,
    nombre_ruta VARCHAR(100),
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_usuario_favorito FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- Insertar datos de edificios
INSERT INTO Edificios (idEdificio, nombre, descripcion, pisos)
VALUES(1, 'A1 - Aulas 1', 'Edificio A1 con aulas', 3),
      (2, 'A2 - Aulas 2', 'Edificio A2 con aulas', 3),
      (3, 'A3 - Aulas 3', 'Edificio A3 con aulas', 3),
      (4, 'A4 - Aulas 4', 'Edificio A4 con aulas', 3),
      (5, 'LC - Laboratorios 1', 'Laboratorios LC', 3),
      (6, 'EG - Edificio de Gobierno', 'Edificio administrativo', 2),
      (7, 'EP - Laboratorios Pesados', 'Laboratorios pesados', 2),
      (8, 'Anexos', 'Zonas anexas', 1);

-- Insertar aulas con coordenadas completas del proyecto original
INSERT INTO Aulas (idAula, numeroAula, nombreAula, piso, idEdificio, coordenada_x, coordenada_y, es_punto_conexion)
VALUES
-- Edificio A1 (coordenadas ejemplo)
(1, 'A-100', 'Aula', 1, 1, 150.00, 200.00, FALSE),
(2, 'A-101', 'Sala de profesores', 1, 1, 180.00, 200.00, FALSE),
(3, 'A-102', 'Aula', 1, 1, 210.00, 200.00, FALSE),
(4, 'A-103', 'Aula', 1, 1, 150.00, 170.00, FALSE),
(5, 'A-104', 'Aula', 1, 1, 180.00, 170.00, FALSE),
(6, 'A-105', 'Aula', 1, 1, 210.00, 170.00, FALSE),
(7, 'A-106', 'Aula', 1, 1, 240.00, 170.00, FALSE),
(8, 'A-110', 'Aula Magna posgrado', 2, 1, 150.00, 140.00, FALSE),
(9, 'A-111', 'Sala de profesores', 2, 1, 180.00, 140.00, FALSE),
(10, 'A-112', 'Sala de profesores', 2, 1, 210.00, 140.00, FALSE),
(11, 'A-113', 'Sala de profesores', 2, 1, 240.00, 140.00, FALSE),
(12, 'A-114', 'UTE y CV', 2, 1, 270.00, 140.00, FALSE),
(13, 'A-115', 'Sala de profesores', 2, 1, 150.00, 110.00, FALSE),
(14, 'A-116', 'Sala de profesores', 2, 1, 180.00, 110.00, FALSE),
(15, 'A-120', 'Aula posgrado', 3, 1, 150.00, 80.00, FALSE),
(16, 'A-121', 'Aula', 3, 1, 180.00, 80.00, FALSE),
(17, 'A-122', 'Aula', 3, 1, 210.00, 80.00, FALSE),
(18, 'A-123', 'Aula', 3, 1, 240.00, 80.00, FALSE),
(19, 'A-124', 'Aula', 3, 1, 270.00, 80.00, FALSE),
(20, 'A-125', 'Aula', 3, 1, 150.00, 50.00, FALSE),
(21, 'A-126', 'Aula', 3, 1, 180.00, 50.00, FALSE),

-- Edificio A2 (coordenadas ejemplo)
(22, 'A-200', 'Lab. de Desarrollo de Proyectos', 1, 2, 350.00, 200.00, FALSE),
(23, 'A-201', 'Aula', 1, 2, 380.00, 200.00, FALSE),
(24, 'A-202', 'Aula', 1, 2, 410.00, 200.00, FALSE),
(25, 'A-203', 'Sala de Cómputo 4', 1, 2, 350.00, 170.00, FALSE),
(26, 'A-204', 'Lab. de Realidad Extendida', 1, 2, 380.00, 170.00, FALSE),
(27, 'A-205', 'Lab. CIM', 1, 2, 410.00, 170.00, FALSE),
(28, 'A-206', 'Lab. CIM', 1, 2, 440.00, 170.00, FALSE),
(29, 'A-210', 'Sala de préstamo', 2, 2, 350.00, 140.00, FALSE),
(30, 'A-211', 'Aula', 2, 2, 380.00, 140.00, FALSE),
(31, 'A-212', 'Sala de Cómputo 1', 2, 2, 410.00, 140.00, FALSE),
(32, 'A-213', 'Sala de Cómputo 2', 2, 2, 440.00, 140.00, FALSE),
(33, 'A-214', 'Sala multimedia', 2, 2, 350.00, 110.00, FALSE),
(34, 'A-215', 'Sin Información', 2, 2, 380.00, 110.00, FALSE),
(35, 'A-216', 'Sala de Cómputo 3', 2, 2, 410.00, 110.00, FALSE),
(36, 'A-220', 'Aula', 3, 2, 350.00, 80.00, FALSE),
(37, 'A-221', 'Aula', 3, 2, 380.00, 80.00, FALSE),
(38, 'A-222', 'Aula', 3, 2, 410.00, 80.00, FALSE),
(39, 'A-223', 'Aula', 3, 2, 440.00, 80.00, FALSE),
(40, 'A-224', 'Aula', 3, 2, 350.00, 50.00, FALSE),
(41, 'A-225', 'Aula', 3, 2, 380.00, 50.00, FALSE),
(42, 'A-226', 'Aula', 3, 2, 410.00, 50.00, FALSE),

-- Edificio A3 (coordenadas ejemplo)
(43, 'A-300', 'Lab. de electrónica 3', 1, 3, 500.00, 200.00, FALSE),
(44, 'A-301', 'Sin Información', 1, 3, 530.00, 200.00, FALSE),
(45, 'A-302', 'Sin Información', 1, 3, 560.00, 200.00, FALSE),
(46, 'A-303', 'Lab. Robótica Avanzada', 1, 3, 500.00, 170.00, FALSE),
(47, 'A-304', 'Red de Expertos Posgrado', 1, 3, 530.00, 170.00, FALSE),
(48, 'A-305', 'Red de Expertos Posgrado', 1, 3, 560.00, 170.00, FALSE),
(49, 'A-306', 'Lab. Síntesis Química Posgrado', 1, 3, 590.00, 170.00, FALSE),

-- Edificio EG (coordenadas ejemplo)
(126, 'EG-001', 'Servicio Médico', 1, 6, 500.00, 300.00, FALSE),
(127, 'EG-002', 'Subdirección de Servicios Educativos', 1, 6, 530.00, 300.00, FALSE),
(128, 'EG-003', 'Actividades Culturales y Deportivas', 1, 6, 560.00, 300.00, FALSE),
(129, 'EG-004', 'Servicios Estudiantiles', 1, 6, 590.00, 300.00, FALSE),
(130, 'EG-007', 'Gestión Escolar', 1, 6, 500.00, 270.00, FALSE),
(140, 'EG-015', 'Auditorio', 1, 6, 550.00, 330.00, FALSE),
(141, 'EG-100', 'Unidad de Informática', 2, 6, 500.00, 240.00, FALSE),
(148, 'EG-108', 'Dirección', 2, 6, 530.00, 240.00, FALSE),
(150, 'EG-109', 'Subdirección Académica', 2, 6, 560.00, 240.00, FALSE),

-- Edificio EP - Laboratorios Pesados
(161, 'EP-01', 'Robótica Industrial', 1, 7, 200.00, 350.00, FALSE),
(162, 'EP-02', 'Manufactura Básica', 1, 7, 230.00, 350.00, FALSE),
(163, 'EP-03', 'Manufactura Avanzada', 1, 7, 260.00, 350.00, FALSE),
(164, 'EP-04', 'Lab. de Metrología', 1, 7, 290.00, 350.00, FALSE),
(170, 'EP-101', 'Lab. de cálculo y simulación 2', 2, 7, 200.00, 320.00, FALSE),
(171, 'EP-102', 'Lab. de cálculo y simulación 1', 2, 7, 230.00, 320.00, FALSE),
(173, 'EP-104', 'Sala de Cómputo 10', 2, 7, 260.00, 320.00, FALSE),

-- Edificio LC - Laboratorios principales
(85, 'LC-100', 'Lab. de Química y Biología', 1, 5, 350.00, 400.00, FALSE),
(86, 'LC-101', 'Lab. de Química y Biología', 1, 5, 380.00, 400.00, FALSE),
(87, 'LC-102', 'Lab. de Física 1', 1, 5, 410.00, 400.00, FALSE),
(88, 'LC-103', 'Lab. de Física 2', 1, 5, 440.00, 400.00, FALSE),
(89, 'LC-104', 'Biblioteca', 1, 5, 350.00, 370.00, FALSE),
(98, 'LC-110', 'Lab. de Cómputo Móvil', 2, 5, 350.00, 340.00, FALSE),
(101, 'LC-113', 'Lab. Telemática I', 2, 5, 380.00, 340.00, FALSE),
(102, 'LC-114', 'Lab. Electrónica II', 2, 5, 410.00, 340.00, FALSE),

-- Punto de entrada principal
(999, 'ENTRADA-PRINCIPAL', 'Entrada Principal UPIITA', 1, 8, 400.00, 100.00, TRUE);

-- Insertar puntos de conexión básicos
INSERT INTO PuntosConexion (nombre, tipo, piso, idEdificio, coordenada_x, coordenada_y)
VALUES
-- Pasillos principales
('Pasillo-A1-Norte', 'pasillo', 1, 1, 195.00, 185.00),
('Pasillo-A1-Sur', 'pasillo', 1, 1, 195.00, 155.00),
('Pasillo-A2-Norte', 'pasillo', 1, 2, 380.00, 185.00),
('Pasillo-Central', 'pasillo', 1, 8, 300.00, 200.00),
('Pasillo-EG', 'pasillo', 1, 6, 515.00, 325.00),

-- Escaleras
('Escalera-A1', 'escalera', 1, 1, 225.00, 185.00),
('Escalera-A2', 'escalera', 1, 2, 395.00, 185.00),

-- Entradas
('Entrada-A1', 'entrada', 1, 1, 195.00, 120.00),
('Entrada-A2', 'entrada', 1, 2, 380.00, 120.00);

-- Insertar algunas rutas básicas
INSERT INTO Rutas (origen_tipo, origen_id, destino_tipo, destino_id, distancia, tipo_conexion)
VALUES
-- Conexiones desde entrada principal
('aula', 999, 'punto', 1, 15.0, 'directo'),
('aula', 999, 'punto', 3, 12.0, 'directo'),
('aula', 999, 'punto', 4, 8.0, 'directo'),

-- Conexiones en edificio A1
('punto', 1, 'aula', 1, 5.0, 'directo'),
('punto', 1, 'aula', 2, 3.0, 'directo'),
('punto', 1, 'aula', 3, 5.0, 'directo'),
('punto', 1, 'punto', 2, 4.0, 'directo'),
('punto', 2, 'aula', 4, 3.0, 'directo'),
('punto', 2, 'aula', 5, 3.0, 'directo'),

-- Conexiones en edificio A2
('punto', 3, 'aula', 22, 4.0, 'directo'),
('punto', 3, 'aula', 23, 3.0, 'directo'),
('punto', 3, 'aula', 24, 5.0, 'directo'),

-- Conexiones entre edificios
('punto', 4, 'punto', 1, 20.0, 'directo'),
('punto', 4, 'punto', 3, 15.0, 'directo'),
('punto', 4, 'punto', 5, 25.0, 'directo');

-- Insertar usuario de prueba
INSERT INTO usuarios (nombre, email, password)
VALUES ('Usuario Prueba', 'test@upiita.mx', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Crear vista útil para consultas
CREATE VIEW vista_lugares AS
SELECT
    'aula' as tipo,
    idAula as id,
    numeroAula as codigo,
    nombreAula as nombre,
    piso,
    idEdificio,
    coordenada_x,
    coordenada_y
FROM Aulas
WHERE coordenada_x IS NOT NULL AND coordenada_y IS NOT NULL
UNION ALL
SELECT
    'punto' as tipo,
    id,
    nombre as codigo,
    nombre,
    piso,
    idEdificio,
    coordenada_x,
    coordenada_y
FROM PuntosConexion;