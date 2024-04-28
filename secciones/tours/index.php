<!-- Importar conexión a BD-->
<?php include("../../bd.php");

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
//Se prepara sentencia para seleccionar todos los datos 
$sentencia = $conexion->prepare("SELECT * FROM tours");
$sentencia->execute();
$tours = $sentencia->fetchAll(PDO::FETCH_ASSOC);
//Para probar que se esté leyendo todos los datos de la tabla, descomentar
//print_r($lista_tbl_puestos);
//******Termina código para mostrar todos los registros******

?>

<!-- Se llama el header desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/header.php"); ?>

    
<h1>Bienvenidos al index de tours</h1>
<a href="crear.php">Crear tours</a>
    <?php foreach($tours as $registro){ ?>

        <ul>
            <li>ID: <?php echo $registro['id']?></li>
            <li><?php echo $registro['titulo']?></li>
            <li><?php echo $registro['duracion']?> hora(s)</li>
            <li>Tipo de tour: <?php echo $registro['tipo']?></li>
            <li>Tamaño de grupo: <?php echo $registro['capacidad']?> personas</li>
            <li>Idiomas: <?php echo $registro['idiomas']?></li>
            <li>
                <img width="300" src="<?php echo $registro['foto']?>" alt="foto"/>
            </li>
            <li>Vista general: <?php echo $registro['vistaGeneral']?></li>
            <li>Destacado: <?php echo $registro['destacado']?></li>
            <li>Itinerario: <?php echo $registro['itinerario']?></li>
            <li>Incluido: <?php echo $registro['incluye']?></li>
            <li>Ubicación del tour: <?php echo $registro['ubicacion']?></li>
            <li>Qué traer: <?php echo $registro['queTraer']?></li>
            <li>Información adicional: <?php echo $registro['infoAdicional']?></li>
            <li>Políticas de cancelación: <?php echo $registro['polCancel']?></li>
            <li>Actividades para hacer: <?php echo $registro['actividades']?></li>
            <li>¿Incluye transportación? <?php echo $registro['incluyeTransporte']?></li>
            <li>Vehículo: <?php echo $registro['transporte']?></li>
            <li>Staff: <?php echo $registro['staff']?></li>
            <li>Desde: $<?php echo $registro['precioBase']?></li>
            <li>Descuento: $<?php echo $registro['descuento']?></li>
            <li>Redes sociales: <?php echo $registro['redes']?></li>
            <li>Fecha de creación: <?php echo $registro['fechaCreacion']?></li>
            <li><a href="editar.php?txtID=<?php echo $registro['id']?>">Editar</a> |
            <a href="index.php?txtID=<?php echo $registro['id']?>">Eliminar</a></li>
            <hr/>
        </ul>  
   
    <?php }?>


    <!--Nuevo look inicia-->
<?php foreach($tours as $registro){ ?>
<div class="card" style="width: 18rem;">
    <img src="<?php echo $registro['foto']?>" class="card-img-top" alt="...">
    <div class="card-body">
        <h5 class="card-title"><?php echo $registro['titulo']?></h5>
        <p class="card-text"><?php echo $registro['vistaGeneral']?></p>
        <a href="editar.php?txtID=<?php echo $registro['id']?>" class="btn btn-primary">Editar</a>
    </div>
</div>
<?php }?>
    <!--Nuevo look termnia-->

<!-- Se llama el footer desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/footer.php"); ?>