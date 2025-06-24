<?php
/**
 * Sistema de Gestión de Empleados - Actualización
 * Maneja búsqueda en tiempo real y actualización de sueldos
 */

// Configuración de la base de datos
class DatabaseConfig {
    const SERVER = "localhost";
    const USERNAME = "root";
    const PASSWORD = "";
    const DBNAME = "bdelafuente";
}

// Clase para manejo de empleados
class EmpleadoManager {
    private $conn;
    
    public function __construct() {
        $this->conn = new mysqli(
            DatabaseConfig::SERVER,
            DatabaseConfig::USERNAME,
            DatabaseConfig::PASSWORD,
            DatabaseConfig::DBNAME
        );
        
        if ($this->conn->connect_error) {
            throw new Exception("Error de conexión: " . $this->conn->connect_error);
        }
        
        $this->conn->set_charset("utf8");
    }
    
    /**
     * Busca empleados por nombre o apellido
     */
    public function buscarEmpleados($query) {
        $query = $this->conn->real_escape_string($query);
        $sql = "SELECT id, nombre, apellido, sexo, sueldo 
                FROM empleados 
                WHERE nombre LIKE ? OR apellido LIKE ? 
                ORDER BY nombre, apellido 
                LIMIT 10";
        
        $stmt = $this->conn->prepare($sql);
        $searchTerm = "%{$query}%";
        $stmt->bind_param("ss", $searchTerm, $searchTerm);
        
        if (!$stmt->execute()) {
            throw new Exception("Error en la búsqueda: " . $stmt->error);
        }
        
        $result = $stmt->get_result();
        $empleados = [];
        
        while ($row = $result->fetch_assoc()) {
            $empleados[] = [
                'id' => $row['id'],
                'nombre' => $row['nombre'],
                'apellido' => $row['apellido'],
                'sexo' => $row['sexo'],
                'sueldo' => number_format($row['sueldo'], 2)
            ];
        }
        
        $stmt->close();
        return $empleados;
    }
    
    /**
     * Actualiza el sueldo de un empleado específico
     */
    public function actualizarSueldo($empleadoId, $nuevoSueldo) {
        // Validar sueldo
        if (!is_numeric($nuevoSueldo) || $nuevoSueldo < 0) {
            throw new Exception("El sueldo debe ser un número positivo");
        }
        
        // Verificar que el empleado existe
        $stmt = $this->conn->prepare("SELECT id, nombre, apellido, sueldo FROM empleados WHERE id = ?");
        $stmt->bind_param("i", $empleadoId);
        
        if (!$stmt->execute()) {
            throw new Exception("Error al verificar empleado: " . $stmt->error);
        }
        
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            $stmt->close();
            throw new Exception("Empleado no encontrado");
        }
        
        $empleado = $result->fetch_assoc();
        $sueldoAnterior = $empleado['sueldo'];
        $stmt->close();
        
        // Actualizar sueldo usando procedimiento almacenado
        $stmt = $this->conn->prepare("CALL ActualizarEmpleado(?, ?, ?, ?)");
        $campo = "sueldo";
        $condicionCampo = "id";
        $stmt->bind_param("ssss", $campo, $nuevoSueldo, $condicionCampo, $empleadoId);
        
        if (!$stmt->execute()) {
            throw new Exception("Error al actualizar sueldo: " . $stmt->error);
        }
        
        $affectedRows = $stmt->affected_rows;
        $stmt->close();
        
        if ($affectedRows === 0) {
            throw new Exception("No se pudo actualizar el sueldo");
        }
        
        return [
            'empleado' => $empleado['nombre'] . ' ' . $empleado['apellido'],
            'sueldo_anterior' => number_format($sueldoAnterior, 2),
            'sueldo_nuevo' => number_format($nuevoSueldo, 2)
        ];
    }
    
    public function __destruct() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}

// Manejo de la solicitud
try {
    $manager = new EmpleadoManager();
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $action = $_POST['action'] ?? '';
        
        // Búsqueda en tiempo real
        if ($action === 'buscar') {
            $query = $_POST['query'] ?? '';
            
            if (strlen($query) < 2) {
                echo json_encode([]);
                exit;
            }
            
            $empleados = $manager->buscarEmpleados($query);
            header('Content-Type: application/json');
            echo json_encode($empleados);
            exit;
        }
        
        // Actualización de sueldo
        $empleadoId = $_POST['empleado_id'] ?? '';
        $nuevoSueldo = $_POST['nuevo_sueldo'] ?? '';
        
        if (empty($empleadoId) || empty($nuevoSueldo)) {
            throw new Exception("Todos los campos son requeridos");
        }
        
        $resultado = $manager->actualizarSueldo($empleadoId, $nuevoSueldo);
        
        // Mostrar página de éxito
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Sueldo Actualizado</title>
            <link rel="stylesheet" href="styles.css">
        </head>
        <body>
            <div class="container">
                <h1>Sueldo Actualizado Exitosamente</h1>
                
                <div class="message success">
                    <h3>✅ Actualización Completada</h3>
                    <p><strong>Empleado:</strong> <?php echo htmlspecialchars($resultado['empleado']); ?></p>
                    <p><strong>Sueldo Anterior:</strong> $<?php echo $resultado['sueldo_anterior']; ?></p>
                    <p><strong>Sueldo Nuevo:</strong> $<?php echo $resultado['sueldo_nuevo']; ?></p>
                </div>
                
                <div style="text-align: center; margin-top: 30px;">
                    <a href="inicio.html" class="btn-submit" style="text-decoration: none; display: inline-block; padding: 12px 24px;">Volver al Sistema</a>
                </div>
            </div>
        </body>
        </html>
        <?php
    } else {
        throw new Exception("Método de solicitud no válido");
    }
    
} catch (Exception $e) {
    // Mostrar página de error
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Error</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <div class="container">
            <h1>Error en la Operación</h1>
            
            <div class="message error">
                <h3>❌ Error</h3>
                <p><?php echo htmlspecialchars($e->getMessage()); ?></p>
            </div>
            
            <div style="text-align: center; margin-top: 30px;">
                <a href="inicio.html" class="btn-submit" style="text-decoration: none; display: inline-block; padding: 12px 24px;">Volver al Sistema</a>
            </div>
        </div>
    </body>
    </html>
    <?php
}
?> 