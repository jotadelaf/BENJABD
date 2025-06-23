<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Empleado</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Eliminar Empleado</h1>
        
        <?php
        // Configuración de la base de datos
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "bdelafuente";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $campo_eliminar = $_POST['campo_eliminar'] ?? '';
            $valor_eliminar = $_POST['valor_eliminar'] ?? '';

            // Validación básica
            if ($campo_eliminar && $valor_eliminar !== '') {
                // Crear conexión
                $conn = new mysqli($servername, $username, $password, $dbname);
                if ($conn->connect_error) {
                    echo '<div class="message error">Conexión fallida: ' . $conn->connect_error . '</div>';
                } else {
                    // Preparar y ejecutar el procedimiento almacenado
                    $stmt = $conn->prepare("CALL EliminarEmpleado(?, ?)");
                    $stmt->bind_param("ss", $campo_eliminar, $valor_eliminar);
                    
                    if ($stmt->execute()) {
                        $affected_rows = $stmt->affected_rows;
                        if ($affected_rows > 0) {
                            echo '<div class="message success">¡Empleado(s) eliminado(s) correctamente! Registros eliminados: ' . $affected_rows . '</div>';
                        } else {
                            echo '<div class="message warning">No se encontraron registros que coincidan con la condición especificada.</div>';
                        }
                    } else {
                        echo '<div class="message error">Error al eliminar: ' . $stmt->error . '</div>';
                    }
                    $stmt->close();
                    $conn->close();
                }
            } else {
                echo '<div class="message warning">Por favor, complete todos los campos.</div>';
            }
        } else {
            echo '<div class="message error">Método de solicitud no válido.</div>';
        }
        ?>
        
        <div style="text-align: center; margin-top: 20px;">
            <a href="inicio.html" class="btn-submit" style="text-decoration: none; display: inline-block; padding: 12px 24px;">Volver al Sistema</a>
        </div>
    </div>
</body>
</html> 