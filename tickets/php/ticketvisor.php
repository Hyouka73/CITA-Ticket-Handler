<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION["nombre_usuario"])) {
    // Si el usuario no está autenticado, redirigirlo a la página de inicio de sesión
    header("Location: ticketsesion1.php");
    exit();
}

// Incluir el archivo de conexión a la base de datos
require_once 'econect.php';

// Obtener el ID del usuario actual
$usuario_id = $_SESSION['usuario_id'];

// Consulta SQL para obtener los tickets asignados al usuario
$sql_tickets_asignados = "SELECT * FROM tickets WHERE AsignadoA = '$usuario_id'";

// Ejecutar la consulta
$resultado_tickets_asignados = mysqli_query($db, $sql_tickets_asignados);

// Verificar si se obtuvieron resultados
if ($resultado_tickets_asignados) {
    // Inicializar un array para almacenar los tickets asignados
    $ticketsAsignados = array();

    // Recorrer los resultados y almacenar los tickets asignados en el array
    while ($fila = mysqli_fetch_assoc($resultado_tickets_asignados)) {
        $ticketsAsignados[] = $fila;
    }
} else {
    // Si hubo un error en la consulta, mostrar un mensaje de error
    $error_message = "Error al obtener los tickets asignados: " . mysqli_error($db);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Perfil y Tickets</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            text-align: center;
        }

        .info {
            margin-bottom: 20px;
        }

        .ticket-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .ticket-card {
            width: calc(33.33% - 20px);
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            background-color: #fff;
        }

        .ticket-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .ticket-description {
            font-size: 16px;
            margin-bottom: 10px;
        }

        .ticket-info {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }

        .ticket-info:last-child {
            margin-bottom: 0;
        }

        form {
            text-align: center;
            margin-top: 20px;
        }

        form select,
        form textarea {
            width: calc(100% - 20px);
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-top: 5px;
        }

        form button {
            padding: 8px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        form button:hover {
            background-color: #0056b3;
        }

        .logout-link {
            display: block;
            margin-top: 20px;
            text-decoration: none;
            color: #007bff;
        }

        .logout-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="info">
        <h2>Perfil de Usuario</h2>
        <p>Bienvenido, <?php echo $_SESSION["nombre_usuario"]; ?></p>
        <!-- Aquí puedes mostrar la información del perfil del usuario, como nombre, correo electrónico, etc. -->
    </div>

    <h2>Tickets Asignados</h2>
    <div class="ticket-container">
        <?php if (isset($ticketsAsignados) && !empty($ticketsAsignados)): ?>
            <?php foreach ($ticketsAsignados as $ticket): ?>
                <div class="ticket-card">
                    <h3 class="ticket-title"><?php echo $ticket['Titulo']; ?></h3>
                    <p class="ticket-description"><?php echo $ticket['Descripcion']; ?></p>
                    <p class="ticket-info">Prioridad: <?php echo $ticket['Prioridad']; ?></p>
                    <p class="ticket-info">Tipo: <?php echo $ticket['Tipo']; ?></p>
                    <p class="ticket-info">Estado: <?php echo $ticket['Estado']; ?></p>
                    <p class="ticket-info">Fecha de creación: <?php echo $ticket['FechaCreacion']; ?></p>
                    <p class="ticket-info">Comentarios: <?php echo $ticket['Comentarios']; ?></p>

                    <?php if ($ticket['Estado'] != 'Cerrado'): ?>
                        <form action="actualizar_estado.php" method="POST">
                            <input type="hidden" name="ticket_id" value="<?php echo $ticket['TicketID']; ?>">
                            <label for="estado">Cambiar Estado:</label>
                            <select id="estado" name="estado" required>
                                <option value="Abierto">Abierto</option>
                                <option value="En Progreso">En Progreso</option>
                                <option value="Cerrado">Cerrado</option>
                                <option value="Pendiente">Pendiente</option>
                            </select>
                            <button type="submit">Guardar</button>
                        </form> 
                    <?php else: ?>
                        <p class="ticket-info">El ticket está cerrado y no se puede modificar.</p>
                    <?php endif; ?>

                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No tienes tickets asignados en este momento.</p>
        <?php endif; ?>
    </div>

    <a href="cerrar_sesion.php" class="logout-link">Cerrar Sesión</a> <!-- Enlace para cerrar sesión -->
</div>

</body>
</html>
