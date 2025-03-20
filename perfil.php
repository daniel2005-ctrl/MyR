<?php
// Iniciar sesión
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php"); // Redirigir al login si no está autenticado
    exit();
}

// Datos de conexión a la base de datos
$host = "localhost";
$user = "root";
$password = "";
$dbname = "myr";

// Conectar a la base de datos
$conn = new mysqli($host, $user, $password, $dbname);

// Verificar si la conexión falló
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener los datos actuales del usuario
$id_usuario = $_SESSION['id_usuario'];
$sql = "SELECT nombre, email, password FROM usuarios WHERE id_usu = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$stmt->bind_result($nombre, $email, $password_hash);
$stmt->fetch();
$stmt->close();

$mensaje = ""; // Variable para almacenar mensajes de éxito o error

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nuevo_nombre = $_POST['nombre']; // Obtener el nuevo nombre
    $nuevo_email = $_POST['email']; // Obtener el nuevo correo
    $password_actual = $_POST['password_actual']; // Obtener la contraseña actual ingresada
    $nueva_password = $_POST['password_nueva']; // Obtener la nueva contraseña (si se cambió)

    // Verificar si la contraseña actual es correcta
    if (password_verify($password_actual, $password_hash)) {
        // Si el usuario ingresó una nueva contraseña
        if (!empty($nueva_password)) {
            $hashed_password = password_hash($nueva_password, PASSWORD_DEFAULT); // Hashear la nueva contraseña
            $sql = "UPDATE usuarios SET nombre = ?, email = ?, password = ? WHERE id_usu = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $nuevo_nombre, $nuevo_email, $hashed_password, $id_usuario);
        } else { 
            // Si el usuario no cambia la contraseña
            $sql = "UPDATE usuarios SET nombre = ?, email = ? WHERE id_usu = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssi", $nuevo_nombre, $nuevo_email, $id_usuario);
        }

        // Ejecutar la actualización y mostrar el mensaje correspondiente
if ($stmt->execute()) {
    $_SESSION['usuario'] = $nuevo_nombre; // Actualizar el nombre en la sesión
    $mensaje = "<div class='mensaje success'>Perfil actualizado con éxito.</div>";
} else {
    $mensaje = "<div class='mensaje error'>Error al actualizar el perfil.</div>";
}
$stmt->close();
} else {
    $mensaje = "<div class='mensaje error'>Contraseña actual incorrecta.</div>"; // Mensaje si la contraseña no coincide
}


}

// Cerrar la conexión con la base de datos
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="perfil.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
</head>
<body>

<div class="container">
    <aside class="sidebar">
        <h2>Configuraciones</h2>
        <ul>
          
            <li><a href="#" class="active">Perfil</a></li>
           
        </ul>
    </aside>

    <main class="profile-content">
        <h2>Perfil</h2>
        <p>Edita tu información personal.</p>

        <?php 
// Verificar si hay un mensaje antes de imprimirlo
if (!empty($mensaje)) {
    echo $mensaje;  
}
?>
      

        


        <form action="perfil.php" method="POST">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>" required>

            <label for="email">Correo:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>

            <label for="password_actual">Contraseña Actual:</label>
            <input type="password" id="password_actual" name="password_actual" required>

            <label for="password_nueva">Nueva Contraseña (opcional):</label>
            <input type="password" id="password_nueva" name="password_nueva">

            <button type="submit">Actualizar perfil</button>
            <div style="display: flex; gap: 10px;">
    
            <div style="display: flex; justify-content: center; margin-top: 15px;">
    <a href="index.html" style="text-decoration: none;">
        <button type="button" style="background-color: gray; color: white; border: none; padding: 10px 15px; cursor: pointer; border-radius: 5px;">
            Volver a Inicio
        </button>
    </a>
</div>


        </form>
    </main>
</div>


</body>
</html>
