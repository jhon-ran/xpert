<?php
//url para conexión en hosting
$url_base = "/xpert/";
//url de base en localhost para concatenar en la navbar y evitar errores de redirección (dinámica)
//$url_base = "http://localhost/xpert/";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de bienvenida</title>
</head>
<body>

    <div class="topnav">
        <!--<a class="active" href="#home">Inicio</a>-->
        <a href="<?php echo $url_base;?>secciones/cuenta/">Mi cuenta</a>
        <a href="<?php echo $url_base;?>secciones/usuarios/">Usuarios</a>
        <a href="<?php echo $url_base;?>secciones/tours/">Tours</a>
        <a href="<?php echo $url_base;?>secciones/cupones/">Cupones</a>
        <a href="<?php echo $url_base;?>secciones/staff/">Staff</a>
        <a href="<?php echo $url_base;?>secciones/transporte/">Transporte</a>
        <a href="<?php echo $url_base;?>secciones/notificaciones/">Notificaciones</a>
        <a href="<?php echo $url_base;?>secciones/reportes/">Reportes</a>
        <a href="../../cerrar.php">Cerrar</a>
    </div>