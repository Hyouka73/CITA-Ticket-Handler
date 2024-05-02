<?php 
    //conexión
    $db = new mysqli(
        "localhost",
        "root",
        "",
        "tickets2"
    );
    //codificador de caracteres
    mysqli_query($db,"SET NAMES 'utf8'");

    if (!$db) {
        die("Error al conectar con la base de datos: " . mysqli_connect_error());
    }

?>