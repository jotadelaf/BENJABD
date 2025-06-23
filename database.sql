-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS bdelafuente;
USE bdelafuente;

-- Crear la tabla empleados
CREATE TABLE IF NOT EXISTS empleados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50),
    apellido VARCHAR(50),
    sexo VARCHAR(20),
    sueldo INT
);

-- Crear el procedimiento almacenado InsertaEmpleado
DELIMITER $$
CREATE PROCEDURE InsertaEmpleado(
    IN NomE VARCHAR(50),
    IN ApeE VARCHAR(50),
    IN SexoE VARCHAR(20),
    IN SueldoE INT
)
BEGIN
    INSERT INTO empleados (nombre, apellido, sexo, sueldo)
    VALUES (NomE, ApeE, SexoE, SueldoE);
END $$
DELIMITER ; 