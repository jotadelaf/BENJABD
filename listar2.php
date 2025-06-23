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
        // Configuración de la base de datos
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "bdelafuente";

        $filtro_sexo = $_POST['filtro_sexo'] ?? '';

        // Crear conexión
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            echo '<div class="message error">Conexión fallida: ' . $conn->connect_error . '</div>';
        } else {
            // Preparar y ejecutar el procedimiento almacenado con parámetros IN y OUT
            $stmt = $conn->prepare("CALL ListarEmpleadosFiltrados(?, @total_empleados, @promedio_sueldo)");
            $stmt->bind_param("s", $filtro_sexo);
            
            if ($stmt->execute()) {
                // Obtener los resultados
                $result = $stmt->get_result();
                
                if ($result && $result->num_rows > 0) {
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
                    
                    while ($row = $result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($row['id']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['nombre']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['apellido']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['sexo']) . '</td>';
                        echo '<td>$' . number_format($row['sueldo'], 2) . '</td>';
                        echo '</tr>';
                    }
                    
                    echo '</tbody>';
                    echo '</table>';
                    
                    // Obtener los valores de las variables OUT
                    $result_stats = $conn->query("SELECT @total_empleados as total, @promedio_sueldo as promedio");
                    if ($result_stats && $row_stats = $result_stats->fetch_assoc()) {
                        echo '<div class="message success" style="margin-top: 20px;">';
                        echo 'Total de empleados: ' . $row_stats['total'] . ' | ';
                        echo 'Promedio de sueldo: $' . number_format($row_stats['promedio'], 2);
                        echo '</div>';
                    }
                } else {
                    echo '<div class="message warning">No hay empleados que coincidan con el filtro especificado.</div>';
                }
            } else {
                echo '<div class="message error">Error al listar empleados: ' . $stmt->error . '</div>';
            }
            $stmt->close();
            $conn->close();
        }
        ?>
        
        <div style="text-align: center; margin-top: 20px;">
            <a href="inicio.html" class="btn-submit" style="text-decoration: none; display: inline-block; padding: 12px 24px;">Volver al Sistema</a>
        </div>
    </div>
</body>
</html> 