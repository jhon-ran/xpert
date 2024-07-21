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

<!doctype html>
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
            
        <nav class="navbar navbar-expand navbar-light bg-light">
            <ul class="nav navbar-nav">
                <?php if($_SESSION["usuario_tipo"]=="admin" || $_SESSION["usuario_tipo"]=="superadmin"):?>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $url_base;?>secciones/cuenta/">Mi cuenta</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $url_base;?>secciones/usuarios/">Usuarios</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $url_base;?>secciones/tours/">Tours</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $url_base;?>secciones/cupones/">Cupones</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $url_base;?>secciones/mis_cupones/">Mis cupones</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Staff
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <li><a class="dropdown-item" href="<?php echo $url_base;?>secciones/staff/">Administrar</a></li>
                        <li><a class="dropdown-item" href="<?php echo $url_base;?>secciones/tipo_staff/index.php">Tipo</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $url_base;?>secciones/notificaciones/">Notificaciones</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $url_base;?>secciones/reportes/">Reportes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $url_base;?>cerrar.php">Cerrar</a>
                </li>
                <?php elseif($_SESSION["usuario_tipo"]=="ventas"):?>
                    <li class="nav-item">
                    <a class="nav-link" href="<?php echo $url_base;?>secciones/cuenta/">Mi cuenta</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $url_base;?>secciones/tours/">Tours</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $url_base;?>secciones/mis_cupones/">Mis cupones</a>
                    </li>
                    <li class="nav-item">
                        <!--<a class="nav-link" href="../../cerrar.php">Cerrar</a>-->
                        <a class="nav-link" href="<?php echo $url_base;?>cerrar.php">Cerrar</a>
                    </li>
                    <?php elseif($_SESSION["usuario_tipo"]=="cliente"):?>
                        <li class="nav-item">
                        <a class="nav-link" href="<?php echo $url_base;?>secciones/cuenta/">Mi cuenta</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $url_base;?>secciones/tours/">Tours</a>
                        </li>
                        <li class="nav-item">
                            <!--<a class="nav-link" href="../../cerrar.php">Cerrar</a>-->
                            <a class="nav-link" href="<?php echo $url_base;?>cerrar.php">Cerrar</a>
                        </li>
                    <?php endif; ?>
            </ul>
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