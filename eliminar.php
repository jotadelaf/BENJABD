<?php
// Incluir conexión a la base de datos
require_once 'conexion.php';

// Agregar estilos CSS específicos para botones
echo '<link rel="stylesheet" href="botones.css">';

// Obtener conexión
$conexion = conectarBD();

// Verificar si es una búsqueda por nombre
if (isset($_POST['nombre']) && !empty($_POST['nombre'])) {
    $nombre = $_POST['nombre'];
    
    // Buscar empleados usando el procedimiento almacenado
    $stmt = $conexion->prepare("CALL EliminarEmpleado('BUSCAR', 'nombre', ?)");
    if (!$stmt) {
        echo '<div class="no-resultados">Error en la consulta: ' . $conexion->error . '</div>';
        cerrarConexion($conexion);
        exit;
    }
    
    $stmt->bind_param("s", $nombre);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    // Verificar si hay resultados
    if ($resultado && $resultado->num_rows > 0) {
        echo '<table class="empleados-table">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>ID</th>';
        echo '<th>Nombre</th>';
        echo '<th>Apellido</th>';
        echo '<th>Sexo</th>';
        echo '<th>Sueldo</th>';
        echo '<th>Acción</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        
        while ($empleado = $resultado->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $empleado['id'] . '</td>';
            echo '<td>' . htmlspecialchars($empleado['nombre']) . '</td>';
            echo '<td>' . htmlspecialchars($empleado['apellido']) . '</td>';
            echo '<td>' . htmlspecialchars($empleado['sexo']) . '</td>';
            echo '<td>$ ' . number_format($empleado['sueldo'], 0, ',', '.') . '</td>';
            echo '<td><button onclick="eliminarEmpleado(' . $empleado['id'] . ')" class="btn-danger">Eliminar</button></td>';
            echo '</tr>';
        }
        
        echo '</tbody>';
        echo '</table>';
    } else {
        echo '<div class="no-resultados">No se encontraron empleados con ese nombre</div>';
    }
    
    $stmt->close();
}

// Verificar si es una eliminación por ID
elseif (isset($_POST['id']) && !empty($_POST['id'])) {
    $id = $_POST['id'];
    
    // Eliminar empleado usando el procedimiento almacenado
    $stmt = $conexion->prepare("CALL EliminarEmpleado('ELIMINAR', 'id', ?)");
    if (!$stmt) {
        echo 'Error en la consulta: ' . $conexion->error;
        cerrarConexion($conexion);
        exit;
    }
    
    $stmt->bind_param("s", $id);
    $stmt->execute();
    
    // Verificar si se eliminó correctamente
    if ($stmt->affected_rows > 0) {
        echo 'Empleado eliminado correctamente';
    } else {
        echo 'No se pudo eliminar el empleado';
    }
    
    $stmt->close();
}

// Si no se recibió ningún parámetro válido
else {
    echo 'Error: No se especificó la acción a realizar';
}

// Cerrar conexión
cerrarConexion($conexion);
?> 