<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Empleados Filtrados</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Lista de Empleados Filtrados</h1>
        
        <?php
        // Incluir archivo de conexión
        require_once 'conexion.php';

        // Obtener el filtro de sexo del formulario
        $filtro_sexo = $_POST['filtro_sexo'] ?? '';

        // Conectar a la base de datos
        $conexion = conectarBD();

        // Verificar si la conexión fue exitosa
        if (!$conexion) {
            echo "Error de conexión a la base de datos";
            exit;
        }

        // Ejecutar el procedimiento almacenado para listar empleados filtrados
        $stmt = $conexion->prepare("CALL ListarEmpleadosFiltrados(?, @total_empleados, @promedio_sueldo)");
        $stmt->bind_param("s", $filtro_sexo);

        // Verificar si la consulta fue exitosa
        if ($stmt->execute()) {
            // Obtener los resultados
            $resultado = $stmt->get_result();
            
            // Verificar si hay empleados
            if ($resultado && $resultado->num_rows > 0) {
                // Crear la tabla
                echo '<table class="empleados-table">';
                echo '<thead>';
                echo '<tr>';
                echo '<th>ID</th>';
                echo '<th>Nombre</th>';
                echo '<th>Apellido</th>';
                echo '<th>Sexo</th>';
                echo '<th>Sueldo</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
                
                // Mostrar cada empleado
                while ($fila = $resultado->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . $fila['id'] . '</td>';
                    echo '<td>' . $fila['nombre'] . '</td>';
                    echo '<td>' . $fila['apellido'] . '</td>';
                    echo '<td>' . $fila['sexo'] . '</td>';
                    echo '<td>$' . number_format($fila['sueldo'], 2) . '</td>';
                    echo '</tr>';
                }
                
                echo '</tbody>';
                echo '</table>';
                
                // Obtener las estadísticas del procedimiento almacenado
                $resultado_stats = $conexion->query("SELECT @total_empleados as total, @promedio_sueldo as promedio");
                if ($resultado_stats && $fila_stats = $resultado_stats->fetch_assoc()) {
                    echo '<div class="message success" style="margin-top: 20px;">';
                    echo 'Total de empleados: ' . $fila_stats['total'] . ' | ';
                    echo 'Promedio de sueldo: $' . number_format($fila_stats['promedio'], 2);
                    echo '</div>';
                }
            } else {
                echo '<div class="message warning">No hay empleados que coincidan con el filtro especificado.</div>';
            }
        } else {
            echo '<div class="message error">Error al listar empleados: ' . $stmt->error . '</div>';
        }

        // Cerrar conexión
        $stmt->close();
        cerrarConexion($conexion);
        ?>
        
        <div style="text-align: center; margin-top: 20px;">
            <a href="inicio.html" class="btn-submit" style="text-decoration: none; display: inline-block; padding: 12px 24px;">Volver al Sistema</a>
        </div>
    </div>
</body>
</html> 