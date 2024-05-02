<?php
session_start(); // Iniciar sesión
require_once 'econect.php';

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener el nombre de usuario y la contraseña del formulario
    $nombre_usuario = $_POST['nombre_usuario'];
    $contraseña = $_POST['contraseña'];

    // Consulta SQL para verificar si el usuario existe
    $sql = "SELECT Contraseña, UsuarioID FROM usuarios WHERE NombreUsuario = '$nombre_usuario'";
    
    // Ejecutar la consulta
    $resultado = mysqli_query($db, $sql); 

    // Verificar si se encontró el usuario
    if ($resultado) {
        if (mysqli_num_rows($resultado) == 1) {
            // Obtener la fila del resultado como un array asociativo
            $fila = mysqli_fetch_assoc($resultado);

            // Verificar si la contraseña ingresada coincide con la contraseña almacenada en la base de datos
            if ($contraseña == $fila['Contraseña']) {
                // Las credenciales son válidas, iniciar sesión
                $_SESSION['nombre_usuario'] = $nombre_usuario;
                $_SESSION['usuario_id'] = $fila['UsuarioID']; // Guardar el ID del usuario en la sesión

                // Redireccionar a otra página
                header("Location: ticketvisor.php");
                exit();
            } else {
                echo "Contraseña incorrecta.";
            }
        } else {
            echo "El usuario no existe.";
        }
    } else {
        echo "Error al ejecutar la consulta: " . mysqli_error($db);
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Iniciar Sesión</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            height: 100vh;
            margin: 0;
        }
        form {
            text-align: center;
            width: 400px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        form label {
            display: block;
            text-align: left;
            margin-bottom: 5px;
        }
        form input[type="text"],
        form input[type="password"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            margin-bottom: 10px;
        }
        form input[type="checkbox"] {
            margin-right: 5px;
        }
        form input[type="submit"],
        form button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 10px;
        }
        form input[type="submit"]:hover,
        form button:hover {
            background-color: #0056b3;
        }
        p {
            margin-top: 20px;
        }
        p a {
            color: #007bff;
            text-decoration: none;
        }
        p a:hover {
            text-decoration: underline;
        }
    </style>
    <script>
        function togglePasswordVisibility() {
            var contraseñaInput = document.getElementById("contraseña");
            if (contraseñaInput.type === "password") {
                contraseñaInput.type = "text";
            } else {
                contraseñaInput.type = "password";
            }
        }
    </script>
</head>
<body>

    
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <h2>Iniciar Sesión</h2>
    <label for="nombre_usuario">Nombre de Usuario:</label>
    <input type="text" id="nombre_usuario" name="nombre_usuario" required><br><br>
    
    <label for="contraseña">Contraseña:</label>
    <input type="password" id="contraseña" name="contraseña" required>
    <input type="checkbox" onclick="togglePasswordVisibility()"> Mostrar Contraseña<br><br>
    
    <input type="submit" value="Iniciar Sesión">
    <button onclick="window.location.href='estadisticas.php'">Estadísticas</button>
    <button onclick="window.location.href='ticketcreate.php'">Crear un Ticket</button>
</form>


</body>
</html>