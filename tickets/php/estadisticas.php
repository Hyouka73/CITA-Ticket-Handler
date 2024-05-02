<!DOCTYPE html>
<html>
<head>
    <title>Estadísticas de Tickets</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        canvas {
            max-width: 800px;
            margin: 0 auto;
        }
    </style>
</head>
<body>

<div class="container my-5">
    <h2 class="text-center mb-4">Estadísticas de Tickets</h2>
    <div class="row justify-content-center mb-4">
        <div class="col-md-6">
            <div class="form-group">
                <label for="graph-select" class="font-weight-bold">Selecciona el tipo de gráfico:</label>
                <select id="graph-select" class="form-control">
                    <option value="bar">Gráfico de Barras</option>
                    <option value="pie">Gráfico de Pastel</option>
                    <option value="line">Gráfico de Línea</option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="data-select" class="font-weight-bold">Selecciona los datos a mostrar:</label>
                <select id="data-select" class="form-control">
                    <option value="estado">Tickets por Estado</option>
                    <option value="usuario">Tickets por Usuario</option>
                    <option value="prioridad">Tickets por Prioridad</option>
                    <option value="Tipo">Tickets por Tipo</option>
                </select>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <canvas id="myChart"></canvas>
        </div>
    </div>
    <div class="row justify-content-center mt-4">
        <div class="col-md-6 text-center">
            <button onclick="window.location.href='ticketsesion1.php'" class="btn btn-primary">Regresar al inicio de sesión</button>
        </div>
    </div>
</div>
<?php
// Conexión a la base de datos
require_once 'econect.php';

// Función para obtener los datos de tickets por estado
function getTicketsPorEstado($db) {
    $sql_estado_tickets = "SELECT Estado, COUNT(*) AS cantidad FROM tickets GROUP BY Estado";
    $resultado_estado_tickets = mysqli_query($db, $sql_estado_tickets);

    $tickets_por_estado = array();
    if ($resultado_estado_tickets) {
        while ($fila = mysqli_fetch_assoc($resultado_estado_tickets)) {
            $tickets_por_estado[$fila['Estado']] = $fila['cantidad'];
        }
    }

    return $tickets_por_estado;
}

// Función para obtener los datos de tickets por usuario
function getTicketsPorUsuario($db) {
    $sql_tickets_por_usuario = "SELECT u.NombreUsuario, COUNT(t.TicketID) AS cantidad FROM usuarios u LEFT JOIN tickets t ON u.UsuarioID = t.AsignadoA GROUP BY u.NombreUsuario";

    $resultado_tickets_por_usuario = mysqli_query($db, $sql_tickets_por_usuario);

    $tickets_por_usuario = array();
    if ($resultado_tickets_por_usuario) {
        while ($fila = mysqli_fetch_assoc($resultado_tickets_por_usuario)) {
            $tickets_por_usuario[$fila['NombreUsuario']] = $fila['cantidad'];
        }
    }

    return $tickets_por_usuario;
}

// Función para obtener los datos de tickets por prioridad
function getTicketsPorPrioridad($db) {
    $sql_prioridad_tickets = "SELECT Prioridad, COUNT(*) AS cantidad FROM tickets GROUP BY Prioridad";
    $resultado_prioridad_tickets = mysqli_query($db, $sql_prioridad_tickets);

    $tickets_por_prioridad = array();
    if ($resultado_prioridad_tickets) {
        while ($fila = mysqli_fetch_assoc($resultado_prioridad_tickets)) {
            $tickets_por_prioridad[$fila['Prioridad']] = $fila['cantidad'];
        }
    }

    return $tickets_por_prioridad;
}

// Función para obtener los datos de tickets por tipo
function getTicketsPorTipo($db) {
    $sql_tipo_tickets = "SELECT Tipo, COUNT(*) AS cantidad FROM tickets GROUP BY Tipo";
    $resultado_tipo_tickets = mysqli_query($db, $sql_tipo_tickets);

    $tickets_por_tipo = array();
    if ($resultado_tipo_tickets) {
        while ($fila = mysqli_fetch_assoc($resultado_tipo_tickets)) {
            $tickets_por_tipo[$fila['Tipo']] = $fila['cantidad'];
        }
    }

    return $tickets_por_tipo;
}


?>

<script>
    // Obtener el contexto del lienzo
    var ctx = document.getElementById('myChart').getContext('2d');

    // Función para actualizar la gráfica
    function actualizarGrafica(tipoGrafica, datosGrafica) {
        console.log(datosGrafica); // Imprimir los datos en la consola
        myChart.destroy(); // Destruir la instancia del gráfico actual

        // Crear la nueva instancia del gráfico
        myChart = new Chart(ctx, {
            type: tipoGrafica,
            data: {
                labels: Object.keys(datosGrafica),
                datasets: [{
                    label: (tipoGrafica === 'pie') ? '' : Object.keys(datosGrafica)[0],
                    data: Object.values(datosGrafica),
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    
    // Obtener los datos iniciales de tickets por estado
    var ticketsPorEstado = <?php echo json_encode(getTicketsPorEstado($db)); ?>;
    var ticketsPorUsuario = <?php echo json_encode(getTicketsPorUsuario($db)); ?>;
    // Obtener los datos iniciales de tickets por prioridad y tipo
    var ticketsPorPrioridad = <?php echo json_encode(getTicketsPorPrioridad($db)); ?>;
    var ticketsPorTipo = <?php echo json_encode(getTicketsPorTipo($db)); ?>;

    // Crear la instancia inicial del gráfico
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: Object.keys(ticketsPorEstado),
            datasets: [{
                label: 'Tickets por Estado',
                data: Object.values(ticketsPorEstado),
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    // Evento para cambiar el tipo de gráfico
    document.getElementById('graph-select').addEventListener('change', function () {
        var tipoGrafica = this.value;
        var datosGrafica;
        if (document.getElementById('data-select').value === 'estado') {
            datosGrafica = ticketsPorEstado;
        } else if (document.getElementById('data-select').value === 'usuario') {
            datosGrafica = ticketsPorUsuario;
        } else if (document.getElementById('data-select').value === 'prioridad') {
            datosGrafica = ticketsPorPrioridad;
        } else if (document.getElementById('data-select').value === 'Tipo') {
            datosGrafica = ticketsPorTipo;
        }
        actualizarGrafica(tipoGrafica, datosGrafica);
    });


    // Evento para cambiar los datos a mostrar
    document.getElementById('data-select').addEventListener('change', function () {
        var tipoGrafica = document.getElementById('graph-select').value;
        var datosGrafica;
        if (document.getElementById('data-select').value === 'estado') {
            datosGrafica = ticketsPorEstado;
        } else if (document.getElementById('data-select').value === 'usuario') {
            datosGrafica = ticketsPorUsuario;
        } else if (document.getElementById('data-select').value === 'prioridad') {
            datosGrafica = ticketsPorPrioridad;
        } else if (document.getElementById('data-select').value === 'Tipo') {
            datosGrafica = ticketsPorTipo;
        }
        actualizarGrafica(tipoGrafica, datosGrafica);
    });
</script>

</body>
</html>
