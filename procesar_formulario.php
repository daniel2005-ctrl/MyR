<?php
date_default_timezone_set("America/Bogota"); // Configurar la zona horaria

// Configuración de la conexión a la base de datos
$servidor = "localhost";
$usuario = "root"; // Cambia si tienes otro usuario
$clave = ""; // Cambia si tienes contraseña en MySQL
$base_datos = "myr"; // Asegúrate de que el nombre de la BD sea correcto

// Crear conexión con MySQL usando mysqli
$conn = new mysqli($servidor, $usuario, $clave, $base_datos);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Verificar que el formulario se haya enviado por método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener y limpiar datos del formulario
    $nombre = trim($_POST['nombre']);
    $telefono = trim($_POST['telefono']);
    $email = trim($_POST['email']);
    $id_pro = trim($_POST['proyecto']); // ID del proyecto seleccionado
    $fecha_envio = date("Y-m-d H:i:s"); // Fecha y hora actual

    // Validar que los campos no estén vacíos
    if (!empty($nombre) && !empty($telefono)  && !empty($email) && !empty($id_pro)) {
        
        // Preparar la consulta SQL para evitar SQL Injection
        $stmt = $conn->prepare("INSERT INTO clientes (nombre, telefono, email, id_pro, fecha_envio) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssis", $nombre, $telefono,  $email, $id_pro, $fecha_envio);

        // Ejecutar la consulta y verificar si fue exitosa
        if ($stmt->execute()) {
            echo "<script>
                    alert('¡Formulario enviado correctamente!');
                    window.location.href = 'formulario.html';
                  </script>";
        } else {
            echo "<script>
                    alert('Error al guardar los datos: " . $stmt->error . "');
                    window.location.href = 'formulario.html';
                  </script>";
        }

        // Cerrar el statement
        $stmt->close();
    } else {
        echo "<script>
                alert('Por favor, completa todos los campos.');
                window.location.href = 'formulario.html';
              </script>";
    }
}

// Cerrar conexión
$conn->close();
?>
