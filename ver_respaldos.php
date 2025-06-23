<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Respaldos - Sistema de Empleados</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Respaldos del Sistema</h1>
        
        <!-- Navegación para respaldos -->
        <nav class="nav-tabs">
            <button class="nav-btn active" onclick="showBackup('insert')">Respaldos INSERT</button>
            <button class="nav-btn" onclick="showBackup('update')">Respaldos UPDATE</button>
        </nav>

        <!-- Respaldos de INSERT -->
        <div id="insert" class="backup-section active">
            <h2>Respaldos de Inserción</h2>
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
                // Consultar respaldos de INSERT
                $result = $conn->query("SELECT * FROM temp_Empleados_Insert ORDER BY fecha_insercion DESC");
                
                if ($result && $result->num_rows > 0) {
                    echo '<table class="empleados-table">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th>ID Respaldo</th>';
                    echo '<th>ID Empleado</th>';
                    echo '<th>Nombre</th>';
                    echo '<th>Apellido</th>';
                    echo '<th>Sexo</th>';
                    echo '<th>Sueldo</th>';
                    echo '<th>Fecha Inserción</th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';
                    
                    while ($row = $result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($row['id_respaldo']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['id_empleado']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['nombre']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['apellido']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['sexo']) . '</td>';
                        echo '<td>$' . number_format($row['sueldo'], 2) . '</td>';
                        echo '<td>' . htmlspecialchars($row['fecha_insercion']) . '</td>';
                        echo '</tr>';
                    }
                    
                    echo '</tbody>';
                    echo '</table>';
                } else {
                    echo '<div class="message warning">No hay respaldos de inserción registrados.</div>';
                }
            }
            ?>
        </div>

        <!-- Respaldos de UPDATE -->
        <div id="update" class="backup-section">
            <h2>Respaldos de Actualización</h2>
            <?php
            if (!$conn->connect_error) {
                // Consultar respaldos de UPDATE
                $result = $conn->query("SELECT * FROM temp_Empleados_Update ORDER BY fecha_modificacion DESC");
                
                if ($result && $result->num_rows > 0) {
                    echo '<table class="empleados-table">';
                    echo '<thead>';
                    echo '<tr>';
                    echo '<th>ID Respaldo</th>';
                    echo '<th>ID Empleado</th>';
                    echo '<th>Campo Modificado</th>';
                    echo '<th>Valor Anterior</th>';
                    echo '<th>Valor Nuevo</th>';
                    echo '<th>Fecha Modificación</th>';
                    echo '</tr>';
                    echo '</thead>';
                    echo '<tbody>';
                    
                    while ($row = $result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($row['id_respaldo']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['id_empleado']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['campo_modificado']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['valor_anterior']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['valor_nuevo']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['fecha_modificacion']) . '</td>';
                        echo '</tr>';
                    }
                    
                    echo '</tbody>';
                    echo '</table>';
                } else {
                    echo '<div class="message warning">No hay respaldos de actualización registrados.</div>';
                }
                $conn->close();
            }
            ?>
        </div>
        
        <div style="text-align: center; margin-top: 20px;">
            <a href="inicio.html" class="btn-submit" style="text-decoration: none; display: inline-block; padding: 12px 24px;">Volver al Sistema</a>
        </div>
    </div>

    <script>
        function showBackup(backupType) {
            // Ocultar todas las secciones
            const sections = document.querySelectorAll('.backup-section');
            sections.forEach(section => section.classList.remove('active'));
            
            // Mostrar la sección seleccionada
            document.getElementById(backupType).classList.add('active');
            
            // Actualizar botones de navegación
            const buttons = document.querySelectorAll('.nav-btn');
            buttons.forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
        }
    </script>
</body>
</html> 