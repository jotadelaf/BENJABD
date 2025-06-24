<?php
// Incluir archivo de conexión
require_once 'conexion.php';

// Conectar a la base de datos
$conexion = conectarBD();

// Verificar si la conexión fue exitosa
if (!$conexion) {
    $error_conexion = "Error de conexión a la base de datos";
}
?>

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
        
        <?php if (isset($error_conexion)): ?>
            <div class="message error"><?php echo $error_conexion; ?></div>
        <?php endif; ?>
        
        <!-- Navegación para respaldos -->
        <nav class="nav-tabs">
            <button class="nav-btn active" onclick="mostrarRespaldo('insert')">Respaldos INSERT</button>
            <button class="nav-btn" onclick="mostrarRespaldo('update')">Respaldos UPDATE</button>
        </nav>

        <!-- Respaldos de INSERT -->
        <div id="insert" class="backup-section active">
            <h2>Respaldos de Inserción</h2>
            <?php
            if (!isset($error_conexion)) {
                // Consultar respaldos de INSERT
                $resultado = $conexion->query("SELECT * FROM temp_Empleados_Insert ORDER BY fecha_insercion DESC");
                
                if ($resultado && $resultado->num_rows > 0) {
                    // Crear tabla
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
                    
                    // Mostrar cada respaldo
                    while ($fila = $resultado->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . $fila['id_respaldo'] . '</td>';
                        echo '<td>' . $fila['id_empleado'] . '</td>';
                        echo '<td>' . $fila['nombre'] . '</td>';
                        echo '<td>' . $fila['apellido'] . '</td>';
                        echo '<td>' . $fila['sexo'] . '</td>';
                        echo '<td>$ ' . number_format($fila['sueldo'], 0, ',', '.') . '</td>';
                        echo '<td>' . $fila['fecha_insercion'] . '</td>';
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
            if (!isset($error_conexion)) {
                // Consultar respaldos de UPDATE
                $resultado = $conexion->query("SELECT * FROM temp_Empleados_Update ORDER BY fecha_modificacion DESC");
                
                if ($resultado && $resultado->num_rows > 0) {
                    // Crear tabla
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
                    
                    // Mostrar cada respaldo
                    while ($fila = $resultado->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . $fila['id_respaldo'] . '</td>';
                        echo '<td>' . $fila['id_empleado'] . '</td>';
                        echo '<td>' . $fila['campo_modificado'] . '</td>';
                        echo '<td>' . $fila['valor_anterior'] . '</td>';
                        echo '<td>' . $fila['valor_nuevo'] . '</td>';
                        echo '<td>' . $fila['fecha_modificacion'] . '</td>';
                        echo '</tr>';
                    }
                    
                    echo '</tbody>';
                    echo '</table>';
                } else {
                    echo '<div class="message warning">No hay respaldos de actualización registrados.</div>';
                }
            }
            
            // Cerrar conexión
            if (isset($conexion)) {
                cerrarConexion($conexion);
            }
            ?>
        </div>
        
        <div style="text-align: center; margin-top: 20px;">
            <a href="inicio.html" class="btn-submit" style="text-decoration: none; display: inline-block; padding: 12px 24px;">Volver al Sistema</a>
        </div>
    </div>

    <script>
        // Función para mostrar diferentes tipos de respaldos
        function mostrarRespaldo(tipo) {
            // Ocultar todas las secciones
            const secciones = document.querySelectorAll('.backup-section');
            secciones.forEach(seccion => seccion.classList.remove('active'));
            
            // Mostrar la sección seleccionada
            document.getElementById(tipo).classList.add('active');
            
            // Actualizar botones de navegación
            const botones = document.querySelectorAll('.nav-btn');
            botones.forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
        }
    </script>
</body>
</html> 