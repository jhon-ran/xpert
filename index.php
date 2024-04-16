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
    <title>Document</title>
</head>
<body>
    <a href="cerrar.php">Cerrar</a>
    <br> 
    
    <h2>Bienvenid@ <?php echo $_SESSION['usuario_nombre']?></h2>
    <p>Este es el inicio de una aventura</p>
</body>
</html>

