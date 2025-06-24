<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Empleados</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Lista de Empleados</h1>
        
        <?php
        // Incluir archivo de conexión
        require_once 'conexion.php';

        // Conectar a la base de datos
        $conexion = conectarBD();

        // Verificar si la conexión fue exitosa
        if (!$conexion) {
            echo "Error de conexión a la base de datos";
            exit;
        }

        // Ejecutar el procedimiento almacenado para listar todos los empleados
        $resultado = $conexion->query("CALL ListarEmpleados()");

        // Verificar si la consulta fue exitosa
        if ($resultado) {
            // Verificar si hay empleados
            if ($resultado->num_rows > 0) {
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
                    echo '<td>$ ' . number_format($fila['sueldo'], 0, ',', '.') . '</td>';
                    echo '</tr>';
                }
                
                echo '</tbody>';
                echo '</table>';
                
                // Mostrar total de empleados
                echo '<div class="message success" style="margin-top: 20px;">';
                echo 'Total de empleados: ' . $resultado->num_rows;
                echo '</div>';
            } else {
                echo '<div class="message warning">No hay empleados registrados en la base de datos.</div>';
            }
        } else {
            echo '<div class="message error">Error al listar empleados: ' . $conexion->error . '</div>';
        }

        // Cerrar conexión
        cerrarConexion($conexion);
        ?>
        
        <div style="text-align: center; margin-top: 20px;">
            <a href="inicio.html" class="btn-submit" style="text-decoration: none; display: inline-block; padding: 12px 24px;">Volver al Sistema</a>
        </div>
    </div>
</body>
</html> 