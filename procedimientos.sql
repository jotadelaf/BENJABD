-- Procedimientos almacenados para el Sistema de Empleados

USE bdelafuente;

-- 1. Procedimiento para actualizar empleados con subconsulta en WHERE
DELIMITER $$
CREATE PROCEDURE ActualizarEmpleado(
    IN campo_modificar VARCHAR(50),
    IN nuevo_valor VARCHAR(100),
    IN condicion_campo VARCHAR(50),
    IN condicion_valor VARCHAR(100)
)
BEGIN
    DECLARE sql_query TEXT;
    
    -- Construir la consulta dinámicamente
    SET sql_query = CONCAT(
        'UPDATE empleados SET ',
        campo_modificar,
        ' = ? WHERE ',
        condicion_campo,
        ' IN (SELECT ',
        condicion_campo,
        ' FROM empleados WHERE ',
        condicion_campo,
        ' = ?)'
    );
    
    -- Ejecutar la consulta dinámica
    SET @sql = sql_query;
    SET @nuevo_valor = nuevo_valor;
    SET @condicion_valor = condicion_valor;
    
    PREPARE stmt FROM @sql;
    EXECUTE stmt USING @nuevo_valor, @condicion_valor;
    DEALLOCATE PREPARE stmt;
END $$
DELIMITER ;

-- 2. Procedimiento para eliminar empleados
DELIMITER $$
CREATE PROCEDURE EliminarEmpleado(
    IN campo_condicion VARCHAR(50),
    IN valor_condicion VARCHAR(100)
)
BEGIN
    DECLARE sql_query TEXT;
    
    -- Construir la consulta dinámicamente
    SET sql_query = CONCAT(
        'DELETE FROM empleados WHERE ',
        campo_condicion,
        ' = ?'
    );
    
    -- Ejecutar la consulta dinámica
    SET @sql = sql_query;
    SET @valor_condicion = valor_condicion;
    
    PREPARE stmt FROM @sql;
    EXECUTE stmt USING @valor_condicion;
    DEALLOCATE PREPARE stmt;
END $$
DELIMITER ;

-- 3. Procedimiento para listar todos los empleados
DELIMITER $$
CREATE PROCEDURE ListarEmpleados()
BEGIN
    SELECT id, nombre, apellido, sexo, sueldo
    FROM empleados
    ORDER BY id;
END $$
DELIMITER ;

-- 4. Procedimiento para listar empleados filtrados con parámetros IN y OUT
DELIMITER $$
CREATE PROCEDURE ListarEmpleadosFiltrados(
    IN filtro_sexo VARCHAR(20),
    OUT total_empleados INT,
    OUT promedio_sueldo DECIMAL(10,2)
)
BEGIN
    DECLARE sql_where TEXT DEFAULT '';
    DECLARE sql_query TEXT;
    
    -- Variable local para construir la condición WHERE
    IF filtro_sexo != '' THEN
        SET sql_where = CONCAT('WHERE sexo = ''', filtro_sexo, '''');
    END IF;
    
    -- Construir la consulta dinámicamente
    SET sql_query = CONCAT(
        'SELECT id, nombre, apellido, sexo, sueldo FROM empleados ',
        sql_where,
        ' ORDER BY id'
    );
    
    -- Ejecutar la consulta dinámica
    SET @sql = sql_query;
    PREPARE stmt FROM @sql;
    EXECUTE stmt;
    DEALLOCATE PREPARE stmt;
    
    -- Calcular estadísticas usando variables locales
    IF filtro_sexo != '' THEN
        SELECT COUNT(*) INTO total_empleados 
        FROM empleados 
        WHERE sexo = filtro_sexo;
        
        SELECT AVG(sueldo) INTO promedio_sueldo 
        FROM empleados 
        WHERE sexo = filtro_sexo;
    ELSE
        SELECT COUNT(*) INTO total_empleados 
        FROM empleados;
        
        SELECT AVG(sueldo) INTO promedio_sueldo 
        FROM empleados;
    END IF;
    
    -- Si no hay empleados, establecer valores por defecto
    IF total_empleados IS NULL THEN
        SET total_empleados = 0;
    END IF;
    
    IF promedio_sueldo IS NULL THEN
        SET promedio_sueldo = 0.00;
    END IF;
END $$
DELIMITER ;