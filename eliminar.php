<?php
/**
 * Sistema de Gestión de Empleados - Eliminación
 * Maneja la eliminación segura de empleados por ID
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
     * Elimina un empleado por ID
     */
    public function eliminarEmpleado($empleadoId) {
        // Validar ID
        if (!is_numeric($empleadoId) || $empleadoId <= 0) {
            throw new Exception("ID de empleado inválido");
        }
        
        // Verificar que el empleado existe
        $stmt = $this->conn->prepare("SELECT id, nombre, apellido FROM empleados WHERE id = ?");
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
        $nombreCompleto = $empleado['nombre'] . ' ' . $empleado['apellido'];
        $stmt->close();
        
        // Eliminar empleado usando procedimiento almacenado
        $stmt = $this->conn->prepare("CALL EliminarEmpleado(?, ?)");
        $campo = "id";
        $stmt->bind_param("ss", $campo, $empleadoId);
        
        if (!$stmt->execute()) {
            throw new Exception("Error al eliminar empleado: " . $stmt->error);
        }
        
        $affectedRows = $stmt->affected_rows;
        $stmt->close();
        
        if ($affectedRows === 0) {
            throw new Exception("No se pudo eliminar el empleado");
        }
        
        return [
            'empleado' => $nombreCompleto,
            'registros_eliminados' => $affectedRows
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
        $empleadoId = $_POST['empleado_id_eliminar'] ?? '';
        
        if (empty($empleadoId)) {
            throw new Exception("ID de empleado no proporcionado");
        }
        
        $resultado = $manager->eliminarEmpleado($empleadoId);
        
        // Mostrar página de éxito
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Empleado Eliminado</title>
            <link rel="stylesheet" href="styles.css">
        </head>
        <body>
            <div class="container">
                <h1>Empleado Eliminado Exitosamente</h1>
                
                <div class="message success">
                    <h3>✅ Eliminación Completada</h3>
                    <p><strong>Empleado:</strong> <?php echo htmlspecialchars($resultado['empleado']); ?></p>
                    <p><strong>Registros Eliminados:</strong> <?php echo $resultado['registros_eliminados']; ?></p>
                    <p><em>La información del empleado ha sido eliminada permanentemente de la base de datos.</em></p>
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
        <title>Error al Eliminar</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <div class="container">
            <h1>Error al Eliminar Empleado</h1>
            
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