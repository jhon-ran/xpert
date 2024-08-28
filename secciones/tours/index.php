<?php include("../../bd.php"); 
//se inicializa variable de sesión
session_start();
//se guarda el valor de la variable de usuario logeado para usarla como modificador en tabla tours_historial
$id_usuario = $_SESSION['usuario_id'];
//******Inicia código para eliminar registro******
//Para recolectar información del url con el botón "eliminar" método GET
//Se verifica que el id exista en el url



if(isset($_GET['txtID'])){
    //Se crea variable para asignar el valor del id seleccionado
     //Si esta variable existe, se asigna ese valor, de lo contrario se queda
     $txtID = (isset($_GET['txtID']))?$_GET['txtID']:$_GET['txtID'];

    //******Inicia código para eliminar foto de carpeta tours******
    //buscar el archivo relacionado con la foto
    $sentencia = $conexion->prepare("SELECT foto FROM tours WHERE id=:id");
    $sentencia->bindParam(":id",$txtID);
    $sentencia->execute();
    //Mensaje de confirmación de borrado que activa Sweet Alert 2
    //Llama a código de templates/footer.php
    $mensaje="Tour eliminado";
    //se recupera solo un registro asosiado al id
    $foto_tour = $sentencia->fetch(PDO::FETCH_LAZY);

    //Si existe ese archivo de la foto o diferente de vacío
    if(isset($foto_tour["foto"]) && $foto_tour["foto"]!=""){
        //Si el archivo existe dentro de la carpeta actual
        if(file_exists("./".$foto_tour["foto"])){
            //Se elimina el archivo
            unlink("./".$foto_tour["foto"]);
        }
    }
    //******Termina código para eliminar foto de carpeta tours******

    
    //Se prepara sentencia para borrar dato seleccionado (id)
    $sentencia = $conexion->prepare("DELETE FROM tours WHERE id=:id");
    //Asignar los valores que vienen del método GET (id seleccionado por params)
    //Se asigna el valor de la variable a la sentencia
    $sentencia->bindParam(":id",$txtID);
    //Se ejecuta la sentencia con el valor asignado para borrar
    $sentencia->execute();
    //Redirecionar después de eliminar a la lista de puestos
    header("Location:index.php");
}
   //******Termina código para eliminar registro******

//******Inicia código para mostrar todos los registros******
//Se prepara sentencia para seleccionar los datos de tablas ubicaciones y tours en join. Explicación:
/*
LEFT JOIN: Se segura que se incluyan todos los registros de la tabla Tours en el resultado, incluso si no hay una coincidencia correspondiente en la tabla Ubicaciones.
SELECT: Especifica las columnas que se incluirán en el conjunto de resultados. Se incluyeron todas las columnas de la tabla Tours, y también las columnas relevantes de la tabla Ubicaciones.
ON: Especifica la condición para la unión, que en este caso es que el campo ubicacion en Tours coincida con el campo id en Ubicaciones.
*/
$sentencia = $conexion->prepare("SELECT 
    tours.id,
    tours.foto,
    tours.titulo,
    tours.incluyeTransporte,
    tours.duracion,
    tours.precioBase,
    ubicaciones.geo,
    ubicaciones.estado,
    ubicaciones.poblacion,
    ubicaciones.direccion,
    IF(likes.id_usuario IS NOT NULL, 1, 0) AS liked  /* Aquí se verifica si hay un 'like' */
FROM 
    tours
LEFT JOIN 
    ubicaciones ON tours.ubicacion = ubicaciones.id
LEFT JOIN 
    likes ON tours.id = likes.id_tour AND likes.id_usuario = :id_usuario");

$sentencia->bindParam(':id_usuario', $id_usuario);
$sentencia->execute();
$tours = $sentencia->fetchAll(PDO::FETCH_ASSOC);

//Para probar que se esté leyendo todos los datos de la tabla, descomentar
//print_r($lista_tbl_puestos);
//******Termina código para mostrar todos los registros******


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id_tour']) && isset($_POST['id_usuario'])) {
        $id_tour = $_POST['id_tour'];
        $id_usuario = $_POST['id_usuario'];
        
        // Verificar si ya existe un like del usuario para ese tour
        $verificar = $conexion->prepare("SELECT * FROM likes WHERE id_usuario = :id_usuario AND id_tour = :id_tour");
        $verificar->bindParam(':id_usuario', $id_usuario);
        $verificar->bindParam(':id_tour', $id_tour);
        $verificar->execute();
        
        if ($verificar->rowCount() > 0) {
            // Si existe, eliminar el like (dislike)
            $eliminar = $conexion->prepare("DELETE FROM likes WHERE id_usuario = :id_usuario AND id_tour = :id_tour");
            $eliminar->bindParam(':id_usuario', $id_usuario);
            $eliminar->bindParam(':id_tour', $id_tour);
            $eliminar->execute();
            echo json_encode(['status' => 'unliked']);
        } else {
            // Si no existe, insertar el like
            $insertar = $conexion->prepare("INSERT INTO likes (id_usuario, id_tour, fechaLike) VALUES (:id_usuario, :id_tour, NOW())");
            $insertar->bindParam(':id_usuario', $id_usuario);
            $insertar->bindParam(':id_tour', $id_tour);
            $insertar->execute();
            echo json_encode(['status' => 'liked']);
        }
        
    }
    exit;
}



?>

<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/header.php"); ?>

<br>
    <div class="p-5 mb-4 bg-light rounded-3">
        <div class="container-fluid py-5">
            <h2 class="display-5 fw-bold">Tours, excursiones y actividades</h2>
            <p class="col-md-8 fs-4">
                Aquí puedes encontrar todos los tours, excursiones o actividades que ofrecemos
            </p>
            <?php if($_SESSION["usuario_tipo"]=="admin"  || $_SESSION["usuario_tipo"]=="superadmin"):?>
                <a name="" id="" class="btn btn-primary btn-lg" href="crear.php" role="button" >Crear nuevo tour</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="cards">
    <?php foreach($tours as $registro){ ?>
        <article class="cardNuevo" data-id-tour="<?php echo $registro['id']; ?>">
            <div class="card__preview">
                <img src="<?php echo $registro['foto']?>" alt="...">
                <div class="card__price">
                    desde $<?php echo $registro['precioBase']?>
                </div>
            </div>
            <div class="card__content">
                <h4 class="card__title"><?php echo $registro['titulo']?></h4>
                <p class="card__address">
                    <?php echo $registro['poblacion']?>
                </p>
                <p class="card__description">
                    Descripción
                </p>
                <div class="card__bottom">
                    <div class="card__properties">
                        <?php echo $registro['duracion']?>hrs
                         <!--Se evalua si incluye transporte y de ser el caso se muestra en la tarjeta -->
                            <?php if($registro['incluyeTransporte'] == "sí"){ ?>
                                <?php echo "| + Transportación"?> 
                            <?php } ?>

                        <?php if($_SESSION["usuario_tipo"]=="admin"  || $_SESSION["usuario_tipo"]=="superadmin"):?>
                        <div class="dropdown">
                            <a href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <li><a class="dropdown-item" href="ver.php?txtID=<?php echo $registro['id']?>">Ver</a></li>
                            <li><a class="dropdown-item" href="editar.php?txtID=<?php echo $registro['id']?>">Editar</a></li>
                            <!--Se sustituye el link del registro por la función SweatAlert para confirmar borrado-->
                            <li><a class="dropdown-item" href="javascript:borrar(<?php echo $registro['id']?>);">Eliminar</a></li>
                            </ul>
                        </div>

                        <?php elseif($_SESSION["usuario_tipo"]=="ventas"):?>
                            <div class="dropdown">
                                <a href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <li><a class="dropdown-item" href="ver.php?txtID=<?php echo $registro['id']?>">Ver</a></li>
                                <li><a class="dropdown-item" href="editar.php?txtID=<?php echo $registro['id']?>">Editar</a></li>
                                </ul>
                            </div>
                        <?php else:?>
                            <div class="dropdown">
                                <a href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <li><a class="dropdown-item" href="ver.php?txtID=<?php echo $registro['id']?>">Ver</a></li>
                                </ul>
                            </div>
                            
                        <?php endif; ?>
                    
                    </div>
                    <button class="card__btn <?php echo ($registro['liked'] ? 'card__btn--like' : ''); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M19.5 12.572l-7.5 7.428l-7.5 -7.428a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572" />
                        </svg>
                    </button>
                </div>
            </div>
        </article>
        <?php } ?>
    </div>
    <br>

<!-- Se llama el footer desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/footer.php"); ?>

<script>
    const idUsuario = <?php echo json_encode($_SESSION['usuario_id']); ?>;
</script>
<!-- Para mostrar los likes-->
<script src="../../js/likes.js"> </script>