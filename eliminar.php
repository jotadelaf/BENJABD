<?php
// Incluir archivo de conexión
require_once 'conexion.php';

// Verificar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Obtener los datos del formulario
    $campo_eliminar = $_POST['campo_eliminar'];
    $valor_eliminar = $_POST['valor_eliminar'];
    
    // Conectar a la base de datos
    $conexion = conectarBD();
    
    // Verificar si la conexión fue exitosa
    if (!$conexion) {
        $mensaje = "Error de conexión a la base de datos";
        $tipo = "error";
    } else {
        // Eliminar el empleado usando el procedimiento almacenado
        $sql = "CALL EliminarEmpleado(?, ?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ss", $campo_eliminar, $valor_eliminar);
        
        // Ejecutar la consulta
        if ($stmt->execute()) {
            $mensaje = "Empleado eliminado exitosamente";
            $tipo = "success";
        } else {
            $mensaje = "Error al eliminar empleado: " . $stmt->error;
            $tipo = "error";
        }
        
        // Cerrar conexión
        $stmt->close();
        cerrarConexion($conexion);
    }
} else {
    $mensaje = "Acceso no permitido";
    $tipo = "error";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Empleado</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Eliminar Empleado</h1>
        
        <div class="message <?php echo $tipo; ?>">
            <?php echo $mensaje; ?>
        </div>
        
        <div style="text-align: center; margin-top: 20px;">
            <a href="inicio.html" class="btn-submit" style="text-decoration: none; display: inline-block; padding: 12px 24px;">Volver al Sistema</a>
        </div>
    </div>
</body>
</html> 