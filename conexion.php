<?php
// Configuración de la base de datos
$servidor = "localhost";
$usuario = "root";
$password = "";
$base_datos = "bdelafuente";

// Función para conectar a la base de datos
function conectarBD() {
    global $servidor, $usuario, $password, $base_datos;
    
    $conexion = new mysqli($servidor, $usuario, $password, $base_datos);
    
    // Verificar si la conexión fue exitosa
    if ($conexion->connect_error) {
        return false;
    }
    
    return $conexion;
}

// Función para cerrar la conexión
function cerrarConexion($conexion) {
    if ($conexion) {
        $conexion->close();
    }
}
?> 