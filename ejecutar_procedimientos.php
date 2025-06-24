<?php
// Incluir conexión a la base de datos
require_once 'conexion.php';

// Obtener conexión
$conexion = conectarBD();

echo "<h2>Ejecutando procedimientos almacenados</h2>";

// Leer el archivo de procedimientos
$sql_file = file_get_contents('procedimientos.sql');

if ($sql_file) {
    // Dividir por DELIMITER
    $statements = explode('DELIMITER', $sql_file);
    
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (!empty($statement) && !str_starts_with($statement, '--')) {
            // Ejecutar cada procedimiento
            if ($conexion->multi_query($statement)) {
                echo "<p style='color: green;'>✓ Procedimiento ejecutado correctamente</p>";
            } else {
                echo "<p style='color: red;'>✗ Error al ejecutar: " . $conexion->error . "</p>";
            }
        }
    }
} else {
    echo "<p style='color: red;'>No se pudo leer el archivo procedimientos.sql</p>";
}

echo "<p><a href='verificar_procedimientos.php'>Verificar procedimientos</a></p>";

// Cerrar conexión
cerrarConexion($conexion);
?> 