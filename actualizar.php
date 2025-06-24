<?php
// Incluir conexión a la base de datos
require_once 'conexion.php';

// Obtener conexión
$conexion = conectarBD();

// Verificar si es una búsqueda por nombre
if (isset($_POST['nombre']) && !empty($_POST['nombre'])) {
    $nombre = $_POST['nombre'];
    
    // Buscar empleados
    $sql = "SELECT id, nombre, apellido, sexo, sueldo FROM empleados WHERE nombre LIKE ? ORDER BY nombre";
    $stmt = $conexion->prepare($sql);
    $nombreBuscar = "%$nombre%";
    $stmt->bind_param("s", $nombreBuscar);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    // Mostrar resultados
    if ($resultado->num_rows > 0) {
        echo '<table class="empleados-table">';
        echo '<thead><tr><th>ID</th><th>Nombre</th><th>Apellido</th><th>Sexo</th><th>Sueldo</th><th>Acción</th></tr></thead>';
        echo '<tbody>';
        
        while ($empleado = $resultado->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $empleado['id'] . '</td>';
            echo '<td>' . htmlspecialchars($empleado['nombre']) . '</td>';
            echo '<td>' . htmlspecialchars($empleado['apellido']) . '</td>';
            echo '<td>' . htmlspecialchars($empleado['sexo']) . '</td>';
            echo '<td>$ ' . number_format($empleado['sueldo'], 0, ',', '.') . '</td>';
            echo '<td><button onclick="seleccionarEmpleado(' . $empleado['id'] . ', \'' . htmlspecialchars($empleado['nombre']) . '\', \'' . $empleado['sueldo'] . '\')" class="btn-danger" style="background: #007bff; border-color: #0056b3;">Seleccionar</button></td>';
            echo '</tr>';
        }
        
        echo '</tbody></table>';
    } else {
        echo '<div class="no-resultados">No se encontraron empleados con ese nombre</div>';
    }
    
    $stmt->close();
}

// Verificar si es una modificación de sueldo
elseif (isset($_POST['id']) && isset($_POST['nuevo_sueldo'])) {
    $id = $_POST['id'];
    $nuevoSueldo = $_POST['nuevo_sueldo'];
    
    // Validar sueldo
    if (!is_numeric($nuevoSueldo) || $nuevoSueldo < 0) {
        echo 'Error: El sueldo debe ser un número válido mayor o igual a 0';
        cerrarConexion($conexion);
        exit;
    }
    
    // Modificar sueldo
    $stmt = $conexion->prepare("CALL ActualizarEmpleado('sueldo', ?, 'id', ?)");
    $stmt->bind_param("ss", $nuevoSueldo, $id);
    $stmt->execute();
    
    // Verificar resultado
    if ($stmt->affected_rows > 0) {
        echo 'Sueldo modificado correctamente';
    } else {
        echo 'No se pudo modificar el sueldo';
    }
    
    $stmt->close();
}

// Cerrar conexión
cerrarConexion($conexion);
?> 