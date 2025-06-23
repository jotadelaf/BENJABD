<?php
// Configuración de la base de datos
$servername = "localhost";
$username = "root"; // Cambia si tu usuario de MySQL es diferente
$password = "";    // Cambia si tu contraseña de MySQL es diferente
$dbname = "bdelafuente";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'] ?? '';
    $apellido = $_POST['apellido'] ?? '';
    $sexo = $_POST['sexo'] ?? '';
    $sueldo = isset($_POST['sueldo']) ? (int)$_POST['sueldo'] : 0;

    // Validación básica
    if ($nombre && $apellido && $sexo && $sueldo !== '') {
        // Crear conexión
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        // Preparar y ejecutar el procedimiento almacenado
        $stmt = $conn->prepare("CALL InsertaEmpleado(?, ?, ?, ?)");
        $stmt->bind_param("sssi", $nombre, $apellido, $sexo, $sueldo);
        
        if ($stmt->execute()) {
            echo "<p>Registro insertado correctamente.</p>";
        } else {
            echo "<p>Error al insertar: " . $stmt->error . "</p>";
        }
        $stmt->close();
        $conn->close();
    } else {
        echo "<p>Por favor, complete todos los campos.</p>";
    }
} else {
    echo "<p>Método de solicitud no válido.</p>";
}
?>
