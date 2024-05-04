<?php
require_once 'econect.php';

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $titulo = $_POST['titulo'];
    $descripcion = $_POST['descripcion'];
    $prioridad = $_POST['prioridad'];
    $tipo = $_POST['tipo'];
    $estado = $_POST['estado'];
    $comentarios = $_POST['comentarios'];
    $asignado_a = $_POST['asignado_a'];

    // Consulta SQL para insertar un nuevo ticket
    $sql = "INSERT INTO Tickets (Titulo, Descripcion, Prioridad, Tipo, Estado, Comentarios, AsignadoA)
            VALUES ('$titulo', '$descripcion', '$prioridad', '$tipo', '$estado', '$comentarios', '$asignado_a')";

    // Ejecutar la consulta
    $insert_ticket = mysqli_query($db, $sql);

    // Preparar el tipo de alerta según el resultado
    if ($insert_ticket) {
        $alert_type = "success";
        $alert_message = "Ticket registrado correctamente.";
    } else {
        $alert_type = "error";
        $alert_message = "Error al registrar el ticket: " . mysqli_error($db);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registro de Ticket</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
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
        form table {
            width: 100%;
        }
        form table td {
            padding: 10px;
        }
        form table td:first-child {
            text-align: right;
        }
        form table td select, 
        form table td input[type="text"],
        form table td textarea {
            width: calc(100% - 20px);
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            margin-top: 5px;
        }
        form table td:last-child {
            text-align: center;
        }
        .btn-home {
            display: block;
            width: 30%; /* Ancho del botón */
            margin: 20px auto; /* Centrado horizontal */
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
        }
        .btn-home:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<script>
        // Obtener el tipo de alerta y el mensaje desde PHP
        var alertType = '<?php echo $alert_type; ?>';
        var alertMessage = '<?php echo $alert_message; ?>';

        // Mostrar la alerta correspondiente
        if (alertType === 'success') {
            Swal.fire('Éxito', alertMessage, 'success');
        } else if (alertType === 'error') {
            Swal.fire('Error', alertMessage, 'error');
        }
    </script>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <h2>Registro de Ticket</h2>
    <table>
        <tr>
            <td><label for="titulo">Título:</label></td>
            <td><input type="text" id="titulo" name="titulo" required></td>
        </tr>
        <tr>
            <td><label for="descripcion">Descripción:</label></td>
            <td><textarea id="descripcion" name="descripcion" required></textarea></td>
        </tr>
        <tr>
            <td><label for="prioridad">Prioridad:</label></td>
            <td>
                <select id="prioridad" name="prioridad" required>
                    <option value="Alta">Alta</option>
                    <option value="Media">Media</option>
                    <option value="Baja">Baja</option>
                </select>
            </td>
        </tr>
        <tr>
            <td><label for="tipo">Tipo:</label></td>
            <td>
                <select id="tipo" name="tipo" required>
                    <option value="Bug">Bug</option>
                    <option value="Solicitud de Característica">Solicitud de Característica</option>
                    <option value="Pregunta">Pregunta</option>
                    <option value="Otro">Otro</option>
                </select>
            </td>
        </tr>
        <tr>
            <td><label for="estado">Estado:</label></td>
            <td>
                <select id="estado" name="estado" required>
                    <option value="Abierto">Abierto</option>
                    <option value="En Progreso">En Progreso</option>
                    <option value="Cerrado">Cerrado</option>
                    <option value="Pendiente">Pendiente</option>
                </select>
            </td>
        </tr>
        <tr>
            <td><label for="comentarios">Comentarios:</label></td>
            <td><textarea id="comentarios" name="comentarios"></textarea></td>
        </tr>
        <tr>
            <td><label for="asignado_a">Asignado a:</label></td>
            <td>
                <select id="asignado_a" name="asignado_a" required>
                    <?php
                    // Consulta SQL para obtener todos los usuarios
                    $sql_usuarios = "SELECT UsuarioID, NombreUsuario FROM usuarios";
                    $resultado_usuarios = mysqli_query($db, $sql_usuarios);

                    // Verificar si se obtuvieron resultados
                    if ($resultado_usuarios) {
                        echo '<option value="">Selecciona un usuario</option>'; // Opción por defecto
                        while ($fila = mysqli_fetch_assoc($resultado_usuarios)) {
                            echo '<option value="' . $fila['UsuarioID'] . '">' . $fila['NombreUsuario'] . '</option>';
                        }
                        mysqli_free_result($resultado_usuarios);
                    } else {
                        echo '<option value="">Error al obtener usuarios</option>'; // Mensaje de error
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center"><input type="submit" value="Registrar"></td>
        </tr>
    </table>
    <a href="ticketsesion1.php" class="btn-home">Volver al inicio de sesión</a>
</form>

</body>
</html>