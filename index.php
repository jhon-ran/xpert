<?php
//inicio variable de sesión
session_start();
//si no existe la variable de sesión usuario_id, se redirige al login
if(!isset($_SESSION['usuario_id'])){
    header('Location:login.php');
    exit();
}
?>

<!-- Se llama el header desde los templates-->
<?php include("templates/header.php"); ?>

    <div class="p-5 mb-4 bg-light rounded-3">
        <div class="container-fluid py-5">
            <h1 class="display-5 fw-bold">Bienvenid@</h1>
            <p class="col-md-8 fs-4"> <?php echo $_SESSION['usuario_nombre']?></p>
            <p class="col-md-8 fs-4"> Ingresaste como <?php echo $_SESSION['usuario_tipo']?></p>
        </div>
    </div>
    

<!-- Se llama el footer desde los templates-->
<?php include("templates/footer.php"); ?>