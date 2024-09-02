<?php
//se inicializa variable de sesión
session_start();

//si no existe la variable de sesión usuario_id, se redirige al login
if(!isset($_SESSION['usuario_id'])){
    header('Location:login.php');
    exit();
}
?>
<!-- Se llama el header desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/header.php"); ?>

    
<br>
<div class="p-4 mb-3 bg-light rounded-3 shadow-sm">
    <div class="container-fluid text-center">
        <h1 class="display-5 fw-bold">Bienvenid@ a tu perfil de usuario</h1>
    </div>
</div>

<div class="container my-3">
    <div class="main-body">
    
          <div class="row gutters-sm">
            <div class="col-md-4 mb-3">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex flex-column align-items-center text-center">
                    <img src="../../rs/avatar.jpg" alt="vecteezy.com" class="rounded-circle" width="150">
                    <div class="mt-3">
                      <h4><?php echo $_SESSION['usuario_nombre']?></h4>
                      <p class="text-secondary mb-1"><?php echo $_SESSION['usuario_tipo']?></p>
                      <p class="text-muted font-size-sm"><?php echo $_SESSION['correo']?></p>
                    </div>
                  </div>
                </div>
              </div>

            </div>
            <div class="col-md-8">
              <div class="card mb-3">
                <div class="card-body">
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Nombre</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                      <?php echo $_SESSION['nombre']?>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Apellidos</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                      <?php echo $_SESSION['apellidos']?>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Correo</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                      <?php echo $_SESSION['correo']?>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-sm-3">
                      <h6 class="mb-0">Tipo usuario</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                      <?php echo $_SESSION['usuario_tipo']?>
                    </div>
                  </div>
                  <hr>

                  <div class="row">
                    <div class="col-sm-12">
                      <a class="btn btn-outline-info" href="editar.php?txtID=<?php echo $_SESSION['usuario_id']?>">Editar</a>
                    </div>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
    </div>
<br>
<br>
<br>
<br>

<!-- Se llama el footer desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/footer.php"); ?>