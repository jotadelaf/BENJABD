<?php
// Incluir archivo de conexión
require_once 'conexion.php';

// Verificar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Obtener los datos del formulario
    $campo_modificar = $_POST['campo_modificar'];
    $nuevo_valor = $_POST['nuevo_valor'];
    $condicion_campo = $_POST['condicion_campo'];
    $condicion_valor = $_POST['condicion_valor'];
    
    // Conectar a la base de datos
    $conexion = conectarBD();
    
    // Verificar si la conexión fue exitosa
    if (!$conexion) {
        $mensaje = "Error de conexión a la base de datos";
        $tipo = "error";
    } else {
        // Actualizar el empleado usando el procedimiento almacenado
        $sql = "CALL ActualizarEmpleado(?, ?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ssss", $campo_modificar, $nuevo_valor, $condicion_campo, $condicion_valor);
        
        // Ejecutar la consulta
        if ($stmt->execute()) {
            $mensaje = "Empleado actualizado exitosamente";
            $tipo = "success";
        } else {
            $mensaje = "Error al actualizar empleado: " . $stmt->error;
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
    <title>Modificar Empleado</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Modificar Empleado</h1>
        
        <div class="message <?php echo $tipo; ?>">
            <?php echo $mensaje; ?>
        </div>
        
        <div style="text-align: center; margin-top: 20px;">
            <a href="inicio.html" class="btn-submit" style="text-decoration: none; display: inline-block; padding: 12px 24px;">Volver al Sistema</a>
        </div>
    </div>
</body>
</html> 