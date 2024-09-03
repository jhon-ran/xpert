<?php
//url para conexión en hosting
$url_base = "/xpert/";
//url de base en localhost para concatenar en la navbar y evitar errores de redirección (dinámica)
//$url_base = "http://localhost/xpert/";

//si no existe la variable de sesión usuario_id, se redirige al login
if(!isset($_SESSION['usuario_id'])){
    header('Location:'.$url_base.'login.php');
    exit();
}

//print_r($_SESSION['usuario_tipo'])
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Tours Xpert</title>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
        />
        <!-- Bootstrap CSS v5.2.1 -->
        <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
            crossorigin="anonymous"
        />
        <!-- estilo para personalizar -->
        <link rel="stylesheet" href="../../style.css">
        <!-- estilo para personalizar y que index.php puede acceder a él -->
        <link rel="stylesheet" href="style.css">
        <!-- font awesome -->
        <script src="https://kit.fontawesome.com/07ff07af43.js" crossorigin="anonymous"></script>
        <!-- cdn JQuery v.3.7.1-->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <!-- cdn DataTables v.1.12.1 -->
        <link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.dataTables.css" />
        <script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
        <!-- cdn para Sweet Alert 2, alertas de acciones -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>

    <body>
        <header>
            <nav class="navbar navbar-expand-lg navbar-light bg-light custom-navbar">
                <div class="container-fluid">
                    <!-- Botón de Menú para Pantallas Pequeñas -->
                    <button class="navbar-toggler custom-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <!-- Contenido de la Barra de Navegación -->
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <?php if ($_SESSION["usuario_tipo"] == "admin" || $_SESSION["usuario_tipo"] == "superadmin"): ?>
                             <li class="nav-item">
                                    <a class="nav-link" href="<?php echo $url_base; ?>index.php"><i class="fa fa-home" aria-hidden="true"></i></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo $url_base; ?>secciones/cuenta/">Mi cuenta</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo $url_base; ?>secciones/usuarios/">Usuarios</a>
                                </li>
                                <!-- Menú Desplegable para Tours -->
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownTours" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Tours
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownTours">
                                        <li><a class="dropdown-item" href="<?php echo $url_base; ?>secciones/tours/">Todos los tours</a></li>
                                        <li><a class="dropdown-item" href="<?php echo $url_base; ?>secciones/vehiculos/">Vehículos</a></li>
                                        <li><a class="dropdown-item" href="<?php echo $url_base; ?>secciones/idiomas/">Idiomas</a></li>
                                        <li><a class="dropdown-item" href="<?php echo $url_base; ?>secciones/ubicaciones/">Ubicaciones</a></li>
                                    </ul>
                                </li>
                                <!-- Menú Desplegable para Cupones -->
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownCupones" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Cupones
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownCupones">
                                        <li><a class="dropdown-item" href="<?php echo $url_base; ?>secciones/cupones/">Todos los cupones</a></li>
                                        <li><a class="dropdown-item" href="<?php echo $url_base; ?>secciones/cupones_usuarios/">Asignar</a></li>
                                        <li><a class="dropdown-item" href="<?php echo $url_base; ?>secciones/mis_cupones/">Mis cupones</a></li>
                                    </ul>
                                </li>
                                <!-- Menú Desplegable para Staff -->
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownStaff" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Staff
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownStaff">
                                        <li><a class="dropdown-item" href="<?php echo $url_base; ?>secciones/staff/">Administrar</a></li>
                                        <li><a class="dropdown-item" href="<?php echo $url_base; ?>secciones/tipo_staff/">Tipo</a></li>
                                    </ul>
                                </li>
                                <!-- Menú Desplegable para Redes Sociales -->
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownRedes" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Redes sociales
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownRedes">
                                        <li><a class="dropdown-item" href="<?php echo $url_base; ?>secciones/redes/">Administrar</a></li>
                                        <li><a class="dropdown-item" href="<?php echo $url_base; ?>secciones/logo_redes/">Logos</a></li>
                                    </ul>
                                </li>
                                <!-- Elementos Independientes para Administración -->
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo $url_base; ?>secciones/notificaciones/">Notificaciones</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo $url_base; ?>secciones/reportes/">Reportes</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo $url_base; ?>cerrar.php">Cerrar</a>
                                </li>
                            <?php elseif ($_SESSION["usuario_tipo"] == "ventas"): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo $url_base; ?>index.php"><i class="fa fa-home" aria-hidden="true"></i></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo $url_base; ?>secciones/cuenta/">Mi cuenta</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo $url_base; ?>secciones/tours/">Tours</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo $url_base; ?>secciones/mis_cupones/">Mis cupones</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo $url_base; ?>cerrar.php">Cerrar</a>
                                </li>
                            <?php elseif ($_SESSION["usuario_tipo"] == "cliente"): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo $url_base; ?>index.php"><i class="fa fa-home" aria-hidden="true"></i></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo $url_base; ?>secciones/cuenta/">Mi cuenta</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo $url_base; ?>secciones/tours/">Tours</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo $url_base; ?>cerrar.php">Cerrar</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>       

        <main class="container">
            <!--Inicia código de mensaje de alerta cuando se borra registro-->
        <!--Si hay algo en el métod get-->
        <?php if(isset($_GET['mensaje'])){ ?>
            <!--se corre el mensaje de eliminado en línea 19-->
            <script>
                Swal.fire({icon:"success", title:"<?php echo $_GET['mensaje'];?>"});
            </script>
        <?php } ?>
        <!--Termina código de mensaje de alerta cuando se borra registro-->