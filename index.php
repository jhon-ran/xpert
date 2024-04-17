<?php
//inicio variable de sesión
session_start();
//si no existe la variable de sesión usuario_id, se redirige al login
if(!isset($_SESSION['usuario_id'])){
    header('Location:login.html');
    exit();
}

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
        <a class="active" href="#home">Inicio</a>
        <a href="#news">Tours</a>
        <a href="#contact">Contact</a>
        <a href="cerrar.php">Cerrar</a>
    </div>

    
    <h2>Bienvenid@ <?php echo $_SESSION['usuario_nombre']?></h2>
    <h3>Ingresaste como <?php echo $_SESSION['usuario_tipo']?></h3>
    <p>Este es el inicio de una aventura</p>
</body>
</html>

