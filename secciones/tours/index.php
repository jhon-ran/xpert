<!-- Importar conexión a BD-->
<?php include("../../bd.php");

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
            <li>Foto: <?php echo $registro['foto']?></li>
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
            <li>Staff: <?php echo $registro['atiende']?></li>
            <li>Desde: $<?php echo $registro['precioBase']?></li>
            <li>Descuento: $<?php echo $registro['descuento']?></li>
            <li>Redes sociales: <?php echo $registro['redes']?></li>
            <li>Fecha de creación: <?php echo $registro['fechaCreacion']?></li>
            <li><a href="editar.php?txtID=<?php echo $registro['id']?>">Editar</a> |
            <a href="index.php?txtID=<?php echo $registro['id']?>">Eliminar</a></li>
            <hr/>
        </ul>  
   
    <?php }?>

<!-- Se llama el footer desde los templates-->
<!-- ../../ sube 2 niveles para poder acceder al folder de templates desde la posición actual-->
<?php include("../../templates/footer.php"); ?>