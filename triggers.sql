-- Triggers para el Sistema de Empleados
-- Crear tabla de respaldo para INSERT

USE bdelafuente;

-- Tabla de respaldo para INSERT
CREATE TABLE IF NOT EXISTS temp_Empleados_Insert (
    id_respaldo INT AUTO_INCREMENT PRIMARY KEY,
    id_empleado INT,
    nombre VARCHAR(50),
    apellido VARCHAR(50),
    sexo VARCHAR(20),
    sueldo INT,
    fecha_insercion DATETIME DEFAULT CURRENT_TIMESTAMP,
    tipo_operacion VARCHAR(20) DEFAULT 'INSERT'
);

-- Tabla de respaldo para UPDATE
CREATE TABLE IF NOT EXISTS temp_Empleados_Update (
    id_respaldo INT AUTO_INCREMENT PRIMARY KEY,
    id_empleado INT,
    campo_modificado VARCHAR(50),
    valor_anterior VARCHAR(100),
    valor_nuevo VARCHAR(100),
    fecha_modificacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    tipo_operacion VARCHAR(20) DEFAULT 'UPDATE'
);

-- Trigger para INSERT
DELIMITER $$
CREATE TRIGGER EmpleadosInsert
AFTER INSERT ON empleados
FOR EACH ROW
BEGIN
    INSERT INTO temp_Empleados_Insert (
        id_empleado,
        nombre,
        apellido,
        sexo,
        sueldo,
        fecha_insercion,
        tipo_operacion
    ) VALUES (
        NEW.id,
        NEW.nombre,
        NEW.apellido,
        NEW.sexo,
        NEW.sueldo,
        NOW(),
        'INSERT'
    );
END $$
DELIMITER ;

-- Trigger para UPDATE
DELIMITER $$
CREATE TRIGGER EmpleadosUpdate
AFTER UPDATE ON empleados
FOR EACH ROW
BEGIN
    -- Verificar si el nombre cambió
    IF OLD.nombre != NEW.nombre THEN
        INSERT INTO temp_Empleados_Update (
            id_empleado,
            campo_modificado,
            valor_anterior,
            valor_nuevo,
            fecha_modificacion,
            tipo_operacion
        ) VALUES (
            NEW.id,
            'nombre',
            OLD.nombre,
            NEW.nombre,
            NOW(),
            'UPDATE'
        );
    END IF;
    
    -- Verificar si el apellido cambió
    IF OLD.apellido != NEW.apellido THEN
        INSERT INTO temp_Empleados_Update (
            id_empleado,
            campo_modificado,
            valor_anterior,
            valor_nuevo,
            fecha_modificacion,
            tipo_operacion
        ) VALUES (
            NEW.id,
            'apellido',
            OLD.apellido,
            NEW.apellido,
            NOW(),
            'UPDATE'
        );
    END IF;
    
    -- Verificar si el sexo cambió
    IF OLD.sexo != NEW.sexo THEN
        INSERT INTO temp_Empleados_Update (
            id_empleado,
            campo_modificado,
            valor_anterior,
            valor_nuevo,
            fecha_modificacion,
            tipo_operacion
        ) VALUES (
            NEW.id,
            'sexo',
            OLD.sexo,
            NEW.sexo,
            NOW(),
            'UPDATE'
        );
    END IF;
    
    -- Verificar si el sueldo cambió
    IF OLD.sueldo != NEW.sueldo THEN
        INSERT INTO temp_Empleados_Update (
            id_empleado,
            campo_modificado,
            valor_anterior,
            valor_nuevo,
            fecha_modificacion,
            tipo_operacion
        ) VALUES (
            NEW.id,
            'sueldo',
            CAST(OLD.sueldo AS CHAR),
            CAST(NEW.sueldo AS CHAR),
            NOW(),
            'UPDATE'
        );
    END IF;
END $$
DELIMITER ; 