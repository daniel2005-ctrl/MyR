<?php
// Inicia la sesión para poder gestionar los datos del usuario durante su sesión.
session_start();

// Definir las credenciales de conexión a la base de datos.
$host = "localhost";  // El servidor de la base de datos (en este caso, es local).
$user = "root";  // El usuario de la base de datos (en este caso, "root").
$password = "";  // La contraseña de la base de datos (vacío por defecto en muchas instalaciones locales).
$dbname = "myr";  // El nombre de la base de datos a la que nos conectaremos.

// Crear una nueva conexión con la base de datos utilizando las credenciales proporcionadas.
$conn = new mysqli($host, $user, $password, $dbname);

// Verificar si ocurrió un error al intentar conectar a la base de datos.
if ($conn->connect_error) {
    // Si hay un error en la conexión, el script se detiene y devuelve un error en formato JSON.
    die(json_encode(["success" => false, "message" => "Error de conexión: " . $conn->connect_error]));
}

// Verificar si el método de la solicitud es POST (esto indica que el formulario de inicio de sesión ha sido enviado).
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener el correo electrónico y la contraseña desde los datos enviados en el formulario.
    $email = $_POST['email'] ?? "";  // Si no se proporciona, se asigna un valor vacío por defecto.
    $password = $_POST['password'] ?? "";  // Lo mismo con la contraseña.

    // Verificar si los campos de correo electrónico o contraseña están vacíos.
    if (empty($email) || empty($password)) {
        // Si falta algún dato, se devuelve un mensaje de error en formato JSON y se termina el script.
        echo json_encode(["success" => false, "message" => "Faltan datos."]);
        exit();
    }

    // Preparar la consulta SQL para buscar al usuario con el correo electrónico proporcionado.
    $sql = "SELECT id_usu, nombre, password FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);  // Preparar la consulta.
    $stmt->bind_param("s", $email);  // Enlazar el parámetro del correo electrónico (tipo "s" para string).
    $stmt->execute();  // Ejecutar la consulta.
    $stmt->store_result();  // Almacenar los resultados de la consulta.

    // Verificar si se encontró un usuario con el correo electrónico proporcionado.
    if ($stmt->num_rows > 0) {
        // Si el usuario existe, se obtiene el resultado de la consulta.
        $stmt->bind_result($id, $nombre, $hashed_password);  // Enlazar los resultados con variables locales.
        $stmt->fetch();  // Obtener el primer resultado (en este caso, solo hay uno por el correo único).

        // Verificar si la contraseña proporcionada coincide con la contraseña almacenada en la base de datos.
        if (password_verify($password, $hashed_password)) {
            // Si la contraseña es correcta, se guardan los datos del usuario en la sesión.
            $_SESSION['usuario'] = $nombre;  // Guardar el nombre del usuario en la sesión.
            $_SESSION['id_usuario'] = $id;  // Guardar el ID del usuario en la sesión.

            // Devolver una respuesta JSON indicando que el inicio de sesión fue exitoso.
            echo json_encode(["success" => true, "usuario" => $nombre]);
        } else {
            // Si la contraseña es incorrecta, devolver un mensaje de error.
            echo json_encode(["success" => false, "message" => "Contraseña incorrecta."]);
        }
    } else {
        // Si no se encuentra un usuario con el correo electrónico proporcionado, devolver un mensaje de error.
        echo json_encode(["success" => false, "message" => "No existe una cuenta con este correo."]);
    }

    // Cerrar la declaración preparada para liberar los recursos.
    $stmt->close();
}

// Cerrar la conexión con la base de datos después de que se haya terminado la operación.
$conn->close();
?>
