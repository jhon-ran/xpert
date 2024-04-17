<?php
//inicio variable de sesión
session_start();
//si no existe la variable de sesión usuario_id, se redirige al login
if(!isset($_SESSION['usuario_id'])){
    header('Location:login.html');
    exit();
}
?>

<!-- Se llama el header desde los templates-->
<?php include("templates/header.php"); ?>

    
    <h2>Bienvenid@ <?php echo $_SESSION['usuario_nombre']?></h2>
    <h3>Ingresaste como <?php echo $_SESSION['usuario_tipo']?></h3>
    <p>Este es el inicio de una aventura</p>

<!-- Se llama el footer desde los templates-->
<?php include("templates/footer.php"); ?>