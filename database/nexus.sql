CREATE DATABASE IF NOT EXISTS escuela CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE escuela;

CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario VARCHAR(50) NOT NULL UNIQUE,
  clave VARCHAR(255) NOT NULL
);
CREATE TABLE alumnos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  grupo VARCHAR(20) NOT NULL
);
CREATE TABLE materias (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  docente VARCHAR(100) NOT NULL,
  creditos TINYINT NOT NULL DEFAULT 5
);
CREATE TABLE inscripciones (
  id INT AUTO_INCREMENT PRIMARY KEY,
  alumno_id INT NOT NULL,
  materia_id INT NOT NULL,
  calificacion DECIMAL(4,1) NULL,
  UNIQUE KEY uq_alumno_materia (alumno_id, materia_id),
  FOREIGN KEY (alumno_id) REFERENCES alumnos(id) ON DELETE CASCADE,
  FOREIGN KEY (materia_id) REFERENCES materias(id) ON DELETE CASCADE
);

INSERT INTO alumnos (nombre,email,grupo) VALUES
 ('Ana López','ana@escuela.mx','1A'),('Luis Pérez','luis@escuela.mx','1B');
INSERT INTO materias (nombre,docente,creditos) VALUES
 ('Matemáticas','Prof. Ramírez',8),('Historia','Prof. Torres',6);
INSERT INTO inscripciones (alumno_id,materia_id,calificacion) VALUES (1,1,9.5),(1,2,8.0),(2,1,7.0);
