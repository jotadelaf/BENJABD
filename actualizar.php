<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Empleado</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Actualizar Empleado</h1>
        
        <?php
        // Configuración de la base de datos
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "bdelafuente";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $campo_modificar = $_POST['campo_modificar'] ?? '';
            $nuevo_valor = $_POST['nuevo_valor'] ?? '';
            $condicion_campo = $_POST['condicion_campo'] ?? '';
            $condicion_valor = $_POST['condicion_valor'] ?? '';

            // Validación básica
            if ($campo_modificar && $nuevo_valor !== '' && $condicion_campo && $condicion_valor !== '') {
                // Crear conexión
                $conn = new mysqli($servername, $username, $password, $dbname);
                if ($conn->connect_error) {
                    echo '<div class="message error">Conexión fallida: ' . $conn->connect_error . '</div>';
                } else {
                    // Preparar y ejecutar el procedimiento almacenado
                    $stmt = $conn->prepare("CALL ActualizarEmpleado(?, ?, ?, ?)");
                    $stmt->bind_param("ssss", $campo_modificar, $nuevo_valor, $condicion_campo, $condicion_valor);
                    
                    if ($stmt->execute()) {
                        $affected_rows = $stmt->affected_rows;
                        if ($affected_rows > 0) {
                            echo '<div class="message success">¡Empleado(s) actualizado(s) correctamente! Registros afectados: ' . $affected_rows . '</div>';
                        } else {
                            echo '<div class="message warning">No se encontraron registros que coincidan con la condición especificada.</div>';
                        }
                    } else {
                        echo '<div class="message error">Error al actualizar: ' . $stmt->error . '</div>';
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