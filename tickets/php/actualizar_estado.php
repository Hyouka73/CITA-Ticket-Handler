<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION["nombre_usuario"])) {
    // Si el usuario no está autenticado, redirigirlo a la página de inicio de sesión
    header("Location: inicio_sesion.php");
    exit();
}

// Incluir el archivo de conexión a la base de datos
require_once 'econect.php';

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener el ID del ticket y el nuevo estado enviado desde el formulario
    $ticket_id = $_POST['ticket_id'];
    $nuevo_estado = $_POST['estado'];

    // Consulta SQL para actualizar el estado del ticket en la base de datos
    $sql_actualizar_estado = "UPDATE tickets SET Estado = '$nuevo_estado' WHERE TicketID = '$ticket_id'";

    // Ejecutar la consulta
    if (mysqli_query($db, $sql_actualizar_estado)) {
        // Redireccionar de vuelta a la página anterior o a donde sea necesario
        header("Location: ticketvisor.php");
        exit();
    } else {
        // Si hay un error en la consulta, mostrar un mensaje de error
        echo "Error al actualizar el estado del ticket: " . mysqli_error($db);
    }
} else {
    // Si no se ha enviado el formulario correctamente, redirigir a donde sea necesario
    header("Location: ticketvisor.php");
    exit();
}
?>
