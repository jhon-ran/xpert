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
<br>
<div class="p-4 mb-3 bg-light rounded-3 shadow-sm">
    <div class="container-fluid text-center">
        <h1 class="display-5 fw-bold">Bienvenid@</h1>
        <p class="lead fs-5"><?php echo $_SESSION['usuario_nombre']?></p>
        <p class="fs-6">Ingresaste como <span class="badge bg-primary"><?php echo $_SESSION['usuario_tipo']?></span></p>
    </div>
</div>

<!-- Sección de accesos rápidos -->
<div class="container my-4">
    <div class="row row-cols-1 row-cols-md-3 g-4">
    <?php if ($_SESSION["usuario_tipo"] == "admin" || $_SESSION["usuario_tipo"] == "superadmin"): ?>
        <!-- Acceso rápido a Mi Cuenta -->
        <div class="col">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-person-fill display-4 text-muted mb-3"></i>
                    <h5 class="card-title">Mi Cuenta <i class="fa fa-user-circle" aria-hidden="true"></i></h5>
                    <p class="card-text">Gestiona tu información personal</p>
                    <a href="<?php echo $url_base; ?>secciones/cuenta/" class="btn btn-outline-secondary">Ir a Mi Cuenta</a>
                </div>
            </div>
        </div>
        <!-- Acceso rápido a Tours -->
        <div class="col">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-map-fill display-4 text-success mb-3"></i>
                    <h5 class="card-title">Tours <i class="fa fa-globe" aria-hidden="true"></i></h5>
                    <p class="card-text">Explora y gestiona todos los tours disponibles</p>
                    <a href="<?php echo $url_base; ?>secciones/tours/" class="btn btn-outline-success">Ir a Tours</a>
                </div>
            </div>
        </div>
        <!-- Acceso rápido a Usuarios -->
        <div class="col">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-people-fill display-4 text-primary mb-3"></i>
                    <h5 class="card-title">Usuarios <i class="fa fa-users" aria-hidden="true"></i></h5>
                    <p class="card-text">Administra todos los usuarios registrados</p>
                    <a href="<?php echo $url_base; ?>secciones/usuarios/" class="btn btn-outline-primary">Ir a Usuarios</a>
                </div>
            </div>
        </div>
        <!-- Acceso rápido a Cupones -->
        <div class="col">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-ticket-fill display-4 text-warning mb-3"></i>
                    <h5 class="card-title">Cupones <i class="fa fa-money" aria-hidden="true"></i></h5>
                    <p class="card-text">Crea y gestiona cupones de descuento</p>
                    <a href="<?php echo $url_base; ?>secciones/cupones/" class="btn btn-outline-warning">Ir a Cupones</a>
                </div>
            </div>
        </div>
        <!-- Acceso rápido a Staff -->
        <div class="col">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-person-badge-fill display-4 text-info mb-3"></i>
                    <h5 class="card-title">Staff <i class="fa fa-address-book" aria-hidden="true"></i></h5>
                    <p class="card-text">Gestiona el staff de los tours</p>
                    <a href="<?php echo $url_base; ?>secciones/staff/" class="btn btn-outline-info">Ir a Staff</a>
                </div>
            </div>
        </div>
        <!-- Acceso rápido a Redes Sociales -->
        <div class="col">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-share-fill display-4 text-danger mb-3"></i>
                    <h5 class="card-title">Redes Sociales <i class="fa fa-instagram" aria-hidden="true"></i></h5>
                    <p class="card-text">Configura y administra las redes sociales de los tours</p>
                    <a href="<?php echo $url_base; ?>secciones/redes/" class="btn btn-outline-danger">Ir a Redes</a>
                </div>
            </div>
        </div>
        <!-- Acceso rápido a Notificaciones -->
        <div class="col">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-bell-fill display-4 text-secondary mb-3"></i>
                    <h5 class="card-title">Notificaciones <i class="fa fa-bell" aria-hidden="true"></i></h5>
                    <p class="card-text">Gestiona las notificaciones del sistema</p>
                    <a href="<?php echo $url_base; ?>secciones/notificaciones/" class="btn btn-outline-secondary">Ir a Notificaciones</a>
                </div>
            </div>
        </div>
        <!-- Acceso rápido a Reportes -->
        <div class="col">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-bar-chart-fill display-4 text-dark mb-3"></i>
                    <h5 class="card-title">Reportes <i class="fa fa-pie-chart" aria-hidden="true"></i></h5>
                    <p class="card-text">Visualiza y descarga reportes del sistema</p>
                    <a href="<?php echo $url_base; ?>secciones/reportes/" class="btn btn-outline-dark">Ir a Reportes</a>
                </div>
            </div>
        </div>
    </div>
    <?php elseif ($_SESSION["usuario_tipo"] == "ventas"): ?>
            <!-- Acceso rápido a Mi Cuenta -->
        <div class="col">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-person-fill display-4 text-muted mb-3"></i>
                    <h5 class="card-title">Mi Cuenta <i class="fa fa-user-circle" aria-hidden="true"></i></h5>
                    <p class="card-text">Gestiona tu información personal</p>
                    <a href="<?php echo $url_base; ?>secciones/cuenta/" class="btn btn-outline-secondary">Ir a Mi Cuenta</a>
                </div>
            </div>
        </div>
        <!-- Acceso rápido a Tours -->
        <div class="col">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-map-fill display-4 text-success mb-3"></i>
                    <h5 class="card-title">Tours <i class="fa fa-globe" aria-hidden="true"></i></h5>
                    <p class="card-text">Explora y gestiona todos los tours disponibles</p>
                    <a href="<?php echo $url_base; ?>secciones/tours/" class="btn btn-outline-success">Ir a Tours</a>
                </div>
            </div>
        </div>
        <!-- Acceso rápido a Cupones -->
        <div class="col">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-ticket-fill display-4 text-warning mb-3"></i>
                    <h5 class="card-title">Mis Cupones <i class="fa fa-money" aria-hidden="true"></i></h5>
                    <p class="card-text">Visualiza tus cupones de descuento asignados</p>
                    <a href="<?php echo $url_base; ?>secciones/mis_cupones/" class="btn btn-outline-warning">Ir a Cupones</a>
                </div>
            </div>
        </div>
        <?php elseif ($_SESSION["usuario_tipo"] == "cliente"): ?>
        <!-- Acceso rápido a Mi Cuenta -->
        <div class="col-12 col-md-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-person-fill display-4 text-muted mb-3"></i>
                    <h5 class="card-title">Mi Cuenta <i class="fa fa-user-circle" aria-hidden="true"></i></h5>
                    <p class="card-text">Gestiona tu información personal</p>
                    <a href="<?php echo $url_base; ?>secciones/cuenta/" class="btn btn-outline-secondary">Ir a Mi Cuenta</a>
                </div>
            </div>
        </div>
        <!-- Acceso rápido a Tours -->
        <div class="col-12 col-md-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-map-fill display-4 text-success mb-3"></i>
                    <h5 class="card-title">Tours <i class="fa fa-globe" aria-hidden="true"></i></h5>
                    <p class="card-text">Explora los tours disponibles</p>
                    <a href="<?php echo $url_base; ?>secciones/tours/" class="btn btn-outline-success">Ir a Tours</a>
                </div>
            </div>
        </div>
        <?php endif; ?>
</div>
<!-- Se llama el footer desde los templates-->
<?php include("templates/footer.php"); ?>