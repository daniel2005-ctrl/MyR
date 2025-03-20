<?php
session_start();

$host = "localhost";
$user = "root";
$password = "";
$dbname = "myr";

// Conexión a la base de datos
$conn = new mysqli($host, $user, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Error de conexión: " . $conn->connect_error]));
}

// Verificar si la solicitud es POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Verificar si el correo ya está registrado
    $sql = "SELECT id_usu FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Este correo ya está registrado."]);
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $insert_sql = "INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("sss", $nombre, $email, $hashed_password);

        if ($insert_stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "¡Registro exitoso! Ahora puede iniciar sesión."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error al registrar usuario."]);
        }
        $insert_stmt->close();
    }
    $stmt->close();
}
$conn->close();
?>
