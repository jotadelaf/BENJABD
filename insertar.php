<?php
// Incluir archivo de conexión
require_once 'conexion.php';

// Verificar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Obtener los datos del formulario
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $sexo = $_POST['sexo'];
    $sueldo = $_POST['sueldo'];
    
    // Conectar a la base de datos
    $conexion = conectarBD();
    
    // Verificar si la conexión fue exitosa
    if (!$conexion) {
        $mensaje = "Error de conexión a la base de datos";
        $tipo = "error";
    } else {
        // Insertar el empleado usando el procedimiento almacenado
        $sql = "CALL InsertaEmpleado(?, ?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("sssd", $nombre, $apellido, $sexo, $sueldo);
        
        // Ejecutar la consulta
        if ($stmt->execute()) {
            $mensaje = "Empleado registrado exitosamente";
            $tipo = "success";
        } else {
            $mensaje = "Error al registrar empleado: " . $stmt->error;
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
    <title>Registrar Empleado</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Registrar Empleado</h1>
        
        <div class="message <?php echo $tipo; ?>">
            <?php echo $mensaje; ?>
        </div>
        
        <div style="text-align: center; margin-top: 20px;">
            <a href="inicio.html" class="btn-submit" style="text-decoration: none; display: inline-block; padding: 12px 24px;">Volver al Sistema</a>
        </div>
    </div>
</body>
</html>
