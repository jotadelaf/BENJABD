-- Actualizar el procedimiento EliminarEmpleado para manejar búsqueda y eliminación
USE bdelafuente;

-- Eliminar el procedimiento existente
DROP PROCEDURE IF EXISTS EliminarEmpleado;

-- Crear el nuevo procedimiento
DELIMITER $$
CREATE PROCEDURE EliminarEmpleado(
    IN accion VARCHAR(20),
    IN campo_condicion VARCHAR(50),
    IN valor_condicion VARCHAR(100)
)
BEGIN
    DECLARE sql_query TEXT;
    
    -- Si la acción es 'BUSCAR'
    IF accion = 'BUSCAR' THEN
        -- Buscar empleados por nombre
        SELECT id, nombre, apellido, sexo, sueldo
        FROM empleados 
        WHERE nombre LIKE CONCAT('%', valor_condicion, '%')
        ORDER BY nombre;
    
    -- Si la acción es 'ELIMINAR'
    ELSEIF accion = 'ELIMINAR' THEN
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
    END IF;
END $$
DELIMITER ; 