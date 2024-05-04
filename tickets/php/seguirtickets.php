<?php
// Incluir el archivo de conexión a la base de datos
require_once 'econect.php';

// Consulta SQL para obtener todos los tickets junto con el nombre del usuario asignado
$sql_todos_tickets = "SELECT t.*, u.NombreUsuario AS nombre_asignado
                        FROM tickets t
                        LEFT JOIN usuarios u ON t.AsignadoA = u.UsuarioID";
// Ejecutar la consulta
$resultado_todos_tickets = mysqli_query($db, $sql_todos_tickets);

// Verificar si se obtuvieron resultados
if ($resultado_todos_tickets) {
    // Inicializar un array para almacenar los tickets agrupados por estado
    $ticketsPorEstado = array(
        'Abierto' => array(),
        'Pendiente' => array(),
        'En Progreso' => array(),
        'Cerrado' => array()
    );

    // Recorrer los resultados y almacenar los tickets en el array
    while ($fila = mysqli_fetch_assoc($resultado_todos_tickets)) {
        $estado = $fila['Estado'];
        $ticketsPorEstado[$estado][] = $fila;
    }
} else {
    // Si hubo un error en la consulta, mostrar un mensaje de error
    $error_message = "Error al obtener los tickets: " . mysqli_error($db);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Seguimiento de Tickets</title>
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

        .ticket-container {
            display: flex;
            flex-direction: row;
            overflow-x: auto;
            max-width: 100%;
            --rows: 1fr;

            /* Estilo para las barras de desplazamiento */
            scrollbar-color: #ccc transparent; /* Color de la barra de desplazamiento y fondo */
            scrollbar-width: thin; /* Ancho de la barra de desplazamiento */
        }

        /* Estilos para navegadores basados en WebKit (Chrome, Safari, Opera) */
        .ticket-container::-webkit-scrollbar {
            width: 8px; /* Ancho de la barra de desplazamiento */
            height: 8px; /* Altura de la barra de desplazamiento */
        }

        .ticket-container::-webkit-scrollbar-track {
            background-color: transparent; /* Color del fondo de la pista */
        }

        .ticket-container::-webkit-scrollbar-thumb {
            background-color: #ccc; /* Color de la barra de desplazamiento */
            border-radius: 4px; /* Radio de borde de la barra de desplazamiento */
        }

        .ticket-card {
            flex: 0 0 calc(33.33% - 20px);
            max-width: calc(33.33% - 20px);
            margin-right: 20px;
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

        .ticket-info {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
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

<div class="container">
    <h2>Seguimiento de Tickets</h2>

    <?php if (!empty($ticketsPorEstado)): ?>
        <?php foreach (array('Abierto', 'Pendiente', 'En Progreso', 'Cerrado') as $estado): ?>
            <?php if (!empty($ticketsPorEstado[$estado])): ?>
                <h3>Estado: <?php echo $estado; ?></h3>
                <div class="ticket-container">
                    <?php foreach ($ticketsPorEstado[$estado] as $ticket): ?>
                        <div class="ticket-card">
                            <h3 class="ticket-title"><?php echo $ticket['Titulo']; ?></h3>
                            <p class="ticket-info"><strong>Prioridad:</strong> <?php echo $ticket['Prioridad']; ?></p>
                            <p class="ticket-info"><strong>Tipo:</strong> <?php echo $ticket['Tipo']; ?></p>
                            <p class="ticket-info"><strong>Asignado a:</strong> <?php echo $ticket['nombre_asignado']; ?></p>
                            <p class="ticket-info"><strong>Fecha de creación:</strong> <?php echo $ticket['FechaCreacion']; ?></p>
                            <p class="ticket-info"><strong>Comentarios:</strong> <?php echo $ticket['Comentarios']; ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No hay tickets en este momento.</p>
    <?php endif; ?>
    <a href="ticketsesion1.php" class="btn-home">Volver al inicio de sesión</a>

</div>

</body>
</html>