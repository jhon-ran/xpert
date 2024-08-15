

<!-- Se llama el header desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/header.php"); ?>

<br>
    <div class="p-5 mb-4 bg-light rounded-3">
        <div class="container-fluid py-5">
            <h2 class="display-5 fw-bold">Tours, excursiones y actividades</h2>
            <p class="col-md-8 fs-4">
                Aquí puedes encontrar todos los tours, excursiones o actividades que ofrecemos en México.
            </p>
            <?php if($_SESSION["usuario_tipo"]=="admin"  || $_SESSION["usuario_tipo"]=="superadmin"):?>
                <a name="" id="" class="btn btn-primary btn-lg" href="crear.php" role="button" >Crear nuevo tour</a>
            <?php endif; ?>
        </div>
    </div>

    <!--Nuevo look inicia-->
<div class="row row-cols-1 row-cols-md-4 g-3 my-2">
    <?php foreach($tours as $registro){ ?>
    <div class="col">
        <div class="card mx-auto" style="width: 100%;">
            <img src="<?php echo $registro['foto']?>" class="card-img-top" alt="...">
            <div class="card-body">
                <p class="card-text"><small class="text-muted"><?php echo $registro['poblacion']?></small></p>
                <h5 class="card-title"><?php echo $registro['titulo']?></h5>
                <div class="row justify-content-between">
                    <div class="col-7">
                        <p class="card-text">
                            <small class="text-muted"><?php echo $registro['duracion']?>hrs
                            <!--Se evalua si incluye transporte y de ser el caso se muestra en la tarjeta -->
                            <?php if($registro['incluyeTransporte'] == "sí"){ ?>
                                <?php echo "+ Transportación"?> 
                                <?php } ?>
                            </small></p>
                    </div>
                    <div class="col-4"><p class="card-text"><small class="text-muted">desde $<?php echo $registro['precioBase']?></small></p></div>
                </div>
                <?php if($_SESSION["usuario_tipo"]=="admin"  || $_SESSION["usuario_tipo"]=="superadmin"):?>
                    <!-- vieja vista con botones
                    <a href="editar.php?txtID=<?php echo $registro['id']?>" class="btn btn-primary">Editar</a>
                    <a name="" id="" class="btn btn-danger" href="javascript:borrar(<?php echo $registro['id']?>);">Eliminar</a>
                    -->
                    <div class="dropdown">
                        <a href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                        <li><a class="dropdown-item" href="editar.php?txtID=<?php echo $registro['id']?>">Editar</a></li>
                        <!--Se sustituye el link del registro por la función SweatAlert para confirmar borrado-->
                        <li><a class="dropdown-item" href="javascript:borrar(<?php echo $registro['id']?>);">Eliminar</a></li>
                        </ul>
                    </div>

                <?php elseif($_SESSION["usuario_tipo"]=="ventas"):?>
                    <!--<a href="editar.php?txtID=<?php echo $registro['id']?>" class="btn btn-primary">Editar</a>-->
                    <div class="dropdown">
                        <a href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                        <li><a class="dropdown-item" href="editar.php?txtID=<?php echo $registro['id']?>">Editar</a></li>
                        </ul>
                    </div>
                <?php else:?>
                    <a href="" class="btn btn-primary">Ver más</a>
                <?php endif; ?>
                </div>
            </div>
        </div>
    <?php }?>
</div>

    <!--Nuevo look termnia-->

<!-- Se llama el footer desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/footer.php"); ?>