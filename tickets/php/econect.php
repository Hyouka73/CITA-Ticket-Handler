<?php
// Conexión
$db = new mysqli("localhost", "root", "", "tickets2");

if (!$db) {
    // Si no se puede conectar a la base de datos, mostrar una alerta de error
    ?>
    <!DOCTYPE html>
    <html>
        <head>
            <title>Error de Conexión</title>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
        </head>
        <body>
            <script>
                Swal.fire({
                    title: 'Error',
                    text: '<?php echo mysqli_connect_error(); ?>',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
                </script>
    </body>
    </html>
    <?php
    die();
}
// Codificador de caracteres
mysqli_query($db, "SET NAMES 'utf8'");

?>