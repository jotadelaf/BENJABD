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
        // Configuración de la base de datos
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "bdelafuente";

        // Crear conexión
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            echo '<div class="message error">Conexión fallida: ' . $conn->connect_error . '</div>';
        } else {
            // Ejecutar el procedimiento almacenado
            $result = $conn->query("CALL ListarEmpleados()");
            
            if ($result) {
                if ($result->num_rows > 0) {
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
                } else {
                    echo '<div class="message warning">No hay empleados registrados en la base de datos.</div>';
                }
            } else {
                echo '<div class="message error">Error al listar empleados: ' . $conn->error . '</div>';
            }
            $conn->close();
        }
        ?>
        
        <div style="text-align: center; margin-top: 20px;">
            <a href="inicio.html" class="btn-submit" style="text-decoration: none; display: inline-block; padding: 12px 24px;">Volver al Sistema</a>
        </div>
    </div>
</body>
</html> 