<?php
// Incluir conexión a la base de datos
require_once 'conexion.php';

// Obtener conexión
$conexion = conectarBD();

echo "<h2>Verificando procedimientos almacenados</h2>";

// Verificar si existe el procedimiento EliminarEmpleado
$sql = "SHOW PROCEDURE STATUS WHERE Name = 'EliminarEmpleado'";
$resultado = $conexion->query($sql);

if ($resultado && $resultado->num_rows > 0) {
    echo "<p style='color: green;'>✓ El procedimiento EliminarEmpleado existe</p>";
} else {
    echo "<p style='color: red;'>✗ El procedimiento EliminarEmpleado NO existe</p>";
}

// Mostrar todos los procedimientos disponibles
echo "<h3>Procedimientos disponibles:</h3>";
$sql = "SHOW PROCEDURE STATUS WHERE Db = 'bdelafuente'";
$resultado = $conexion->query($sql);

if ($resultado) {
    echo "<ul>";
    while ($row = $resultado->fetch_assoc()) {
        echo "<li>" . $row['Name'] . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p>No se pudieron obtener los procedimientos</p>";
}

// Cerrar conexión
cerrarConexion($conexion);
?> 