<?php
session_start();
header('Content-Type: application/json'); // Asegura que el servidor devuelve JSON
ob_clean(); // Limpia cualquier salida previa

// Configurar la base de datos
$host = "localhost";
$user = "root";
$password = "";
$dbname = "myr";

// Conectar a la base de datos
$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Error de conexión: " . $conn->connect_error]));
}

// Verificar si los datos llegan correctamente
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['newnombre'] ?? '';
    $email = $_POST['newemail'] ?? '';
    $password = $_POST['newpassword'] ?? '';

    // 🔹 Guardar los datos recibidos en un archivo para depuración
    file_put_contents("debug_registro.txt", "Nombre: $nombre, Email: $email, Password: $password\n", FILE_APPEND);

    if (empty($nombre) || empty($email) || empty($password)) {
        echo json_encode(["success" => false, "message" => "Todos los campos son obligatorios."]);
        exit;
    }

    // Verificar si el email ya está registrado
    $sql = "SELECT email FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo json_encode(["success" => false, "message" => "El correo ya está registrado."]);
        exit;
    }
    $stmt->close();

    // Encriptar la contraseña
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insertar nuevo usuario
    $sql = "INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $nombre, $email, $hashed_password);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Registro exitoso"]);
    } else {
        echo json_encode(["success" => false, "message" => "Error al registrar usuario."]);
    }
    $stmt->close();
}
$conn->close();

// 🔹 Mostrar datos en la respuesta JSON para depuración
echo json_encode(["success" => true, "message" => "Registro exitoso"]);
exit; // 🔹 Finaliza el script para evitar texto extra


?>
